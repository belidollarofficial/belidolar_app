<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\BankAccount;
use App\Models\PayoutMethod;
use App\Models\RateRange;
use App\Models\Transaction;
use App\Support\SiteContent;
use App\Support\WhatsAppMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OrderForm extends Component
{
    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $paypalAccountName = '';

    public string $accountType = 'indonesia';

    public string $service = 'topup';

    public string $amount = '';

    public ?int $bankAccountId = null;

    public string $payoutType = 'bank';

    public ?int $payoutMethodId = null;

    public string $payoutAccountNumber = '';

    public string $merchantName = '';

    public string $merchantUrl = '';

    public string $paymentMethod = '';

    public string $paymentLink = '';

    public string $notes = '';

    public string $website = '';

    public ?string $successCode = null;

    public ?string $successUrl = null;

    public function mount(): void
    {
        // Preselect layanan dari query string (?service=topup|convert_usd|invoice).
        $service = (string) request()->query('service', '');

        if ($service !== '' && array_key_exists($service, Transaction::SERVICES)) {
            $this->service = $service;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'paypalAccountName' => ['nullable', 'string', 'max:255'],
            'accountType' => ['nullable', 'string', Rule::in(array_keys(Transaction::ACCOUNT_TYPES))],
            'service' => ['required', 'string', 'in:'.implode(',', array_keys(Transaction::SERVICES))],
            'amount' => ['required', 'numeric', 'min:1'],
            'bankAccountId' => ['nullable', 'integer', 'exists:bank_accounts,id'],
            'payoutType' => ['nullable', 'string', Rule::in(array_keys(PayoutMethod::TYPES))],
            'payoutMethodId' => ['nullable', 'integer', 'exists:payout_methods,id'],
            'payoutAccountNumber' => ['nullable', 'string', 'max:100'],
            'merchantName' => ['nullable', 'string', 'max:255'],
            'merchantUrl' => ['nullable', 'string', 'url', 'max:500'],
            'paymentMethod' => ['nullable', 'string', 'max:255'],
            'paymentLink' => ['nullable', 'string', 'url', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            // Honeypot anti-bot: field tersembunyi yang hanya diisi bot.
            'website' => ['prohibited'],
        ];
    }

    private function serviceUsesTopupRate(): bool
    {
        return in_array($this->service, Transaction::PAYABLE_SERVICES, true);
    }

    /**
     * Rate yang tampil reaktif sesuai nominal + layanan yang dipilih.
     *
     * Convert USD ke IDR memakai rentang rate (per bracket nominal).
     * Top Up & Bayar Invoice memakai rate tunggal yang diatur di admin.
     *
     * @return array{type: string, min?: float, max?: float, rate: float, estimated?: float}|null
     */
    public function getRateInfoProperty(): ?array
    {
        if (! is_numeric($this->amount) || (float) $this->amount <= 0) {
            return null;
        }

        $amount = (float) $this->amount;

        if ($this->service === 'convert_usd') {
            $range = RateRange::rateFor($amount);

            if (! $range) {
                return null;
            }

            return [
                'type' => 'range',
                'min' => (float) $range->min_amount,
                'max' => (float) $range->max_amount,
                'rate' => (float) $range->rate,
                'estimated' => (float) $range->rate * $amount,
            ];
        }

        if ($this->serviceUsesTopupRate()) {
            $rate = (float) SiteContent::setting('rate.topup', 18300);

            return [
                'type' => 'single',
                'rate' => $rate,
                'estimated' => $rate * $amount,
            ];
        }

        return null;
    }

    /**
     * Estimasi fee Top Up untuk akun Luar Negeri, diatur admin lewat
     * pengaturan kategori "fee" (fee.min & fee.max).
     */
    public function getFeeEstimateProperty(): ?string
    {
        if ($this->service !== 'topup' || $this->accountType !== 'luar_negeri') {
            return null;
        }

        $min = (float) SiteContent::setting('fee.min', 5);
        $max = (float) SiteContent::setting('fee.max', 15);

        if ($max < $min) {
            [$min, $max] = [$max, $min];
        }

        return '$'.self::formatFeeAmount($min).'-$'.self::formatFeeAmount($max);
    }

    private static function formatFeeAmount(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    /**
     * Daftar rekening bank tujuan transfer (Top Up, Invoice & Jasa Bayar Online).
     */
    public function getBankAccountsProperty()
    {
        return BankAccount::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Daftar metode pencairan sesuai tipe yang dipilih (Convert).
     */
    public function getPayoutMethodsProperty()
    {
        return PayoutMethod::query()
            ->where('type', $this->payoutType)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function updatedPayoutType(): void
    {
        $this->payoutMethodId = null;
    }

    /**
     * Informasi jam operasional & estimasi lama proses (diatur admin).
     *
     * @return array{hours: array<int, string>, processing_time: ?string, note: ?string}
     */
    public function getOperasionalInfoProperty(): array
    {
        return [
            'hours' => array_values(array_filter([
                SiteContent::setting('operasional.weekday'),
                SiteContent::setting('operasional.weekend'),
                SiteContent::setting('operasional.holiday'),
            ])),
            'processing_time' => SiteContent::setting('operasional.processing_time'),
            'note' => SiteContent::setting('operasional.note'),
        ];
    }

    public function submit(): void
    {
        // Rate limit berbasis IP: maksimal 10 order per menit.
        $throttleKey = 'order:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $this->addError('form', 'Terlalu banyak permintaan. Silakan coba lagi beberapa saat lagi.');

            return;
        }

        RateLimiter::hit($throttleKey, 60);

        $rules = $this->rules();

        if (in_array($this->service, ['topup', 'convert_usd'], true)) {
            $rules['paypalAccountName'][] = 'required';
        }

        if ($this->service === 'topup') {
            $rules['accountType'][] = 'required';
        }

        if ($this->service === 'invoice') {
            $rules['paymentLink'][] = 'required';
        }

        if ($this->serviceUsesTopupRate()) {
            $rules['bankAccountId'][] = 'required';
        }

        if ($this->service === 'convert_usd') {
            $rules['payoutMethodId'][] = 'required';
            $rules['payoutAccountNumber'][] = 'required';
        }

        if ($this->service === 'pay_online') {
            $rules['merchantName'][] = 'required';
            $rules['merchantUrl'][] = 'required';
            $rules['paymentMethod'][] = 'required';
        }

        $data = $this->validate($rules);

        $customerPhone = WhatsAppMessage::normalizePhone($data['phone']);

        $payable = null;
        if ($this->serviceUsesTopupRate()) {
            $rate = (float) SiteContent::setting('rate.topup', 18300);
            $payable = round($rate * (float) $data['amount'], 2);
        }

        $bank = $this->serviceUsesTopupRate() && $this->bankAccountId
            ? BankAccount::find($this->bankAccountId)
            : null;

        $payout = $this->service === 'convert_usd' && $this->payoutMethodId
            ? PayoutMethod::find($this->payoutMethodId)
            : null;

        $keepsPaypalDetails = in_array($this->service, ['topup', 'convert_usd'], true);

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'customer_name' => $data['name'],
            'customer_phone' => $customerPhone ?: $data['phone'],
            'email' => $keepsPaypalDetails ? ($data['email'] ?: null) : null,
            'paypal_account_name' => $keepsPaypalDetails ? ($data['paypalAccountName'] ?: null) : null,
            'account_type' => $this->service === 'topup' ? $data['accountType'] : null,
            'service' => $data['service'],
            'amount' => $data['amount'],
            'payable_amount' => $payable,
            'payment_link' => $data['paymentLink'] ?: null,
            'bank_name' => $bank?->bank_name,
            'bank_account_name' => $bank?->account_name,
            'bank_account_number' => $bank?->account_number,
            'payout_type' => $payout?->type,
            'payout_method' => $payout?->name,
            'payout_account_number' => $this->service === 'convert_usd' ? ($data['payoutAccountNumber'] ?: null) : null,
            'merchant_name' => $this->service === 'pay_online' ? $data['merchantName'] : null,
            'merchant_url' => $this->service === 'pay_online' ? $data['merchantUrl'] : null,
            'payment_method' => $this->service === 'pay_online' ? $data['paymentMethod'] : null,
            'fee_estimate' => $this->service === 'topup' ? $this->feeEstimate : null,
            'notes' => $data['notes'] ?: null,
            'status' => 'pending',
            'source' => 'website',
        ]);

        $this->successCode = $transaction->transaction_code;
        $this->successUrl = $transaction->adminChatLink();

        $this->dispatch('order-created', url: $this->successUrl, code: $this->successCode);
    }

    public function resetForm(): void
    {
        $this->reset([
            'name', 'phone', 'email', 'paypalAccountName', 'accountType', 'amount',
            'bankAccountId', 'payoutMethodId', 'payoutAccountNumber',
            'merchantName', 'merchantUrl', 'paymentMethod',
            'paymentLink', 'notes', 'successCode', 'successUrl',
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.landing-page.components.order-form');
    }
}

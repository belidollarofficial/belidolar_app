<?php

namespace Tests\Feature;

use App\Livewire\LandingPage\Components\OrderForm;
use App\Models\BankAccount;
use App\Models\PayoutMethod;
use App\Models\Setting;
use App\Models\Transaction;
use App\Support\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class OrderFormTest extends TestCase
{
    use RefreshDatabase;

    private BankAccount $bank;

    private PayoutMethod $payout;

    protected function setUp(): void
    {
        parent::setUp();

        SiteContent::flush();

        $this->bank = BankAccount::create([
            'bank_name' => 'BCA',
            'account_name' => 'Beli Dolar',
            'account_number' => '1234567890',
            'sort_order' => 1,
        ]);

        $this->payout = PayoutMethod::create([
            'type' => 'bank',
            'name' => 'BCA',
            'sort_order' => 1,
        ]);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'phone' => '081290859343',
            'email' => 'budi@example.com',
            'paypalAccountName' => 'Budi Santoso',
            'service' => 'topup',
            'amount' => 50,
            'bankAccountId' => $this->bank->id,
            'payoutType' => 'bank',
            'payoutMethodId' => $this->payout->id,
            'payoutAccountNumber' => '1234567890',
            'notes' => 'Top up segera',
            'termsAccepted' => true,
        ], $overrides);
    }

    public function test_order_creates_transaction_and_wa_link(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData())
            ->call('submit')
            ->assertHasNoErrors();

        $code = $component->get('successCode');
        $url = $component->get('successUrl');

        $this->assertNotNull($code);
        $this->assertStringStartsWith('ZP-', $code);
        $this->assertTrue(str_starts_with($url, 'https://wa.me/6281290859343?text='));

        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '6281290859343',
            'email' => 'budi@example.com',
            'paypal_account_name' => 'Budi Santoso',
            'service' => 'topup',
            'amount' => 50,
            'payable_amount' => 915000,
            'bank_name' => null,
            'bank_account_number' => null,
            'status' => 'pending',
        ]);

        $message = rawurldecode(explode('?text=', $url, 2)[1] ?? '');
        $this->assertStringContainsString('Top Up Saldo PayPal', $message);
        $this->assertStringContainsString('$50.00', $message);
        $this->assertStringContainsString('Rp 915.000', $message);
        $this->assertStringContainsString($code, $message);
        $this->assertStringContainsString('Budi Santoso', $message);
    }

    public function test_topup_directly_redirects_to_whatsapp(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData())
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertNotNull($component->get('successCode'));
        $this->assertStringStartsWith('https://wa.me/', $component->get('successUrl'));

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'bank_name' => null,
            'bank_account_number' => null,
        ]);
    }

    public function test_order_form_dispatches_service_change_for_terms(): void
    {
        Livewire::test(OrderForm::class)
            ->set('service', 'convert_usd')
            ->assertDispatched('order-service-changed', service: 'convert_usd');
    }

    public function test_topup_does_not_show_bank_account_field(): void
    {
        Livewire::test(OrderForm::class)
            ->set(['service' => 'topup', 'amount' => 50])
            ->assertDontSee('Bank Tujuan Transfer');
    }

    public function test_convert_records_payout_method(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData(['service' => 'convert_usd', 'amount' => 100]))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'convert_usd',
            'payout_type' => 'bank',
            'payout_method' => 'BCA',
            'payout_account_number' => '1234567890',
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Nomor Rekening / E-Wallet: 1234567890', $message);
    }

    public function test_convert_accepts_manual_bank_name(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'convert_usd',
                'amount' => 100,
                'payoutMethodId' => 'other',
                'payoutMethodOther' => 'Bank Jago',
                'payoutAccountNumber' => '1234567890',
            ]))
            ->assertSee('Nama Bank / E-Wallet Lainnya')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'convert_usd',
            'payout_type' => 'bank',
            'payout_method' => 'Bank Jago',
            'payout_account_number' => '1234567890',
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Metode Pencairan: Bank - Bank Jago', $message);
    }

    public function test_convert_requires_manual_bank_name_when_other_selected(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'convert_usd',
                'payoutMethodId' => 'other',
                'payoutMethodOther' => '',
            ]))
            ->call('submit')
            ->assertHasErrors('payoutMethodOther');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_topup_submits_without_bank_account(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['bankAccountId' => null]))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'bank_name' => null,
            'bank_account_number' => null,
        ]);
    }

    public function test_invoice_order_hides_and_clears_paypal_fields(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'invoice',
                'amount' => 150,
                'paymentLink' => 'https://paypal.com/invoice/abc123',
            ]))
            ->assertDontSee('Nama Akun Paypal')
            ->assertDontSee('Email/Username Paypal')
            ->assertDontSee('Bank Tujuan Transfer')
            ->call('submit')
            ->assertHasNoErrors();

        $transaction = Transaction::latest('id')->first();

        $this->assertNull($transaction->email);
        $this->assertNull($transaction->paypal_account_name);
        $this->assertNull($transaction->bank_name);
        $this->assertNull($transaction->bank_account_number);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringNotContainsString('Email PayPal', $message);
        $this->assertStringNotContainsString('Nama Akun PayPal', $message);
        $this->assertStringNotContainsString('Bank Tujuan', $message);
    }

    public function test_invoice_submits_without_bank_account(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'invoice',
                'bankAccountId' => null,
                'paymentLink' => 'https://paypal.com/invoice/abc123',
            ]))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'invoice',
            'bank_name' => null,
            'bank_account_number' => null,
        ]);
    }

    public function test_topup_overseas_stores_fee_estimate_and_shows_it_in_wa_message(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData(['accountType' => 'luar_negeri', 'amount' => 50]))
            ->assertSee('Estimasi Fee')
            ->assertSee('$5-$15')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'account_type' => 'luar_negeri',
            'fee_estimate' => '$5-$15',
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Jenis Akun: Luar Negeri', $message);
        $this->assertStringContainsString('Estimasi Fee: $5-$15', $message);
    }

    public function test_topup_fee_estimate_is_adjustable_from_settings(): void
    {
        Setting::create([
            'category' => 'fee',
            'key' => 'min',
            'label' => 'Estimasi Fee Minimum',
            'type' => 'number',
            'value' => '8',
        ]);

        Setting::create([
            'category' => 'fee',
            'key' => 'max',
            'label' => 'Estimasi Fee Maksimum',
            'type' => 'number',
            'value' => '20',
        ]);

        SiteContent::flush();

        Livewire::test(OrderForm::class)
            ->set(['service' => 'topup', 'accountType' => 'luar_negeri', 'amount' => 10])
            ->assertSee('$8-$20');
    }

    public function test_topup_indonesia_does_not_show_fee_estimate(): void
    {
        Livewire::test(OrderForm::class)
            ->set(['service' => 'topup', 'accountType' => 'indonesia', 'amount' => 10])
            ->assertDontSee('Estimasi Fee');
    }

    public function test_pay_online_creates_transaction_with_merchant_details_and_total(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'pay_online',
                'amount' => 100,
                'merchantName' => 'Amazon',
                'merchantUrl' => 'https://www.amazon.com/checkout',
                'paymentMethod' => 'PayPal',
            ]))
            ->assertSee('Nama Tempat / Merchant Belanja')
            ->assertSee('Link / URL Situs Belanja')
            ->assertSee('Metode Pembayaran')
            ->assertSee('Nominal Pembelian')
            ->assertDontSee('Bank Tujuan Transfer')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'pay_online',
            'amount' => 100,
            'payable_amount' => 1830000,
            'merchant_name' => 'Amazon',
            'merchant_url' => 'https://www.amazon.com/checkout',
            'payment_method' => 'PayPal',
            'bank_name' => null,
            'bank_account_number' => null,
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Jasa Bayar Online', $message);
        $this->assertStringContainsString('Nama Tempat / Merchant: Amazon', $message);
        $this->assertStringContainsString('Link / URL Situs: https://www.amazon.com/checkout', $message);
        $this->assertStringContainsString('Metode Pembayaran: PayPal', $message);
        $this->assertStringContainsString('Total Bayar: Rp 1.830.000', $message);
    }

    public function test_pay_online_requires_merchant_fields(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['service' => 'pay_online', 'amount' => 100]))
            ->call('submit')
            ->assertHasErrors(['merchantName', 'merchantUrl', 'paymentMethod']);

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_wd_permanent_order_only_requires_name_phone_and_notes(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set([
                'name' => 'Budi Santoso',
                'phone' => '081290859343',
                'service' => 'wd_akun_180',
                'notes' => 'Akun permanen 180 hari',
                'termsAccepted' => true,
            ])
            ->assertSee('Catatan (Opsional)')
            ->assertDontSee('Email/Username Paypal')
            ->assertDontSee('Bank Tujuan Transfer')
            ->assertDontSee('Nominal')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'wd_akun_180',
            'amount' => 0,
            'notes' => 'Akun permanen 180 hari',
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('WD Akun Permanen 180 Hari', $message);
        $this->assertStringContainsString('Catatan: Akun permanen 180 hari', $message);
        $this->assertStringNotContainsString('Nominal:', $message);
    }

    public function test_admin_fee_is_charged_below_threshold_for_payable_services(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData(['amount' => 10]))
            ->assertSee('Biaya Admin')
            ->assertSee('Rp 15.000')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'amount' => 10,
            'payable_amount' => 198000,
            'admin_fee' => 15000,
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Yang harus dibayar: Rp 198.000', $message);
        $this->assertStringContainsString('Biaya Admin: Rp 15.000', $message);
    }

    public function test_admin_fee_is_free_at_or_above_threshold(): void
    {
        $component = Livewire::test(OrderForm::class)
            ->set($this->validData(['amount' => 50]))
            ->assertSee('Gratis')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'amount' => 50,
            'payable_amount' => 915000,
            'admin_fee' => 0,
        ]);

        $message = rawurldecode(explode('?text=', $component->get('successUrl'), 2)[1] ?? '');
        $this->assertStringContainsString('Biaya Admin: Gratis', $message);
    }

    public function test_admin_fee_applies_to_invoice_and_pay_online(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'invoice',
                'amount' => 25,
                'paymentLink' => 'https://paypal.com/invoice/abc123',
            ]))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'invoice',
            'amount' => 25,
            'admin_fee' => 15000,
            'payable_amount' => 472500,
        ]);

        Livewire::test(OrderForm::class)
            ->set($this->validData([
                'service' => 'pay_online',
                'amount' => 10,
                'merchantName' => 'Amazon',
                'merchantUrl' => 'https://www.amazon.com/checkout',
                'paymentMethod' => 'PayPal',
            ]))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'pay_online',
            'amount' => 10,
            'admin_fee' => 15000,
            'payable_amount' => 198000,
        ]);
    }

    public function test_admin_fee_settings_are_adjustable(): void
    {
        Setting::updateOrCreate(
            ['category' => 'admin_fee', 'key' => 'threshold'],
            ['label' => 'Batas Bebas Biaya Admin', 'type' => 'number', 'value' => '100'],
        );

        Setting::updateOrCreate(
            ['category' => 'admin_fee', 'key' => 'amount'],
            ['label' => 'Biaya Admin', 'type' => 'number', 'value' => '25000'],
        );

        SiteContent::flush();

        Livewire::test(OrderForm::class)
            ->set($this->validData(['amount' => 60]))
            ->assertSee('Rp 25.000')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', [
            'service' => 'topup',
            'amount' => 60,
            'admin_fee' => 25000,
            'payable_amount' => 1123000,
        ]);
    }

    public function test_wa_message_format_differs_per_service(): void
    {
        $submit = fn (array $overrides) => Livewire::test(OrderForm::class)
            ->set($this->validData($overrides))
            ->call('submit')
            ->assertHasNoErrors()
            ->get('successUrl');

        $convert = $submit(['service' => 'convert_usd', 'amount' => 100]);
        $invoice = $submit([
            'service' => 'invoice',
            'amount' => 150,
            'paymentLink' => 'https://paypal.com/invoice/abc123',
        ]);

        $decode = fn (string $url) => rawurldecode(explode('text=', $url, 2)[1] ?? '');

        $this->assertStringContainsString('Convert USD ke IDR', $decode($convert));
        $this->assertStringContainsString('$100.00', $decode($convert));
        $this->assertStringNotContainsString('Jenis Pembayaran', $decode($convert));

        $this->assertStringContainsString('Bayar Invoice PayPal', $decode($invoice));
        $this->assertStringContainsString('$150.00', $decode($invoice));
        $this->assertStringContainsString('Rp 2.745.000', $decode($invoice));
        $this->assertStringContainsString('paypal.com/invoice/abc123', $decode($invoice));

        $this->assertDatabaseHas('transactions', [
            'service' => 'invoice',
            'amount' => 150,
            'payable_amount' => 2745000,
            'payment_link' => 'https://paypal.com/invoice/abc123',
        ]);

        $this->assertSame(2, Transaction::count());
    }

    public function test_invoice_order_requires_payment_link(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['service' => 'invoice']))
            ->call('submit')
            ->assertHasErrors('paymentLink');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_order_requires_terms_acceptance(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['termsAccepted' => false]))
            ->call('submit')
            ->assertHasErrors('termsAccepted');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_order_requires_valid_input(): void
    {
        Livewire::test(OrderForm::class)
            ->call('submit')
            ->assertHasErrors(['name', 'phone', 'amount']);

        Livewire::test(OrderForm::class)
            ->set(['name' => 'Andi', 'phone' => '081234567890', 'service' => 'hackermode', 'amount' => 10])
            ->call('submit')
            ->assertHasErrors('service');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_order_normalizes_indonesian_phone_number(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['phone' => '+62 812-9085-9343']))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('transactions', ['customer_phone' => '6281290859343']);
    }

    public function test_order_is_rejected_when_honeypot_is_filled(): void
    {
        Livewire::test(OrderForm::class)
            ->set($this->validData(['website' => 'http://spam.example']))
            ->call('submit')
            ->assertHasErrors('website');

        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_order_is_rate_limited_after_ten_submissions_per_minute(): void
    {
        $key = 'order:127.0.0.1';

        for ($i = 0; $i < 10; $i++) {
            RateLimiter::hit($key, 60);
        }

        Livewire::test(OrderForm::class)
            ->set($this->validData())
            ->call('submit')
            ->assertHasErrors('form');

        $this->assertDatabaseCount('transactions', 0);
    }
}

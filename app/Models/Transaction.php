<?php

namespace App\Models;

use App\Support\WhatsAppMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public const SERVICES = [
        'topup' => 'Top Up Saldo PayPal',
        'convert_usd' => 'Convert USD ke IDR',
        'invoice' => 'Bayar Invoice PayPal',
        'pay_online' => 'Jasa Bayar Online',
        'wd_akun_180' => 'WD Akun Permanen 180 Hari',
    ];

    /**
     * Layanan yang tidak memakai nominal transaksi.
     */
    public const NO_AMOUNT_SERVICES = ['wd_akun_180'];

    /**
     * Opsi jenis akun untuk layanan Top Up Saldo PayPal.
     */
    public const ACCOUNT_TYPES = [
        'indonesia' => 'Indonesia',
        'luar_negeri' => 'Luar Negeri',
    ];

    /**
     * Layanan lama yang tidak lagi ditawarkan, tapi masih muncul
     * pada data transaksi yang pernah dibuat sebelumnya.
     */
    public const LEGACY_SERVICE_LABELS = [
        'convert_idr' => 'Top Up Saldo PayPal',
        'verify' => 'Verifikasi Kartu Kredit',
    ];

    /**
     * Mata uang nominal tiap layanan.
     *
     * Nominal selalu dalam USD. Untuk Top Up & Bayar Invoice, pelanggan
     * membayar dalam Rupiah (lihat kolom payable_amount).
     */
    public const SERVICE_CURRENCIES = [
        'topup' => 'usd',
        'convert_usd' => 'usd',
        'invoice' => 'usd',
        'pay_online' => 'usd',
    ];

    /**
     * Layanan yang pembayarannya dikonversi ke Rupiah memakai setting rate.topup.
     */
    public const PAYABLE_SERVICES = ['topup', 'invoice', 'pay_online'];

    public const STATUSES = [
        'pending' => 'Menunggu',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'gagal' => 'Gagal',
        'batal' => 'Dibatalkan',
    ];

    /**
     * Asal transaksi: dari form website atau diinput manual oleh admin.
     */
    public const SOURCES = [
        'website' => 'Website',
        'manual' => 'Input Manual',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payable_amount' => 'decimal:2',
        'notified_at' => 'datetime',
    ];

    public function getServiceLabelAttribute(): string
    {
        return self::SERVICES[$this->service]
            ?? self::LEGACY_SERVICE_LABELS[$this->service]
            ?? $this->service;
    }

    public function serviceCurrency(): string
    {
        return self::SERVICE_CURRENCIES[$this->service] ?? 'usd';
    }

    public function getAmountLabelAttribute(): string
    {
        if (in_array($this->service, self::NO_AMOUNT_SERVICES, true)) {
            return '-';
        }

        return self::formatAmount((float) $this->amount, $this->service);
    }

    public function hasPayableAmount(): bool
    {
        return $this->payable_amount !== null;
    }

    public function getPayableLabelAttribute(): ?string
    {
        if ($this->payable_amount === null) {
            return null;
        }

        return 'Rp '.number_format((float) $this->payable_amount, 0, ',', '.');
    }

    /**
     * True bila layanan membayar dengan Rupiah (Top Up & Bayar Invoice).
     */
    public function isPayableService(): bool
    {
        return in_array($this->service, self::PAYABLE_SERVICES, true);
    }

    public static function formatAmount(float $amount, string $service): string
    {
        if ((self::SERVICE_CURRENCIES[$service] ?? 'usd') === 'idr') {
            return 'Rp '.number_format($amount, 0, ',', '.');
        }

        return '$'.number_format($amount, 2, '.', ',');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getSourceLabelAttribute(): string
    {
        return self::SOURCES[$this->source] ?? $this->source;
    }

    public function getPayoutTypeLabelAttribute(): ?string
    {
        if ($this->payout_type === null) {
            return null;
        }

        return PayoutMethod::TYPES[$this->payout_type] ?? $this->payout_type;
    }

    public function getAccountTypeLabelAttribute(): ?string
    {
        if ($this->account_type === null) {
            return null;
        }

        return self::ACCOUNT_TYPES[$this->account_type] ?? $this->account_type;
    }

    /**
     * Label nominal rupiah; khusus Jasa Bayar Online disebut total bayar.
     */
    public function getPayableCaptionAttribute(): string
    {
        return $this->service === 'pay_online' ? 'Total Bayar' : 'Yang Harus Dibayar';
    }

    public function isManual(): bool
    {
        return $this->source === 'manual';
    }

    /**
     * Buat kode transaksi unik berformat ZP-YYMMDD-XXXXX.
     */
    public static function generateCode(): string
    {
        do {
            $code = 'ZP-'.now()->format('ymd').'-'.strtoupper(Str::random(5));
        } while (self::withTrashed()->where('transaction_code', $code)->exists());

        return $code;
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_path ? url('/storage/'.$this->proof_path) : null;
    }

    /**
     * Link wa.me untuk menghubungi user (notifikasi status). Digerakkan admin.
     */
    public function notificationLink(): string
    {
        return WhatsAppMessage::url($this->customer_phone, WhatsAppMessage::forNotification($this));
    }

    /**
     * Link wa.me untuk menghubungi admin (dipakai customer setelah submit order).
     */
    public function adminChatLink(): string
    {
        return WhatsAppMessage::adminUrl(
            WhatsAppMessage::forOrder([
                'code' => $this->transaction_code,
                'name' => $this->customer_name,
                'phone' => $this->customer_phone,
                'email' => $this->email,
                'paypal_account_name' => $this->paypal_account_name,
                'account_type' => $this->account_type,
                'service' => $this->service,
                'amount' => $this->amount,
                'payable_amount' => $this->payable_amount,
                'payment_link' => $this->payment_link,
                'bank_name' => $this->bank_name,
                'bank_account_name' => $this->bank_account_name,
                'bank_account_number' => $this->bank_account_number,
                'payout_type' => $this->payout_type,
                'payout_method' => $this->payout_method,
                'payout_account_number' => $this->payout_account_number,
                'merchant_name' => $this->merchant_name,
                'merchant_url' => $this->merchant_url,
                'payment_method' => $this->payment_method,
                'fee_estimate' => $this->fee_estimate,
                'notes' => $this->notes,
            ])
        );
    }
}

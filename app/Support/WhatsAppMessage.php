<?php

namespace App\Support;

use App\Models\PayoutMethod;
use App\Models\Transaction;

class WhatsAppMessage
{
    /**
     * Normalisasi nomor menjadi format internasional tanpa +/spasi/tanda hubung.
     * Contoh: 081290859343 -> 6281290859343
     */
    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '62')) {
            // sudah format internasional
        }

        return $digits;
    }

    /**
     * Build link wa.me untuk nomor tujuan tertentu.
     */
    public static function url(string $phone, string $message): string
    {
        return 'https://wa.me/'.self::normalizePhone($phone).'?text='.rawurlencode($message);
    }

    /**
     * Link ke nomor admin (dari pengaturan general.whatsapp_number).
     */
    public static function adminUrl(string $message): string
    {
        $number = (string) (SiteContent::setting('general.whatsapp_number') ?: '6281290859343');

        return 'https://wa.me/'.self::normalizePhone($number).'?text='.rawurlencode($message);
    }

    /**
     * Format pesan order sesuai jenis layanan yang dipilih pelanggan.
     *
     * @param  array{code?: string, name?: string, phone?: string, email?: ?string, paypal_account_name?: ?string, account_type?: ?string, service?: string, amount?: float|string|int|null, payable_amount?: float|string|int|null, payment_link?: ?string, bank_name?: ?string, bank_account_name?: ?string, bank_account_number?: ?string, payout_type?: ?string, payout_method?: ?string, payout_account_number?: ?string, merchant_name?: ?string, merchant_url?: ?string, payment_method?: ?string, fee_estimate?: ?string, notes?: ?string}  $data
     */
    public static function forOrder(array $data): string
    {
        $service = $data['service'] ?? 'topup';
        $label = Transaction::SERVICES[$service] ?? $service;

        $lines = [
            'Halo Beli Dolar! Saya ingin *'.$label.'*.',
            '',
            'Kode Transaksi: '.($data['code'] ?? '-'),
            'Nama: '.($data['name'] ?? '-'),
            'No. WhatsApp: '.($data['phone'] ?? '-'),
        ];

        // Bayar Invoice PayPal tidak lagi memakai field email/nama akun PayPal.
        if ($service !== 'invoice') {
            if (! empty($data['email'])) {
                $lines[] = 'Email PayPal: '.$data['email'];
            }

            if (! empty($data['paypal_account_name'])) {
                $lines[] = 'Nama Akun PayPal: '.$data['paypal_account_name'];
            }
        }

        if (! empty($data['account_type'])) {
            $accountType = Transaction::ACCOUNT_TYPES[$data['account_type']] ?? $data['account_type'];
            $lines[] = 'Jenis Akun: '.$accountType;
        }

        if ($service !== 'wd_akun_180') {
            $lines[] = 'Nominal: '.Transaction::formatAmount((float) ($data['amount'] ?? 0), $service);
        }

        if (in_array($service, Transaction::PAYABLE_SERVICES, true) && ! empty($data['payable_amount'])) {
            $caption = $service === 'pay_online' ? 'Total Bayar' : 'Yang harus dibayar';
            $lines[] = $caption.': Rp '.number_format((float) $data['payable_amount'], 0, ',', '.');
        }

        if ($service === 'topup' && ($data['account_type'] ?? null) === 'luar_negeri' && ! empty($data['fee_estimate'])) {
            $lines[] = 'Estimasi Fee: '.$data['fee_estimate'];
        }

        if (! empty($data['bank_name'])) {
            $lines[] = 'Bank Tujuan: '.$data['bank_name'].' - '.($data['bank_account_number'] ?? '-').' (a.n. '.($data['bank_account_name'] ?? '-').')';
        }

        if (! empty($data['payout_method'])) {
            $type = $data['payout_type'] ? (PayoutMethod::TYPES[$data['payout_type']] ?? $data['payout_type']) : null;
            $lines[] = 'Metode Pencairan: '.($type ? $type.' - ' : '').$data['payout_method'];
        }

        if ($service === 'convert_usd' && ! empty($data['payout_account_number'])) {
            $lines[] = 'Nomor Rekening / E-Wallet: '.$data['payout_account_number'];
        }

        if ($service === 'invoice' && ! empty($data['payment_link'])) {
            $lines[] = 'Link Pembayaran: '.$data['payment_link'];
        }

        if ($service === 'pay_online') {
            if (! empty($data['merchant_name'])) {
                $lines[] = 'Nama Tempat / Merchant: '.$data['merchant_name'];
            }

            if (! empty($data['merchant_url'])) {
                $lines[] = 'Link / URL Situs: '.$data['merchant_url'];
            }

            if (! empty($data['payment_method'])) {
                $lines[] = 'Metode Pembayaran: '.$data['payment_method'];
            }
        }

        if (! empty($data['notes'])) {
            $lines[] = 'Catatan: '.$data['notes'];
        }

        $lines[] = '';
        $lines[] = 'Mohon dibantu prosesnya. Terima kasih!';

        return implode("\n", $lines);
    }

    /**
     * Format pesan notifikasi status transaksi untuk pelanggan.
     */
    public static function forNotification(Transaction $transaction): string
    {
        $proof = $transaction->proofUrl;

        $lines = [
            'Halo *'.$transaction->customer_name.'*, terima kasih telah berbelanja di Beli Dolar!',
            '',
            'Status transaksi kamu saat ini: *'.$transaction->statusLabel.'*.',
            'Kode Transaksi: '.$transaction->transaction_code,
            'Layanan: '.$transaction->serviceLabel,
        ];

        if (! in_array($transaction->service, Transaction::NO_AMOUNT_SERVICES, true)) {
            $lines[] = 'Nominal: '.Transaction::formatAmount((float) $transaction->amount, $transaction->service);
        }

        if ($transaction->hasPayableAmount()) {
            $lines[] = 'Yang harus dibayar: '.$transaction->payable_label;
        }

        if (! empty($transaction->admin_note)) {
            $lines[] = 'Catatan Admin: '.$transaction->admin_note;
        }

        if ($proof !== null) {
            $lines[] = 'Bukti Transaksi: '.$proof;
        }

        $lines[] = '';
        $lines[] = 'Silakan cek riwayat transaksi kamu. Terima kasih! 🎉';

        return implode("\n", $lines);
    }
}

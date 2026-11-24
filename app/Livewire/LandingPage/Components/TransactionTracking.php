<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\Transaction;
use App\Support\WhatsAppMessage;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class TransactionTracking extends Component
{
    public string $code = '';

    public string $phone = '';

    public ?Transaction $transaction = null;

    public bool $searched = false;

    /**
     * Cari transaksi berdasarkan kode + nomor WhatsApp pemesan.
     */
    public function search(): void
    {
        // Rate limit berbasis IP: maksimal 20 pencarian per menit.
        $throttleKey = 'tracking:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 20)) {
            $this->addError('code', 'Terlalu banyak percobaan. Silakan coba lagi beberapa saat lagi.');

            return;
        }

        RateLimiter::hit($throttleKey, 60);

        $data = $this->validate([
            'code' => ['required', 'string', 'max:40'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $this->searched = true;
        $this->transaction = null;

        $phone = WhatsAppMessage::normalizePhone($data['phone']);
        $rawPhone = preg_replace('/\D+/', '', $data['phone']) ?? '';

        $transaction = Transaction::query()
            ->where('transaction_code', strtoupper(trim($data['code'])))
            ->first();

        $matchesPhone = $transaction && (
            $transaction->customer_phone === $phone
            || $transaction->customer_phone === $data['phone']
            || $transaction->customer_phone === $rawPhone
        );

        if (! $matchesPhone) {
            $this->addError('code', 'Transaksi tidak ditemukan. Periksa kembali kode transaksi dan nomor WhatsApp Anda.');

            return;
        }

        $this->transaction = $transaction;
    }

    public function resetForm(): void
    {
        $this->reset(['code', 'phone', 'transaction', 'searched']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.landing-page.components.transaction-tracking');
    }
}

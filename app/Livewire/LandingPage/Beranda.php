<?php

namespace App\Livewire\LandingPage;

use App\Support\SiteContent;
use Livewire\Component;

class Beranda extends Component
{
    public $stats = [];

    public function mount(): void
    {
        $this->stats = [
            'transactions' => (int) SiteContent::get('stats', 'transaksi_sukses', 0),
            'experience' => (int) SiteContent::get('stats', 'tahun_pengalaman', 0),
            'total_sent' => (int) SiteContent::get('stats', 'saldo_terkirim', 0),
        ];
    }

    public function render()
    {
        return view('livewire.landing-page.beranda')
            ->layout('layouts.app');
    }
}

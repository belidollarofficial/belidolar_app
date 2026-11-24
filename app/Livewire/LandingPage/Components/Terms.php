<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\Transaction;
use App\Support\SiteContent;
use Livewire\Attributes\On;
use Livewire\Component;

class Terms extends Component
{
    public string $service = 'topup';

    public function mount(): void
    {
        $service = (string) request()->query('service', '');

        if ($service !== '' && array_key_exists($service, Transaction::SERVICES)) {
            $this->service = $service;
        }
    }

    #[On('order-service-changed')]
    public function syncService(string $service): void
    {
        if (array_key_exists($service, Transaction::SERVICES)) {
            $this->service = $service;
        }
    }

    public function render()
    {
        return view('livewire.landing-page.components.terms', [
            'title' => SiteContent::get('syarat_ketentuan', 'title', 'Syarat & Ketentuan'),
        ]);
    }
}

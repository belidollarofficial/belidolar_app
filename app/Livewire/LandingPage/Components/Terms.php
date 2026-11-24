<?php

namespace App\Livewire\LandingPage\Components;

use App\Support\SiteContent;
use Livewire\Component;

class Terms extends Component
{
    public function render()
    {
        return view('livewire.landing-page.components.terms', [
            'title' => SiteContent::get('syarat_ketentuan', 'title', 'Syarat & Ketentuan'),
        ]);
    }
}

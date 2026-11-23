<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\Faq as FaqModel;
use Livewire\Component;

class Faq extends Component
{
    public function render()
    {
        return view('livewire.landing-page.components.faq', [
            'faqs' => FaqModel::query()
                ->active()
                ->ordered()
                ->get(),
        ]);
    }
}

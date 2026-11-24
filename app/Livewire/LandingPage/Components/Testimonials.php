<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\Testimonial;
use Livewire\Component;

class Testimonials extends Component
{
    public $items;

    public function mount(): void
    {
        $this->items = Testimonial::approved()
            ->latest()
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.landing-page.components.testimonials');
    }
}

<?php

namespace App\Livewire\LandingPage\Components;

use Livewire\Component;

class Navbar extends Component
{
    public $menuItems = [
        ['id' => 'beranda', 'label' => 'Beranda', 'icon' => 'fa-home'],
        ['id' => 'layanan', 'label' => 'Layanan', 'icon' => 'fa-cogs'],
        ['id' => 'cara-order', 'label' => 'Cara Order', 'icon' => 'fa-list-ol'],
        ['id' => 'form-order', 'label' => 'Form Order', 'icon' => 'fa-clipboard-list'],
        ['id' => 'testimoni', 'label' => 'Testimoni', 'icon' => 'fa-star'],
        ['id' => 'riwayat', 'label' => 'Riwayat', 'icon' => 'fa-history'],
    ];

    public $currentSection = 'beranda';

    public function setActive($section)
    {
        $this->currentSection = $section;
        $this->dispatch('sectionChanged', section: $section);
    }

    public function render()
    {
        return view('livewire.landing-page.components.navbar');
    }
}

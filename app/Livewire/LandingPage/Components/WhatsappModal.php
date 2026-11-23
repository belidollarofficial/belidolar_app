<?php

namespace App\Livewire\LandingPage\Components;

use App\Support\SiteContent;
use App\Support\WhatsAppMessage;
use Livewire\Component;

class WhatsappModal extends Component
{
    public $isOpen = false;

    public function toggle()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        $number = WhatsAppMessage::normalizePhone(
            (string) (SiteContent::setting('general.whatsapp_number') ?: '6281290859343')
        );

        return view('livewire.landing-page.components.whatsapp-modal', [
            'waNumber' => $number,
            'siteName' => (string) (SiteContent::setting('general.site_name') ?: 'Beli Dolar'),
            'greeting' => 'Halo! Ada yang bisa kami bantu? Silakan ketik pesan Anda.',
            'quickReplies' => [
                'Top Up Saldo PayPal',
                'Convert USD ke IDR',
                'Bayar Invoice',
            ],
        ]);
    }
}

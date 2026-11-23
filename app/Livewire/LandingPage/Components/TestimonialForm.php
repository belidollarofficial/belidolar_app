<?php

namespace App\Livewire\LandingPage\Components;

use App\Models\BannedWord;
use App\Models\Testimonial;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class TestimonialForm extends Component
{
    public string $name = '';

    public string $rating = '5';

    public string $message = '';

    public string $website = '';

    public bool $submitted = false;

    public bool $pendingApproval = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
            // Honeypot anti-bot.
            'website' => ['prohibited'],
        ];
    }

    public function submit(): void
    {
        // Rate limit berbasis IP: maksimal 5 testimoni per menit.
        $throttleKey = 'testimonial:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('form', 'Terlalu banyak permintaan. Silakan coba lagi beberapa saat lagi.');

            return;
        }

        RateLimiter::hit($throttleKey, 60);

        $data = $this->validate();

        $status = Testimonial::STATUS_APPROVED;

        if (BannedWord::textContains($data['name'].' '.$data['message'])) {
            $status = Testimonial::STATUS_PENDING;
            $this->pendingApproval = true;
        }

        Testimonial::create([
            'name' => $data['name'],
            'rating' => (int) $data['rating'],
            'message' => $data['message'],
            'status' => $status,
        ]);

        $this->submitted = true;
        $this->reset(['name', 'rating', 'message', 'website']);
    }

    public function render()
    {
        return view('livewire.landing-page.components.testimonial-form');
    }
}

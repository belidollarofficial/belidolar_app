<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Andi S.', 5, '"Proses cepat banget! Top up $50 cuma 3 menit. Recommended!"'],
            ['Rina W.', 5, '"Convert USD ke IDR dengan kurs bagus. CS ramah dan responsif."'],
            ['Budi P.', 5, '"Bayar invoice PayPal jadi mudah. Terima kasih Beli Dolar!"'],
        ];

        foreach ($items as [$name, $rating, $message]) {
            Testimonial::firstOrCreate(
                ['name' => $name, 'message' => $message],
                ['rating' => $rating, 'status' => Testimonial::STATUS_APPROVED]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Setting;
use App\Support\ContentDefaults;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 0;
        foreach (ContentDefaults::all() as $section => $items) {
            foreach ($items as [$key, $label, $type, $value]) {
                Content::updateOrCreate(
                    ['section' => $section, 'key' => $key],
                    ['label' => $label, 'type' => $type, 'value' => $value, 'sort_order' => $sort++]
                );
            }
        }

        $settings = [
            'general' => [
                ['site_name', 'Nama Situs', 'text', 'Beli Dolar'],
                ['whatsapp_number', 'Nomor WhatsApp (format internasional)', 'text', '6281290859343'],
                ['whatsapp_display', 'Tampilan Nomor WhatsApp', 'text', '+62 812-9085-9343'],
                ['email', 'Email Contact', 'text', 'cs@belidolar.id'],
            ],
            'rate' => [
                ['topup', 'Rate Top Up / Bayar Invoice (Rp per USD)', 'number', '18300'],
            ],
            'operasional' => [
                ['weekday', 'Jam Operasional Senin - Minggu', 'text', 'Senin - Minggu (08:00 - 24:00)'],
                ['weekend', 'Hari Besar & Tanggal Merah', 'text', 'Hari besar, tanggal merah tetap buka'],
                ['holiday', 'Jam Operasional Tambahan (opsional)', 'text', ''],
                ['processing_time', 'Estimasi Lama Pesanan Diproses', 'text', '5-15 menit'],
                ['note', 'Catatan Jam Operasional', 'textarea', 'Catatan: Jika transaksi melewati jam operasional, konfirmasi terlebih dahulu'],
            ],
            'fee' => [
                ['min', 'Estimasi Fee Minimum (USD)', 'number', '5'],
                ['max', 'Estimasi Fee Maksimum (USD)', 'number', '15'],
            ],
            'admin_fee' => [
                ['threshold', 'Batas Nominal Bebas Biaya Admin (USD)', 'number', '50'],
                ['amount', 'Biaya Admin di Bawah Batas (Rp)', 'number', '15000'],
            ],
            'branding' => [
                ['logo', 'Logo Website (PNG/SVG/WebP)', 'image', ''],
                ['favicon', 'Favicon (PNG/SVG/ICO)', 'image', ''],
            ],
        ];

        $sort = 0;
        foreach ($settings as $category => $items) {
            foreach ($items as [$key, $label, $type, $value]) {
                Setting::updateOrCreate(
                    ['category' => $category, 'key' => $key],
                    ['label' => $label, 'type' => $type, 'value' => $value, 'sort_order' => $sort++]
                );
            }
        }
    }
}

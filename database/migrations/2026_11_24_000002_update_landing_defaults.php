<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perbarui angka statistik, info rating/pelanggan, tahun berdiri,
     * dan jam operasional. Hanya nilai yang masih memakai default lama
     * yang diubah agar kustomisasi admin tidak tertimpa.
     */
    public function up(): void
    {
        $now = now();

        $contentUpdates = [
            ['hero', 'badge_rating', '★ 5.0', '★ 4.9'],
            ['hero', 'badge_customers', '1K+ Pelanggan', '2K+ Pelanggan'],
            ['hero', 'card_stat_1_value', '10K+', '2K+'],
            ['hero', 'card_stat_2_value', '★ 5.0', '★ 4.9'],
            ['stats', 'transaksi_sukses', '135000', '36000'],
            ['stats', 'tahun_pengalaman', '7', '5'],
            ['stats', 'saldo_terkirim', '400000', '3000000'],
            ['footer', 'description', 'Solusi terpercaya untuk kebutuhan transaksi digital Anda sejak 2025.', 'Solusi terpercaya untuk kebutuhan transaksi digital Anda sejak 2021.'],
            ['footer', 'copyright_lhs', '© 2026 Beli Dolar. All rights reserved. Made with', '© 2021 Beli Dolar. All rights reserved. Made with'],
        ];

        foreach ($contentUpdates as [$section, $key, $old, $new]) {
            DB::table('contents')
                ->where('section', $section)
                ->where('key', $key)
                ->where('value', $old)
                ->update(['value' => $new, 'updated_at' => $now]);
        }

        $operationalUpdates = [
            ['weekday', 'Senin - Jumat: 09.00 - 21.00 WIB', 'Senin - Minggu (08:00 - 24:00)'],
            ['weekend', 'Sabtu: 10.00 - 20.00 WIB', 'Hari besar, tanggal merah tetap buka'],
            ['holiday', 'Minggu, Hari Besar & Tanggal Merah: Tutup / Tidak Melayani Transaksi', ''],
            ['processing_time', '± 20 menit', '5-15 menit'],
            ['note', 'Catatan: Jika transfer di atas pukul 22.00 WIB, kami mungkin memproses pesanan Anda besoknya.', 'Catatan: Jika transaksi melewati jam operasional, konfirmasi terlebih dahulu'],
        ];

        foreach ($operationalUpdates as [$key, $old, $new]) {
            DB::table('settings')
                ->where('category', 'operasional')
                ->where('key', $key)
                ->where('value', $old)
                ->update(['value' => $new, 'updated_at' => $now]);
        }

        $labelUpdates = [
            'weekday' => 'Jam Operasional Senin - Minggu',
            'weekend' => 'Hari Besar & Tanggal Merah',
            'holiday' => 'Jam Operasional Tambahan (opsional)',
            'note' => 'Catatan Jam Operasional',
        ];

        foreach ($labelUpdates as $key => $label) {
            DB::table('settings')
                ->where('category', 'operasional')
                ->where('key', $key)
                ->update(['label' => $label, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        // Nilai lama tidak dikembalikan otomatis.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pastikan pengaturan logo & favicon tersedia di database produksi
     * tanpa perlu menjalankan ulang seluruh ContentSeeder.
     */
    public function up(): void
    {
        $now = now();
        $sort = (int) DB::table('settings')->max('sort_order') + 1;

        $items = [
            'logo' => 'Logo Website (PNG/SVG/WebP)',
            'favicon' => 'Favicon (PNG/SVG/ICO)',
        ];

        foreach ($items as $key => $label) {
            if (DB::table('settings')->where('key', $key)->exists()) {
                continue;
            }

            DB::table('settings')->insert([
                'category' => 'branding',
                'key' => $key,
                'label' => $label,
                'type' => 'image',
                'value' => '',
                'sort_order' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('category', 'branding')
            ->whereIn('key', ['logo', 'favicon'])
            ->delete();
    }
};

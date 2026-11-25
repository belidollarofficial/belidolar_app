<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan kartu WD di Layanan Kami dan pengaturan biaya admin.
     */
    public function up(): void
    {
        $now = now();

        foreach (['item_5_title', 'item_5_desc'] as $key) {
            $exists = DB::table('contents')
                ->where('section', 'layanan')
                ->where('key', $key)
                ->exists();

            if ($exists) {
                continue;
            }

            $default = ContentDefaults::find('layanan', $key);

            if ($default === null) {
                continue;
            }

            DB::table('contents')->insert([
                'section' => 'layanan',
                'key' => $key,
                'label' => $default['label'],
                'type' => $default['type'],
                'value' => $default['value'],
                'sort_order' => (int) DB::table('contents')->max('sort_order') + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $feeSettings = [
            ['threshold', 'Batas Nominal Bebas Biaya Admin (USD)', '50'],
            ['amount', 'Biaya Admin di Bawah Batas (Rp)', '15000'],
        ];

        foreach ($feeSettings as [$key, $label, $value]) {
            $exists = DB::table('settings')
                ->where('category', 'admin_fee')
                ->where('key', $key)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('settings')->insert([
                'category' => 'admin_fee',
                'key' => $key,
                'label' => $label,
                'type' => 'number',
                'value' => $value,
                'sort_order' => (int) DB::table('settings')->max('sort_order') + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('contents')
            ->where('section', 'layanan')
            ->whereIn('key', ['item_5_title', 'item_5_desc'])
            ->delete();

        DB::table('settings')
            ->where('category', 'admin_fee')
            ->whereIn('key', ['threshold', 'amount'])
            ->delete();
    }
};

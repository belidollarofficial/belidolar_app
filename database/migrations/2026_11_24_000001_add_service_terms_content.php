<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan syarat & ketentuan khusus untuk setiap jenis transaksi.
     * Admin dapat mengedit masing-masing lewat editor WYSIWYG.
     */
    public function up(): void
    {
        $now = now();
        $sort = (int) DB::table('contents')->max('sort_order') + 1;
        $keys = ['topup', 'convert_usd', 'invoice', 'pay_online'];

        foreach ($keys as $key) {
            $exists = DB::table('contents')
                ->where('section', 'syarat_ketentuan')
                ->where('key', $key)
                ->exists();

            if ($exists) {
                continue;
            }

            $default = ContentDefaults::find('syarat_ketentuan', $key);

            if ($default === null) {
                continue;
            }

            DB::table('contents')->insert([
                'section' => 'syarat_ketentuan',
                'key' => $key,
                'label' => $default['label'],
                'type' => $default['type'],
                'value' => $default['value'],
                'sort_order' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('contents')
            ->where('section', 'syarat_ketentuan')
            ->whereIn('key', ['topup', 'convert_usd', 'invoice', 'pay_online'])
            ->delete();
    }
};

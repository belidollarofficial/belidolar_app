<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan disclaimer footer dan konten halaman legal yang dapat
     * diedit admin melalui Konten Landing Page.
     */
    public function up(): void
    {
        $items = [
            'footer' => ['disclaimer'],
            'tentang_kami' => ['content'],
            'cara_order_page' => ['content'],
        ];

        $now = now();
        $sort = (int) DB::table('contents')->max('sort_order') + 1;

        foreach ($items as $section => $keys) {
            foreach ($keys as $key) {
                $exists = DB::table('contents')
                    ->where('section', $section)
                    ->where('key', $key)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $default = ContentDefaults::find($section, $key);

                if ($default === null) {
                    continue;
                }

                DB::table('contents')->insert([
                    'section' => $section,
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
    }

    public function down(): void
    {
        DB::table('contents')->where('section', 'tentang_kami')->delete();
        DB::table('contents')->where('section', 'cara_order_page')->delete();
        DB::table('contents')
            ->where('section', 'footer')
            ->where('key', 'disclaimer')
            ->delete();
    }
};

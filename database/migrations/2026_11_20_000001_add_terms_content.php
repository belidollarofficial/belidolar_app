<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan konten Syarat & Ketentuan yang bisa diedit admin lewat
     * editor WYSIWYG di halaman Konten Landing Page.
     */
    public function up(): void
    {
        $now = now();
        $sort = (int) DB::table('contents')->max('sort_order') + 1;

        foreach (ContentDefaults::all()['syarat_ketentuan'] ?? [] as [$key, $label, $type, $value]) {
            $exists = DB::table('contents')
                ->where('section', 'syarat_ketentuan')
                ->where('key', $key)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('contents')->insert([
                'section' => 'syarat_ketentuan',
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'value' => $value,
                'sort_order' => $sort++,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('contents')->where('section', 'syarat_ketentuan')->delete();
    }
};

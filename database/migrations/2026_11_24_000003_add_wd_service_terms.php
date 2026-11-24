<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan syarat & ketentuan untuk layanan WD Akun Permanen 180 Hari.
     */
    public function up(): void
    {
        $exists = DB::table('contents')
            ->where('section', 'syarat_ketentuan')
            ->where('key', 'wd_akun_180')
            ->exists();

        if ($exists) {
            return;
        }

        $default = ContentDefaults::find('syarat_ketentuan', 'wd_akun_180');

        if ($default === null) {
            return;
        }

        $now = now();

        DB::table('contents')->insert([
            'section' => 'syarat_ketentuan',
            'key' => 'wd_akun_180',
            'label' => $default['label'],
            'type' => $default['type'],
            'value' => $default['value'],
            'sort_order' => (int) DB::table('contents')->max('sort_order') + 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('contents')
            ->where('section', 'syarat_ketentuan')
            ->where('key', 'wd_akun_180')
            ->delete();
    }
};

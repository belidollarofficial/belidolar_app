<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Landing page hanya menampilkan judul "Syarat & Ketentuan" dari card,
     * sehingga heading "Ketentuan <layanan>" di dalam isi tidak diperlukan.
     */
    public function up(): void
    {
        $keys = ['topup', 'convert_usd', 'invoice', 'pay_online', 'wd_akun_180'];

        foreach ($keys as $key) {
            $row = DB::table('contents')
                ->where('section', 'syarat_ketentuan')
                ->where('key', $key)
                ->first(['id', 'value']);

            if (! $row || ! is_string($row->value)) {
                continue;
            }

            if (preg_match('/^\s*<h3>\s*Ketentuan[^<]*<\/h3>\s*/i', $row->value) !== 1) {
                continue;
            }

            $value = preg_replace('/^\s*<h3>\s*Ketentuan[^<]*<\/h3>\s*/i', '', $row->value, 1);

            DB::table('contents')
                ->where('id', $row->id)
                ->update(['value' => $value, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Heading lama tidak dikembalikan otomatis.
    }
};

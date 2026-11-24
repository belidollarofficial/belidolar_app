<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Bersihkan data lama saat rebranding Zona Pay -> Beli Dolar dan
     * hapus konten/pengaturan kurs yang sudah tidak dipakai.
     */
    public function up(): void
    {
        DB::table('contents')->where('section', 'kurs')->delete();
        DB::table('settings')->where('category', 'kurs')->delete();

        $replace = static fn (?string $value): ?string => $value === null
            ? null
            : str_replace(
                ['Zona Pay', 'ZonaPay', 'BeliDollar', 'zonapay.com', 'belidollar.com', 'belidollar.id'],
                ['Beli Dolar', 'Beli Dolar', 'Beli Dolar', 'belidolar.id', 'belidolar.id', 'belidolar.id'],
                $value,
            );

        DB::table('contents')->orderBy('id')->chunkById(100, function ($rows) use ($replace) {
            foreach ($rows as $row) {
                $new = $replace($row->value);

                if ($new !== $row->value) {
                    DB::table('contents')->where('id', $row->id)->update(['value' => $new]);
                }
            }
        });

        DB::table('settings')->orderBy('id')->chunkById(100, function ($rows) use ($replace) {
            foreach ($rows as $row) {
                $new = $replace($row->value);

                if ($new !== $row->value) {
                    DB::table('settings')->where('id', $row->id)->update(['value' => $new]);
                }
            }
        });
    }

    /**
     * Data kurs yang dihapus tidak dapat dikembalikan otomatis.
     */
    public function down(): void
    {
        //
    }
};

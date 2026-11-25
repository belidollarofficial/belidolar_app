<?php

use App\Support\ContentDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambahkan URL profil sosial media dan link WD di footer
     * tanpa menimpa konten footer yang sudah disunting admin.
     */
    public function up(): void
    {
        $footerItems = ContentDefaults::all()['footer'] ?? [];
        $now = now();
        $sort = (int) DB::table('contents')->max('sort_order') + 1;

        foreach ($footerItems as [$key, $label, $type, $value]) {
            if (! in_array($key, [
                'layanan_link_5',
                'social_facebook_url',
                'social_instagram_url',
                'social_twitter_url',
                'social_youtube_url',
            ], true)) {
                continue;
            }

            $exists = DB::table('contents')
                ->where('section', 'footer')
                ->where('key', $key)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('contents')->insert([
                'section' => 'footer',
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
        DB::table('contents')
            ->where('section', 'footer')
            ->whereIn('key', [
                'layanan_link_5',
                'social_facebook_url',
                'social_instagram_url',
                'social_twitter_url',
                'social_youtube_url',
            ])
            ->delete();
    }
};

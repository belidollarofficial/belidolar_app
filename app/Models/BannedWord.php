<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    protected $table = 'testimonial_banned_words';

    protected $guarded = [];

    /**
     * Cek apakah teks mengandung salah satu kata yang dilarang (tidak case-sensitive).
     */
    public static function textContains(string $text): bool
    {
        $text = mb_strtolower(trim($text));

        if ($text === '') {
            return false;
        }

        return static::query()
            ->get()
            ->contains(fn (self $word) => $word->word !== '' && mb_strpos($text, mb_strtolower(trim($word->word))) !== false);
    }
}

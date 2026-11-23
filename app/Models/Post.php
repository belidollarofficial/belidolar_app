<?php

namespace App\Models;

use App\Support\Branding;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Artikel yang boleh tampil di halaman publik.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function getCoverUrlAttribute(): ?string
    {
        return Branding::storageUrl($this->cover_image);
    }

    /**
     * Konten HTML yang sudah disanitasi (aman untuk {!! !!}).
     */
    public function getContentHtmlAttribute(): string
    {
        return HtmlSanitizer::clean($this->content);
    }

    /**
     * Ringkasan artikel: pakai kolom excerpt, atau potong dari konten.
     */
    public function getSummaryAttribute(): string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->content)) ?? '');

        return Str::limit($text, 180);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->content));

        return max(1, (int) ceil($words / 200));
    }

    /**
     * Buat slug unik dari judul. Slug yang sudah dipakai post lain akan
     * ditambahi akhiran angka.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);

        if ($base === '') {
            $base = 'artikel';
        }

        $slug = $base;
        $counter = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query) => $query->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    /**
     * Sanitasi konten setiap kali disimpan, apa pun jalur inputnya.
     */
    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->content = HtmlSanitizer::clean($post->content);

            if (blank($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });

        static::deleting(function (Post $post) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
        });
    }
}

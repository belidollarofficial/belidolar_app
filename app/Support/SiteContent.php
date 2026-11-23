<?php

namespace App\Support;

use App\Models\Content;
use App\Models\Setting;
use Illuminate\Support\Collection;

class SiteContent
{
    protected static ?Collection $contents = null;

    protected static ?Collection $settings = null;

    protected static function contents(): Collection
    {
        if (static::$contents === null) {
            static::$contents = Content::query()
                ->orderBy('sort_order')
                ->get()
                ->mapWithKeys(fn (Content $c) => [$c->section.'.'.$c->key => $c]);
        }

        return static::$contents;
    }

    protected static function settings(): Collection
    {
        if (static::$settings === null) {
            static::$settings = Setting::all()
                ->mapWithKeys(fn (Setting $s) => [$s->category.'.'.$s->key => $s]);
        }

        return static::$settings;
    }

    /**
     * Ambil konten landing page.
     *
     * @param  mixed  $default
     */
    public static function get(string $section, string $key, $default = null): mixed
    {
        return static::contents()->get($section.'.'.$key)?->value ?? $default;
    }

    /**
     * Ambil konten berupa HTML dan bersihkan dengan allowlist sanitizer.
     * Aman untuk ditampilkan memakai {!! !!}.
     */
    public static function rich(string $section, string $key, ?string $default = null): string
    {
        return HtmlSanitizer::clean((string) static::get($section, $key, $default ?? ''));
    }

    /**
     * Ambil seluruh konten satu section sebagai array [key => value].
     */
    public static function section(string $section): array
    {
        return static::contents()
            ->filter(fn ($c, string $fullKey) => str_starts_with($fullKey, $section.'.'))
            ->mapWithKeys(fn (Content $c) => [$c->key => $c->value])
            ->all();
    }

    /**
     * Ambil nilai setting global.
     *
     * @param  mixed  $default
     */
    public static function setting(string $key, $default = null): mixed
    {
        return static::settings()->get($key)?->value ?? $default;
    }

    /**
     * Bersihkan cache data dalam memory (dipanggil setelah update via admin).
     */
    public static function flush(): void
    {
        static::$contents = null;
        static::$settings = null;
    }
}

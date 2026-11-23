<?php

namespace App\Support;

/**
 * URL logo & favicon situs.
 *
 * Admin bisa mengunggah logo/favicon lewat menu Pengaturan. Jika belum
 * diunggah, tampilan memakai aset bawaan di public/images.
 */
class Branding
{
    public static function logoUrl(): string
    {
        return self::url('branding.logo', 'images/belidolar-logo.svg');
    }

    public static function faviconUrl(): string
    {
        return self::url('branding.favicon', 'images/belidolar-logo.svg');
    }

    /**
     * URL publik untuk file di disk "public".
     *
     * Memakai asset() agar host/port mengikuti halaman yang sedang dibuka,
     * sehingga tetap sama-origin (tidak diblokir CSP) baik saat diakses
     * lewat `php artisan serve` maupun subfolder XAMPP.
     */
    public static function storageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    private static function url(string $key, string $fallback): string
    {
        return self::storageUrl(SiteContent::setting($key)) ?? asset($fallback);
    }
}

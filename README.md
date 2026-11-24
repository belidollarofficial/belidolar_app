# Beli Dolar

Aplikasi web layanan top up saldo PayPal, convert USD ke IDR, pembayaran invoice PayPal, dan jasa bayar online.

## Teknologi

- Laravel 13
- Livewire 3 + Volt
- Tailwind CSS + Vite
- MySQL

## Fitur

- Landing page dengan form order, pelacakan transaksi, testimoni, dan FAQ.
- Layanan: Top Up Saldo PayPal, Convert USD ke IDR, Bayar Invoice PayPal, Jasa Bayar Online.
- Blog dengan editor WYSIWYG dan sanitasi HTML.
- Admin: transaksi, rekening bank, metode pencairan, rate, fee, konten landing page, logo & favicon, blog, FAQ, testimoni, dan laporan.
- Syarat & Ketentuan yang dapat diedit admin dan tampil di landing page serta halaman legal.

## Menjalankan di lokal

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database di `.env`, lalu:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://localhost:8000`.

## Test

```bash
php artisan test
```

## Deploy

- Gunakan PHP 8.3 atau lebih baru.
- Arahkan document root domain ke folder `public`.
- Jalankan `php artisan migrate --force`.
- Jalankan `php artisan storage:link` agar logo, favicon, cover blog, dan bukti transfer dapat diakses.
- Set `APP_ENV=production`, `APP_DEBUG=false`, dan `APP_URL=https://domain-anda`.
- Jalankan `npm run build` sebelum mengunggah, atau unggah folder `public/build` yang sudah dibangun.
- Pastikan folder `storage` dan `bootstrap/cache` dapat ditulisi.

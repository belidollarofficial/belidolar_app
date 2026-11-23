<?php

use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PayoutMethodController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RateRangeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BlogController;
use App\Livewire\Admin\BannedWords;
use App\Livewire\Admin\ChangePassword;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\Testimonials;
use App\Livewire\LandingPage\Beranda;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

Route::get('/', Beranda::class)->name('beranda');

// Fallback file publik di storage ketika symbolic link public/storage belum
// tersedia (misalnya shared hosting tanpa akses Terminal).
Route::get('storage/{path}', function (string $path) {
    $disk = Storage::disk('public');

    abort_unless($disk->exists($path), 404);

    return $disk->response($path);
})->where('path', '.*')->name('storage.public');

// Blog publik.
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Halaman informasi & legal (publik).
Volt::route('tentang-kami', 'pages.legal.about')->name('legal.about');
Volt::route('cara-order', 'pages.legal.how-to-order')->name('legal.how-to-order');
Volt::route('syarat-ketentuan', 'pages.legal.terms')->name('legal.terms');
Volt::route('kebijakan-privasi', 'pages.legal.privacy')->name('legal.privacy');

// SEO: sitemap & robots.txt dinamis agar URL mengikuti APP_URL.
Route::get('sitemap.xml', function () {
    $urls = [
        ['loc' => route('beranda'), 'priority' => '1.0'],
        ['loc' => route('legal.about'), 'priority' => '0.6'],
        ['loc' => route('legal.how-to-order'), 'priority' => '0.6'],
        ['loc' => route('legal.terms'), 'priority' => '0.4'],
        ['loc' => route('legal.privacy'), 'priority' => '0.4'],
        ['loc' => route('blog.index'), 'priority' => '0.7'],
    ];

    foreach (Post::query()->published()->orderByDesc('published_at')->get() as $post) {
        $urls[] = ['loc' => route('blog.show', $post->slug), 'priority' => '0.6'];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
        .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

    foreach ($urls as $url) {
        $xml .= '  <url><loc>'.e($url['loc']).'</loc><priority>'.$url['priority'].'</priority></url>'."\n";
    }

    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

Route::get('robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        'Disallow: /login',
        'Disallow: /dashboard',
        '',
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::middleware(['auth'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('users');

    Route::get('contents', [ContentController::class, 'index'])->name('contents.index');
    Route::put('contents', [ContentController::class, 'update'])->name('contents.update');
    Route::put('contents/{content}/reset', [ContentController::class, 'reset'])->name('contents.reset');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('rate-ranges', RateRangeController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy'])
        ->names('rate-ranges');

    Route::resource('bank-accounts', BankAccountController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy'])
        ->names('bank-accounts');

    Route::resource('payout-methods', PayoutMethodController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy'])
        ->names('payout-methods');

    Route::resource('transactions', TransactionController::class)
        ->only(['index', 'create', 'store', 'show', 'update', 'destroy'])
        ->names('transactions');

    // Tandai notifikasi WA terkirim & ambil link wa.me untuk di-klik admin.
    Route::post('transactions/{transaction}/notify', [TransactionController::class, 'notify'])
        ->name('transactions.notify');

    Route::get('testimonials', Testimonials::class)->name('testimonials.index');

    Route::resource('posts', PostController::class)
        ->except(['show'])
        ->names('posts');

    Route::resource('faqs', FaqController::class)
        ->except(['show'])
        ->names('faqs');

    Route::get('banned-words', BannedWords::class)->name('banned-words.index');

    Route::get('reports', Reports::class)->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('change-password', ChangePassword::class)->name('password.edit');
});

// Backward compatible: /dashboard (Breeze default) → admin dashboard
Route::redirect('/dashboard', '/admin/dashboard')->name('dashboard');

require __DIR__.'/auth.php';

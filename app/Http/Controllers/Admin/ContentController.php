<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Transaction;
use App\Support\ContentDefaults;
use App\Support\HtmlSanitizer;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $sections = Content::query()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $termsServices = Transaction::SERVICES;
        $termsService = (string) $request->query('terms_service', 'topup');

        if (! array_key_exists($termsService, $termsServices)) {
            $termsService = 'topup';
        }

        $termsContent = Content::query()
            ->where('section', 'syarat_ketentuan')
            ->where('key', $termsService)
            ->first();

        $termsDefault = ContentDefaults::value('syarat_ketentuan', $termsService);

        return view('admin.contents', compact(
            'sections',
            'termsServices',
            'termsService',
            'termsContent',
            'termsDefault',
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('contents', []);

        if (! is_array($values) || $values === []) {
            return back()->with('error', 'Tidak ada perubahan yang dikirim.');
        }

        foreach ($values as $section => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $value) {
                $content = Content::query()
                    ->where('section', $section)
                    ->where('key', $key)
                    ->first();

                if (! $content) {
                    continue;
                }

                // Konten WYSIWYG dibersihkan sebelum disimpan.
                if ($content->type === 'wysiwyg') {
                    $value = HtmlSanitizer::clean((string) $value);
                }

                $content->update(['value' => $value ?? '']);
            }
        }

        SiteContent::flush();

        return back()->with('success', 'Konten landing page berhasil diperbarui.');
    }

    /**
     * Kembalikan satu item konten ke nilai bawaan (default).
     */
    public function reset(Content $content): RedirectResponse
    {
        $default = ContentDefaults::value($content->section, $content->key);

        if ($default === null) {
            return back()->with('error', 'Nilai default untuk konten ini tidak ditemukan.');
        }

        $content->update(['value' => $default]);

        SiteContent::flush();

        return back()->with('success', 'Konten "'.$content->label.'" berhasil dikembalikan ke default.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Support\ContentDefaults;
use App\Support\HtmlSanitizer;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        $this->ensureTermsDefaultsExist();

        $sections = Content::query()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        return view('admin.contents', compact('sections'));
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
                $content = $this->findOrCreateContent($section, $key);

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
     * Isi default terms khusus layanan yang belum ada, tanpa menimpa
     * teks yang sudah pernah diedit admin.
     */
    private function ensureTermsDefaultsExist(): void
    {
        foreach (ContentDefaults::all()['syarat_ketentuan'] ?? [] as [$key, $label, $type, $value]) {
            Content::firstOrCreate(
                ['section' => 'syarat_ketentuan', 'key' => $key],
                [
                    'label' => $label,
                    'type' => $type,
                    'value' => $value,
                    'sort_order' => (int) Content::max('sort_order') + 1,
                ],
            );
        }
    }

    private function findOrCreateContent(string $section, string $key): ?Content
    {
        $content = Content::query()
            ->where('section', $section)
            ->where('key', $key)
            ->first();

        if ($content) {
            return $content;
        }

        $default = ContentDefaults::find($section, $key);

        if ($default === null) {
            return null;
        }

        return Content::create([
            'section' => $section,
            'key' => $key,
            'label' => $default['label'],
            'type' => $default['type'],
            'value' => $default['value'],
            'sort_order' => (int) Content::max('sort_order') + 1,
        ]);
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

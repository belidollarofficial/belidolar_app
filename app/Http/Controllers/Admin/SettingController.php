<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('settings', []);
        $files = $request->file('files', []);
        $removals = $request->input('remove_files', []);

        $hasValues = is_array($values) && $values !== [];
        $hasFiles = is_array($files) && $files !== [];
        $hasRemovals = is_array($removals) && $removals !== [];

        if (! $hasValues && ! $hasFiles && ! $hasRemovals) {
            return back()->with('error', 'Tidak ada perubahan yang dikirim.');
        }

        $request->validate([
            'files.branding.logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'files.branding.favicon' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg,ico', 'max:1024'],
            'remove_files' => ['nullable', 'array'],
        ], [
            'files.branding.logo.mimes' => 'Logo harus berupa file PNG, JPG, WebP, atau SVG.',
            'files.branding.logo.max' => 'Ukuran logo maksimal 2 MB.',
            'files.branding.favicon.mimes' => 'Favicon harus berupa file PNG, JPG, WebP, SVG, atau ICO.',
            'files.branding.favicon.max' => 'Ukuran favicon maksimal 1 MB.',
        ]);

        foreach ($values as $category => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $value) {
                Setting::query()
                    ->where('category', $category)
                    ->where('key', $key)
                    ->update(['value' => $value ?? '']);
            }
        }

        $stored = [];

        foreach ($files as $category => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                $setting = Setting::query()
                    ->where('category', $category)
                    ->where('key', $key)
                    ->first();

                if (! $setting) {
                    continue;
                }

                $this->deleteStoredFile($setting->value);

                $setting->update(['value' => $file->store('branding', 'public')]);
                $stored[$category][$key] = true;
            }
        }

        // Hapus file hanya bila admin mencentang "hapus" dan tidak mengunggah
        // file baru untuk setting yang sama.
        foreach ($removals as $category => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach (array_keys($fields) as $key) {
                if (isset($stored[$category][$key])) {
                    continue;
                }

                $setting = Setting::query()
                    ->where('category', $category)
                    ->where('key', $key)
                    ->first();

                if (! $setting) {
                    continue;
                }

                $this->deleteStoredFile($setting->value);

                $setting->update(['value' => '']);
            }
        }

        SiteContent::flush();

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Hapus file branding lama dari storage (bukan aset bawaan public/).
     */
    private function deleteStoredFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'branding/')) {
            Storage::disk('public')->delete($path);
        }
    }
}

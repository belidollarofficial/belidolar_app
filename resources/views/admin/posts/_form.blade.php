@php
    $isEdit = isset($post) && $post !== null;
    $action = $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-5" enctype="multipart/form-data" id="postForm">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="admin-card p-4 border-l-4 !border-l-red-500">
            <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-white mb-1.5">Judul Artikel <span class="text-red-400">*</span></label>
            <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" class="form-input" placeholder="Contoh: Tips Aman Top Up Saldo PayPal" required />
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Slug URL (opsional)</label>
                <input type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}" class="form-input font-mono" placeholder="otomatis-dari-judul" />
                <p class="text-xs text-[#94a3b8] mt-1">Kosongkan untuk dibuat otomatis dari judul.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Tanggal Publikasi</label>
                <input type="datetime-local" name="published_at"
                    value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                    class="form-input" />
                <p class="text-xs text-[#94a3b8] mt-1">Kosongkan untuk memakai waktu saat dipublikasikan.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-white mb-1.5">Ringkasan / Excerpt (opsional)</label>
            <textarea name="excerpt" rows="2" class="form-textarea" placeholder="Ringkasan singkat artikel untuk daftar blog...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            <p class="text-xs text-[#94a3b8] mt-1">Jika kosong, ringkasan diambil otomatis dari isi artikel.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-white mb-1.5">Isi Artikel <span class="text-red-400">*</span></label>

            @include('admin.partials.rte', [
                'name' => 'content',
                'value' => old('content', $post->content ?? ''),
                'id' => 'postContent',
            ])
            <p class="text-xs text-[#94a3b8] mt-1">Gunakan toolbar di atas untuk memformat teks. HTML berbahaya akan dibersihkan otomatis saat disimpan.</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Gambar Sampul (opsional)</label>
                <input type="file" name="cover" accept="image/png,image/jpeg,image/webp" class="form-input file:bg-[#334155] file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3" />
                <p class="text-xs text-[#94a3b8] mt-1">Format JPG / PNG / WebP, maks 4MB.</p>
                @if (! empty($post?->cover_url))
                    <img src="{{ $post->cover_url }}" alt="Sampul saat ini" class="mt-3 rounded-lg border border-[#334155] max-h-40" />
                @endif
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm text-[#e5e7eb] pb-3">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-[#334155] bg-[#0f1724]" @checked(old('is_published', $post->is_published ?? false)) />
                    Publikasikan artikel ini
                </label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold">
            <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Artikel' }}
        </button>
        <a href="{{ route('admin.posts.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl text-sm font-semibold no-underline">Batal</a>
    </div>
</form>

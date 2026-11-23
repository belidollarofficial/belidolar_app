<x-admin-layout>
    @section('title', 'Edit Artikel')

    <div class="max-w-4xl space-y-5">
        <a href="{{ route('admin.posts.index') }}" class="text-sm text-[#94a3b8] hover:text-white transition no-underline inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
        </a>

        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Edit Artikel</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Perbarui konten artikel blog.</p>
            </div>
            @if ($post->is_published)
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn-ghost px-4 py-2 rounded-xl text-sm font-semibold no-underline inline-flex items-center gap-2">
                    <i class="fas fa-eye"></i> Lihat Artikel
                </a>
            @endif
        </div>

        @include('admin.posts._form', ['post' => $post])
    </div>
</x-admin-layout>

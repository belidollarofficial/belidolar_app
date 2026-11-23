<x-admin-layout>
    @section('title', 'Tulis Artikel')

    <div class="max-w-4xl space-y-5">
        <a href="{{ route('admin.posts.index') }}" class="text-sm text-[#94a3b8] hover:text-white transition no-underline inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
        </a>

        <div>
            <h2 class="text-2xl font-extrabold text-white">Tulis Artikel Baru</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Buat artikel blog dengan editor WYSIWYG.</p>
        </div>

        @include('admin.posts._form', ['post' => null])
    </div>
</x-admin-layout>

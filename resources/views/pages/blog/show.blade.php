<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Blog
        </a>

        <header class="mt-6 mb-8">
            <p class="text-xs text-gray-500">
                <i class="far fa-calendar mr-1"></i>
                {{ ($post->published_at ?? $post->created_at)->format('d M Y') }}
                <span class="mx-1">•</span>
                <i class="far fa-clock mr-1"></i>{{ $post->reading_time }} menit baca
            </p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-3 leading-tight">{{ $post->title }}</h1>
            @if (filled($post->excerpt))
                <p class="text-base text-gray-400 mt-3 leading-relaxed">{{ $post->excerpt }}</p>
            @endif
        </header>

        @if ($post->cover_url)
            <div class="mb-8 rounded-2xl overflow-hidden border border-[#334155]">
                <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="w-full h-auto" />
            </div>
        @endif

        <article class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <div class="article-content">
                {!! $post->content_html !!}
            </div>
        </article>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#22d3ee] hover:gap-3 transition-all">
                <i class="fas fa-arrow-left text-xs"></i> Lihat Artikel Lainnya
            </a>
            <a href="{{ route('beranda') }}#form-order" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2">
                <i class="fas fa-clipboard-list"></i> Order Sekarang
            </a>
        </div>
    </div>
</x-app-layout>

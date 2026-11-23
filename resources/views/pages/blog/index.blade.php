<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-16">
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-cyan-300 bg-cyan-500/10 border border-cyan-500/30 rounded-full px-3 py-1">
                <i class="fas fa-newspaper"></i> Blog
            </span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">Artikel &amp; Informasi</h1>
            <p class="text-sm text-gray-400 mt-2 max-w-2xl mx-auto">
                Tips, informasi, dan update terbaru seputar layanan pembayaran digital Beli Dolar.
            </p>
        </div>

        @if ($posts->isEmpty())
            <div class="bg-[#1e293b] border border-[#334155] rounded-2xl p-10 text-center text-sm text-gray-400">
                Belum ada artikel yang dipublikasikan.
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <article class="bg-[#1e293b] border border-[#334155] rounded-2xl overflow-hidden card-shadow flex flex-col hover:-translate-y-1 hover:border-[#4f46e5]/50 transition-all">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-[16/9] bg-gradient-to-br from-[#4f46e5] to-[#06b6d4] relative overflow-hidden">
                            @if ($post->cover_url)
                                <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover" loading="lazy" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white/70 text-4xl">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                        </a>

                        <div class="p-5 flex flex-col flex-1">
                            <p class="text-xs text-gray-500">
                                <i class="far fa-calendar mr-1"></i>
                                {{ ($post->published_at ?? $post->created_at)->format('d M Y') }}
                                <span class="mx-1">•</span>
                                {{ $post->reading_time }} menit baca
                            </p>
                            <h2 class="text-lg font-bold text-white mt-2 leading-snug">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-[#22d3ee] transition">{{ $post->title }}</a>
                            </h2>
                            <p class="text-sm text-gray-400 mt-2 leading-relaxed flex-1">{{ $post->summary }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#22d3ee] hover:gap-3 transition-all">
                                Baca Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

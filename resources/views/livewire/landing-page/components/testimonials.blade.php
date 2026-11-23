<!-- ===== TESTIMONI PELANGGAN ===== -->
<section id="testimoni" class="mb-12 scroll-mt-20">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white">
            @content('testimoni', 'title') <span class="gradient-text">@content('testimoni', 'title_highlight')</span>
        </h2>
        <p class="text-gray-400 mt-2">@content('testimoni', 'subtitle')</p>
    </div>

    @if ($items->isEmpty())
        <p class="text-sm text-gray-400 text-center py-8">Belum ada testimoni. Jadilah yang pertama memberi ulasan!</p>
    @else
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($items as $testimonial)
                <div class="bg-[#1e293b] rounded-2xl card-shadow p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(trim($testimonial->name), 0, 1) ?: 'A') }}
                        </div>
                        <div>
                            <p class="font-bold text-white">{{ $testimonial->name }}</p>
                            <div class="text-yellow-400 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $testimonial->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-300">{{ $testimonial->message }}</p>
                </div>
            @endforeach
        </div>
    @endif
</section>
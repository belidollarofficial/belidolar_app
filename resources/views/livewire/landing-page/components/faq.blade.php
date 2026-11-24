<!-- ===== FAQ (Pertanyaan Umum) ===== -->
<section id="faq" class="mb-12 scroll-mt-20">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white">
            Pertanyaan <span class="gradient-text">Umum</span>
        </h2>
        <p class="text-gray-400 mt-2">Temukan jawaban dari pertanyaan yang paling sering ditanyakan.</p>
    </div>

    @if ($faqs->isEmpty())
        <div class="max-w-3xl mx-auto bg-[#1e293b] border border-[#334155] rounded-2xl p-8 text-center text-sm text-gray-400">
            Belum ada pertanyaan yang ditambahkan.
        </div>
    @else
        <div class="max-w-3xl mx-auto space-y-3">
            @foreach ($faqs as $faq)
                <details class="group bg-[#1e293b] border border-[#334155] rounded-2xl overflow-hidden transition hover:border-[#4f46e5]/50">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-5 py-4 select-none [&::-webkit-details-marker]:hidden">
                        <span class="font-semibold text-white">{{ $faq->question }}</span>
                        <i class="fas fa-chevron-down text-[#22d3ee] text-sm transition-transform duration-300 group-open:rotate-180 shrink-0"></i>
                    </summary>
                    <div class="px-5 pb-5 pt-0 text-sm text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $faq->answer }}
                    </div>
                </details>
            @endforeach
        </div>
    @endif
</section>

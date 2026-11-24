@php
    $generalTerms = \App\Support\SiteContent::rich(
        'syarat_ketentuan',
        'content',
        \App\Support\ContentDefaults::value('syarat_ketentuan', 'content')
    );

    $serviceTerms = collect(\App\Models\Transaction::SERVICES)
        ->map(fn (string $label, string $key) => [
            'key' => $key,
            'label' => $label,
            'html' => \App\Support\SiteContent::rich(
                'syarat_ketentuan',
                $key,
                \App\Support\ContentDefaults::value('syarat_ketentuan', $key)
            ),
        ])
        ->filter(fn (array $service) => trim(strip_tags($service['html'])) !== '')
        ->values();
@endphp

<div class="space-y-4">
    @if (trim(strip_tags($generalTerms)) !== '')
        <div class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <div class="article-content">
                {!! $generalTerms !!}
            </div>
        </div>
    @endif

    @foreach ($serviceTerms as $service)
        <details class="group bg-[#1e293b] border border-[#334155] rounded-2xl overflow-hidden transition hover:border-[#4f46e5]/50">
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-4 select-none [&::-webkit-details-marker]:hidden">
                <span class="font-bold text-white">{{ $service['label'] }}</span>
                <i class="fas fa-chevron-down text-[#22d3ee] text-sm transition-transform duration-300 group-open:rotate-180 shrink-0"></i>
            </summary>
            <div class="px-6 pb-6 pt-0 article-content">
                {!! $service['html'] !!}
            </div>
        </details>
    @endforeach
</div>

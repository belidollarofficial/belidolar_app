@php
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

<div class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
    @foreach ($serviceTerms as $service)
        <div class="{{ $loop->first ? '' : 'mt-8 border-t border-[#334155] pt-6' }}">
            <h2 class="text-xl font-bold text-white mb-3">{{ $service['label'] }}</h2>
            <div class="article-content text-sm">
                {!! $service['html'] !!}
            </div>
        </div>
    @endforeach
</div>

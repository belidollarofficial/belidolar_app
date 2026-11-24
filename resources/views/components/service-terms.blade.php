@props(['service', 'title' => 'Syarat & Ketentuan'])

@php
    $label = \App\Models\Transaction::SERVICES[$service] ?? $service;
    $html = \App\Support\SiteContent::rich(
        'syarat_ketentuan',
        $service,
        \App\Support\ContentDefaults::value('syarat_ketentuan', $service)
    );
@endphp

@if (trim(strip_tags($html)) !== '')
    <div {{ $attributes->class(['border border-[#334155] rounded-2xl p-6']) }}>
        <div class="flex flex-wrap items-center gap-2 border-b border-[#334155] pb-3 mb-3">
            <h3 class="font-bold text-white">
                <i class="fas fa-file-contract text-[#22d3ee] mr-1"></i> {{ $title }}
            </h3>
            <span class="text-xs font-semibold text-cyan-200 bg-cyan-500/10 border border-cyan-500/30 rounded-full px-2.5 py-1">
                {{ $label }}
            </span>
        </div>

        <div class="article-content text-sm">
            {!! $html !!}
        </div>
    </div>
@endif

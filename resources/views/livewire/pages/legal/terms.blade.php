<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

@php
    $termsHtml = \App\Support\SiteContent::rich(
        'syarat_ketentuan',
        'content',
        \App\Support\ContentDefaults::value('syarat_ketentuan', 'content')
    );
@endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-8">
        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-cyan-300 bg-cyan-500/10 border border-cyan-500/30 rounded-full px-3 py-1">
            <i class="fas fa-file-contract"></i> Legal
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">@content('syarat_ketentuan', 'title', 'Syarat & Ketentuan')</h1>
        <p class="text-sm text-gray-400 mt-2">Terakhir diperbarui: @content('legal', 'updated_at', 'November 2026')</p>
    </div>

    <div class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
        <div class="article-content">
            {!! $termsHtml !!}
        </div>
    </div>
</div>

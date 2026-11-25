<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

@php
    $howToOrderHtml = \App\Support\SiteContent::rich(
        'cara_order_page',
        'content',
        \App\Support\ContentDefaults::value('cara_order_page', 'content')
    );
@endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-8">
        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-cyan-300 bg-cyan-500/10 border border-cyan-500/30 rounded-full px-3 py-1">
            <i class="fas fa-list-check"></i> Panduan
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">Cara Order</h1>
        <p class="text-sm text-gray-400 mt-2">Ikuti langkah berikut untuk menyelesaikan pesanan Anda.</p>
    </div>

    <div class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
        <div class="article-content">
            {!! $howToOrderHtml !!}
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('beranda') }}#form-order" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] text-white text-sm font-bold px-6 py-3 rounded-xl hover:brightness-110 transition">
            <i class="fas fa-paper-plane"></i> Order Sekarang
        </a>
    </div>
</div>

<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    //
}; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16">
    <div class="mb-8">
        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-cyan-300 bg-cyan-500/10 border border-cyan-500/30 rounded-full px-3 py-1">
            <i class="fas fa-circle-info"></i> Informasi
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">Tentang Kami</h1>
        <p class="text-sm text-gray-400 mt-2">Terakhir diperbarui: @content('legal', 'updated_at', '2021')</p>
    </div>

    <div class="space-y-6">
        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-bold text-white mb-3">Siapa Beli Dolar?</h2>
            <p class="text-sm text-gray-400 leading-relaxed">
                Beli Dolar adalah layanan digital yang membantu pelanggan di Indonesia untuk melakukan
                top up saldo PayPal, mengonversi USD ke Rupiah, serta membayar invoice PayPal dengan
                proses yang cepat, mudah, dan aman. Kami hadir sebagai jembatan antara kebutuhan
                transaksi internasional Anda dan metode pembayaran lokal.
            </p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-bold text-white mb-3">Layanan Kami</h2>
            <ul class="space-y-3 text-sm text-gray-400">
                <li class="flex gap-3">
                    <i class="fas fa-wallet text-[#818cf8] mt-0.5"></i>
                    <span><span class="text-white font-semibold">Top Up Saldo PayPal</span> &mdash; isi saldo akun PayPal Anda menggunakan pembayaran Rupiah melalui rekening bank yang tersedia.</span>
                </li>
                <li class="flex gap-3">
                    <i class="fas fa-right-left text-[#22d3ee] mt-0.5"></i>
                    <span><span class="text-white font-semibold">Convert USD ke IDR</span> &mdash; cairkan saldo USD Anda ke rekening bank atau e-wallet dengan rate kompetitif.</span>
                </li>
                <li class="flex gap-3">
                    <i class="fas fa-file-invoice-dollar text-[#fbbf24] mt-0.5"></i>
                    <span><span class="text-white font-semibold">Bayar Invoice PayPal</span> &mdash; selesaikan tagihan invoice PayPal Anda tanpa perlu kartu kredit internasional.</span>
                </li>
            </ul>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-bold text-white mb-3">Komitmen Kami</h2>
            <p class="text-sm text-gray-400 leading-relaxed">
                Setiap transaksi ditangani secara manual oleh tim kami pada jam operasional yang
                telah ditentukan. Kami mengutamakan transparansi nominal, keamanan data pelanggan,
                dan komunikasi yang jelas melalui WhatsApp. Kepuasan serta kepercayaan pelanggan
                adalah prioritas utama kami.
            </p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-bold text-white mb-3">Hubungi Kami</h2>
            <p class="text-sm text-gray-400 leading-relaxed">
                Pertanyaan, kendala transaksi, atau kerja sama dapat disampaikan melalui WhatsApp
                admin yang tertera di halaman utama. Kami akan merespons pada jam operasional.
            </p>
            <a href="{{ route('beranda') }}#form-order" class="inline-flex items-center gap-2 mt-4 bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:brightness-110 transition">
                <i class="fas fa-paper-plane"></i> Mulai Order
            </a>
        </section>
    </div>
</div>

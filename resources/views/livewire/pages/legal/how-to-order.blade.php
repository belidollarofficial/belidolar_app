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
            <i class="fas fa-list-check"></i> Panduan
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">Cara Order</h1>
        <p class="text-sm text-gray-400 mt-2">Ikuti langkah berikut untuk menyelesaikan pesanan Anda.</p>
    </div>

    <div class="space-y-6">
        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-4">Langkah Umum</h2>
            <ol class="space-y-4 text-sm text-gray-400">
                <li class="flex gap-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#4f46e5]/20 text-[#818cf8] flex items-center justify-center font-bold">1</span>
                    <span>Pilih layanan pada form order: <span class="text-white font-semibold">Top Up Saldo PayPal</span>, <span class="text-white font-semibold">Convert USD ke IDR</span>, atau <span class="text-white font-semibold">Bayar Invoice PayPal</span>.</span>
                </li>
                <li class="flex gap-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#4f46e5]/20 text-[#818cf8] flex items-center justify-center font-bold">2</span>
                    <span>Isi nama, nomor WhatsApp aktif, email (opsional), dan nama akun PayPal tujuan.</span>
                </li>
                <li class="flex gap-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#4f46e5]/20 text-[#818cf8] flex items-center justify-center font-bold">3</span>
                    <span>Masukkan nominal USD. Estimasi Rupiah dan rate akan tampil otomatis sesuai layanan yang dipilih.</span>
                </li>
                <li class="flex gap-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#4f46e5]/20 text-[#818cf8] flex items-center justify-center font-bold">4</span>
                    <span>Lengkapi detail pembayaran atau pencairan, lalu tekan tombol <span class="text-white font-semibold">Order via WhatsApp</span>.</span>
                </li>
                <li class="flex gap-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#4f46e5]/20 text-[#818cf8] flex items-center justify-center font-bold">5</span>
                    <span>Anda akan diarahkan ke WhatsApp admin dengan ringkasan pesanan. Kirim pesan tersebut dan ikuti instruksi admin.</span>
                </li>
            </ol>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-4">Detail per Layanan</h2>
            <div class="space-y-4 text-sm text-gray-400">
                <div class="border border-[#334155] rounded-xl p-4">
                    <h3 class="text-white font-semibold mb-1"><i class="fas fa-wallet text-[#818cf8] mr-2"></i>Top Up Saldo PayPal</h3>
                    <p>Pilih rekening bank tujuan transfer, lalu bayar sesuai nominal Rupiah yang tertera. Setelah pembayaran dikonfirmasi, saldo akan dikirim ke akun PayPal Anda.</p>
                </div>
                <div class="border border-[#334155] rounded-xl p-4">
                    <h3 class="text-white font-semibold mb-1"><i class="fas fa-right-left text-[#22d3ee] mr-2"></i>Convert USD ke IDR</h3>
                    <p>Pilih tipe pencairan (bank atau e-wallet) beserta metode tujuannya. Rate mengikuti rentang nominal yang berlaku. Dana dicairkan ke rekening/e-wallet pilihan Anda.</p>
                </div>
                <div class="border border-[#334155] rounded-xl p-4">
                    <h3 class="text-white font-semibold mb-1"><i class="fas fa-file-invoice-dollar text-[#fbbf24] mr-2"></i>Bayar Invoice PayPal</h3>
                    <p>Sertakan tautan invoice PayPal Anda, pilih rekening bank tujuan pembayaran, dan lakukan transfer sesuai nominal Rupiah. Admin akan memproses pembayaran invoice tersebut.</p>
                </div>
            </div>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">Melacak Pesanan</h2>
            <p class="text-sm text-gray-400 leading-relaxed">
                Gunakan kode transaksi (format <span class="text-white font-mono">ZP-YYMMDD-XXXXX</span>)
                dan nomor WhatsApp yang Anda masukkan saat order pada bagian pelacakan transaksi di
                halaman utama untuk melihat status pesanan.
            </p>
        </section>

        <div class="text-center">
            <a href="{{ route('beranda') }}#form-order" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] text-white text-sm font-bold px-6 py-3 rounded-xl hover:brightness-110 transition">
                <i class="fas fa-paper-plane"></i> Order Sekarang
            </a>
        </div>
    </div>
</div>

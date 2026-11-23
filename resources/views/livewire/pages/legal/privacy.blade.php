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
            <i class="fas fa-shield-halved"></i> Privasi
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mt-4">Kebijakan Privasi</h1>
        <p class="text-sm text-gray-400 mt-2">Terakhir diperbarui: @content('legal', 'updated_at', 'Januari 2026')</p>
    </div>

    <div class="space-y-6 text-sm text-gray-400 leading-relaxed">
        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">1. Data yang Kami Kumpulkan</h2>
            <p>Kami hanya mengumpulkan data yang diperlukan untuk memproses pesanan Anda, meliputi:</p>
            <ul class="list-disc pl-5 space-y-2 mt-3">
                <li>Nama dan nomor WhatsApp.</li>
                <li>Alamat email (opsional).</li>
                <li>Nama akun PayPal tujuan.</li>
                <li>Detail transaksi seperti layanan, nominal, rekening tujuan, dan bukti pembayaran.</li>
                <li>Data teknis dasar seperti alamat IP untuk mencegah penyalahgunaan layanan.</li>
            </ul>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">2. Tujuan Penggunaan Data</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Memproses dan menyelesaikan transaksi yang Anda ajukan.</li>
                <li>Menghubungi Anda terkait status pesanan melalui WhatsApp atau email.</li>
                <li>Menjaga keamanan layanan, termasuk mencegah spam dan penipuan.</li>
                <li>Memenuhi kewajiban hukum yang berlaku.</li>
            </ul>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">3. Penyimpanan dan Keamanan</h2>
            <p>
                Data transaksi disimpan pada sistem kami dengan akses terbatas hanya untuk admin yang
                berwenang. Kami menerapkan langkah keamanan yang wajar untuk melindungi data dari
                akses, perubahan, atau penyalahgunaan yang tidak sah. Meskipun demikian, tidak ada
                sistem yang sepenuhnya bebas dari risiko.
            </p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">4. Pembagian Data kepada Pihak Ketiga</h2>
            <p>
                Kami tidak menjual atau menyewakan data pribadi Anda. Data hanya dapat dibagikan
                kepada pihak ketiga apabila diperlukan untuk menyelesaikan transaksi (misalnya
                penyedia layanan pembayaran), atau jika diwajibkan oleh hukum yang berlaku.
            </p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">5. Hak Anda</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>Meminta informasi mengenai data pribadi yang kami simpan.</li>
                <li>Meminta perbaikan atas data yang tidak akurat.</li>
                <li>Meminta penghapusan data sepanjang tidak bertentangan dengan kewajiban hukum atau keperluan audit.</li>
            </ul>
            <p class="mt-3">Permintaan dapat diajukan melalui kontak WhatsApp atau email yang tertera di halaman utama.</p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">6. Cookie dan Teknologi Serupa</h2>
            <p>
                Situs ini menggunakan cookie sesi untuk menjaga keamanan formulir dan proses
                transaksi. Kami tidak menggunakan cookie untuk pelacakan iklan pihak ketiga.
            </p>
        </section>

        <section class="bg-[#1e293b] border border-[#334155] rounded-2xl p-6 md:p-8">
            <h2 class="text-lg font-bold text-white mb-3">7. Perubahan Kebijakan</h2>
            <p>
                Kebijakan privasi ini dapat diperbarui dari waktu ke waktu. Versi terbaru akan
                selalu dipublikasikan pada halaman ini beserta tanggal pembaruannya.
            </p>
        </section>
    </div>
</div>

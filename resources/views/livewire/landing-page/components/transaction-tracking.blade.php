<!-- ===== LACAK TRANSAKSI ===== -->
<section id="riwayat" class="mb-12 scroll-mt-20">
    <div class="max-w-2xl mx-auto">
        <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 md:p-8">
            <div class="flex items-center gap-3 border-b border-[#334155] pb-4">
                <div class="w-12 h-12 rounded-xl bg-[#4f46e5]/10 flex items-center justify-center">
                    <i class="fas fa-search-location text-2xl text-[#4f46e5]"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Lacak Transaksi</h3>
                    <p class="text-xs text-gray-400">Cek status pesanan Anda dengan kode transaksi.</p>
                </div>
            </div>

            <form wire:submit="search" class="space-y-4 mt-5">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1">
                        <i class="fas fa-receipt text-[#4f46e5] mr-1"></i> Kode Transaksi
                    </label>
                    <input type="text" wire:model="code" placeholder="Contoh: ZP-260609-AB12C"
                            class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1">
                        <i class="fab fa-whatsapp text-[#4f46e5] mr-1"></i> Nomor WhatsApp Pemesan
                    </label>
                    <input type="tel" wire:model="phone" placeholder="Contoh: 0812xxxxxxx"
                            class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                </div>

                <button type="submit"
                        class="w-full btn-primary font-bold py-3 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition">
                    <i class="fas fa-search"></i> Lacak Transaksi
                </button>
            </form>

            @error('code')
                <div class="mt-4 text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">
                    <i class="fas fa-circle-exclamation mr-1"></i> {{ $message }}
                </div>
            @enderror

            @if ($transaction)
                @php
                    $badge = match ($transaction->status) {
                        'pending' => 'bg-yellow-900/30 text-yellow-400',
                        'diproses' => 'bg-blue-900/30 text-blue-400',
                        'selesai' => 'bg-green-900/30 text-green-400',
                        'gagal', 'batal' => 'bg-red-900/30 text-red-400',
                        default => 'bg-gray-700/40 text-gray-300',
                    };
                    $amountLabel = $transaction->amount_label;
                @endphp

                <div class="mt-5 bg-[#0f1724] rounded-xl border border-[#334155] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-mono text-sm text-white">{{ $transaction->transaction_code }}</span>
                        <span class="text-xs px-3 py-1 rounded-full {{ $badge }}">{{ $transaction->status_label }}</span>
                    </div>

                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400">Layanan</dt>
                            <dd class="text-white text-right">{{ $transaction->service_label }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400">Nominal</dt>
                            <dd class="text-white font-semibold text-right">{{ $amountLabel }}</dd>
                        </div>
                        @if ($transaction->hasPayableAmount())
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400">Yang Harus Dibayar</dt>
                                <dd class="text-white font-semibold text-right">{{ $transaction->payable_label }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400">Tanggal Order</dt>
                            <dd class="text-white text-right">{{ $transaction->created_at->translatedFormat('d M Y, H:i') }}</dd>
                        </div>
                        @if ($transaction->admin_note)
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400">Catatan Admin</dt>
                                <dd class="text-gray-200 text-right">{{ $transaction->admin_note }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if ($transaction->proof_url)
                        <a href="{{ $transaction->proof_url }}" target="_blank" rel="noopener"
                           class="mt-4 inline-flex items-center gap-2 text-sm text-[#22d3ee] hover:underline">
                            <i class="fas fa-image"></i> Lihat bukti transaksi
                        </a>
                    @endif

                    <button type="button" wire:click="resetForm"
                            class="mt-5 w-full text-sm text-gray-300 border border-[#334155] rounded-xl py-2.5 hover:bg-[#1e293b] transition">
                        Lacak transaksi lain
                    </button>
                </div>
            @elseif ($searched)
                <div class="mt-4 text-sm text-gray-400 bg-[#0f1724] border border-[#334155] rounded-xl px-4 py-3 text-center">
                    Transaksi tidak ditemukan.
                </div>
            @endif
        </div>
    </div>
</section>

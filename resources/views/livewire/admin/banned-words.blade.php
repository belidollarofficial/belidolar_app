<div class="space-y-5">

    <div>
        <h2 class="text-2xl font-extrabold text-white">Daftar Kata Terlarang</h2>
        <p class="text-sm text-[#94a3b8] mt-1">Testimoni yang mengandung kata ini otomatis masuk status "Menunggu" (tidak langsung tayang). Pencocokan tidak membedakan huruf kapital.</p>
    </div>

    @error('word')
        <div class="text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">{{ $message }}</div>
    @enderror

    <div class="admin-card p-6">
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
            <div class="w-10 h-10 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center text-[#fbbf24]">
                <i class="fas fa-ban"></i>
            </div>
            <div>
                <h3 class="font-bold text-white">Tambah Kata Terlarang</h3>
                <p class="text-xs text-[#94a3b8]">Contoh: penipu, scam, gratis</p>
            </div>
        </div>

        <form wire:submit="add" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-sm font-semibold text-white mb-1.5">Kata</label>
                <input type="text" wire:model="word" placeholder="Masukkan kata terlarang..." class="form-input" />
            </div>
            <div>
                <button type="submit" wire:loading.attr="disabled" wire:target="add"
                        class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    <div class="admin-card p-6">
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
            <div class="w-10 h-10 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]">
                <i class="fas fa-list"></i>
            </div>
            <div>
                <h3 class="font-bold text-white">Daftar Kata</h3>
                <p class="text-xs text-[#94a3b8]">Total {{ $words->count() }} kata terlarang.</p>
            </div>
        </div>

        @if ($words->isEmpty())
            <p class="text-sm text-[#94a3b8] text-center py-8">Belum ada kata terlarang. Semua testimoni akan langsung tayang.</p>
        @else
            <div class="flex flex-wrap gap-3">
                @foreach ($words as $item)
                    <div class="inline-flex items-center gap-2 bg-[#0f1724] border border-[#334155] rounded-xl px-4 py-2">
                        <i class="fas fa-ban text-red-400 text-xs"></i>
                        <span class="text-white font-semibold text-sm">{{ $item->word }}</span>
                        <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Hapus kata '{{ $item->word }}'?"
                                class="text-red-400 hover:text-red-300 transition" title="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
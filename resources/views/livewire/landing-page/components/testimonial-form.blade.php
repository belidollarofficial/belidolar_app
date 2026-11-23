<!-- ===== FORM ULASAN / TESTIMONI ===== -->
<section id="ulasan" class="mb-12 scroll-mt-20">
    <div class="max-w-xl mx-auto">
        <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 md:p-8">
            <div class="flex items-center gap-3 border-b border-[#334155] pb-4">
                <div class="w-12 h-12 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center">
                    <i class="fas fa-comment-dots text-2xl text-[#fbbf24]"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Kirim Testimoni / Ulasan</h3>
                    <p class="text-xs text-gray-400">Bagikan pengalaman Anda menggunakan layanan Beli Dolar.</p>
                </div>
            </div>

            @if ($submitted)
                <div class="mt-6 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-5 text-center">
                    <div class="text-4xl mb-2 text-emerald-400"><i class="fas fa-check-circle"></i></div>
                    @if ($pendingApproval)
                        <p class="text-white font-bold">Terima kasih atas ulasan Anda!</p>
                        <p class="text-sm text-gray-400 mt-1">Ulasan Anda sedang ditinjau admin sebelum tampil di halaman ini.</p>
                    @else
                        <p class="text-white font-bold">Terima kasih atas ulasan Anda!</p>
                        <p class="text-sm text-gray-400 mt-1">Ulasan Anda sudah tampil di bagian testimoni.</p>
                    @endif
                    <button type="button" wire:click="$set('submitted', false)"
                            class="mt-4 text-sm text-cyan-300 hover:underline">Tulis ulasan lain</button>
                </div>
            @else
                <form wire:submit="submit" class="space-y-4 mt-5">
                    <input type="text" wire:model="website" tabindex="-1" autocomplete="off"
                           aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;">

                    @error('form')
                        <div class="text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-user text-[#f59e0b] mr-1"></i> Nama
                        </label>
                        <input type="text" wire:model="name" placeholder="Nama Anda..."
                                class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f59e0b] transition" />
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-star text-[#f59e0b] mr-1"></i> Rating
                        </label>
                        <select wire:model="rating"
                                class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f59e0b] transition">
                            <option value="5">★★★★★ (Sangat Puas)</option>
                            <option value="4">★★★★ (Puas)</option>
                            <option value="3">★★★ (Biasa Saja)</option>
                            <option value="2">★★ (Kurang Puas)</option>
                            <option value="1">★ (Tidak Puas)</option>
                        </select>
                        @error('rating') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-pen text-[#f59e0b] mr-1"></i> Ulasan
                        </label>
                        <textarea wire:model="message" rows="4" placeholder="Ceritakan pengalaman Anda..."
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#f59e0b] transition resize-none"></textarea>
                        @error('message') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                            class="w-full btn-secondary font-bold py-3.5 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition">
                        <span wire:loading.remove wire:target="submit"><i class="fas fa-paper-plane"></i> Kirim Ulasan</span>
                        <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin"></i> Mengirim...</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
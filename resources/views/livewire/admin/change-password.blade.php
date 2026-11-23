<div class="space-y-5">
    <div>
        <h2 class="text-2xl font-extrabold text-white">Ubah Password</h2>
        <p class="text-sm text-[#94a3b8] mt-1">Perbarui password akun Anda secara berkala untuk menjaga keamanan.</p>
    </div>

    <div class="admin-card p-6 max-w-xl">
        <div class="flex items-center gap-3 pb-4 mb-5 border-b border-[#334155]">
            <div class="w-10 h-10 rounded-xl bg-[#4f46e5]/10 flex items-center justify-center text-[#818cf8]">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h3 class="font-bold text-white">Kredensial Baru</h3>
                <p class="text-xs text-[#94a3b8]">Masukkan password lama untuk konfirmasi.</p>
            </div>
        </div>

        <form wire:submit="update" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-semibold text-white mb-1.5">Password Saat Ini</label>
                <input wire:model="current_password" id="current_password" type="password" name="current_password"
                       autocomplete="current-password" class="form-input" />
                @error('current_password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-white mb-1.5">Password Baru</label>
                <input wire:model="password" id="password" type="password" name="password"
                       autocomplete="new-password" class="form-input" />
                @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-white mb-1.5">Konfirmasi Password Baru</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation"
                       autocomplete="new-password" class="form-input" />
                @error('password_confirmation') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-1">
                <button type="submit" wire:loading.attr="disabled" wire:target="update"
                        class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                    <span wire:loading.remove wire:target="update"><i class="fas fa-save"></i> Simpan Password</span>
                    <span wire:loading wire:target="update"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
<x-admin-layout>
    @section('title', 'Edit User')

    <div class="max-w-2xl">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-[#94a3b8] hover:text-white transition no-underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>

        <div class="admin-card p-6 mt-4">
            <h2 class="text-xl font-extrabold text-white mb-5"><i class="fas fa-user-edit mr-2 text-[#818cf8]"></i>Edit User</h2>

            @if ($errors->any())
                <div class="border border-red-500/40 bg-red-500/10 rounded-xl p-4 mb-5">
                    <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Password <span class="text-xs font-normal text-[#94a3b8]">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-input" />
                    <p class="text-xs text-[#94a3b8] mt-1">Minimal 6 karakter.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl text-sm font-semibold no-underline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
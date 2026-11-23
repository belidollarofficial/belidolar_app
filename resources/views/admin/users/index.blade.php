<x-admin-layout>
    @section('title', 'Manajemen User')

    <div class="space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Daftar User</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Total {{ $users->total() }} user terdaftar.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold no-underline">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="admin-card p-4">
            <div class="flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau email..."
                    class="form-input flex-1" />
                <button type="submit" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold"><i class="fas fa-search mr-1"></i> Cari</button>
            </div>
        </form>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $user->name }}</p>
                                            @if ($user->id === auth()->id())
                                                <span class="text-xs text-[#22d3ee]">Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-[#94a3b8]">{{ $user->email }}</td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge badge-green">Aktif</span>
                                    @else
                                        <span class="badge badge-gray">Belum Verifikasi</span>
                                    @endif
                                </td>
                                <td class="text-[#94a3b8] text-sm">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-ghost w-9 h-9 rounded-lg inline-flex items-center justify-center no-underline" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-9 h-9 rounded-lg inline-flex items-center justify-center bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition" title="Hapus">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-[#94a3b8]">Tidak ada user ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
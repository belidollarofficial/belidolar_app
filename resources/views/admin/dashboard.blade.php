<x-admin-layout>
    @section('title', 'Dashboard')

    <div class="space-y-6">

        <!-- Greeting -->
        <div class="admin-card p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Kelola pengguna, konten landing page, dan pengaturan situs dari sini.</p>
            </div>
            <a href="{{ route('beranda') }}" target="_blank" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold no-underline">
                <i class="fas fa-external-link-alt"></i> Buka Landing Page
            </a>
        </div>

        <!-- Stat Cards -->
        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <a href="{{ route('admin.transactions.index') }}" class="admin-card p-6 flex items-center gap-4 hover:border-[#4f46e5] transition no-underline">
                <div class="w-14 h-14 rounded-2xl bg-[#f59e0b]/10 flex items-center justify-center text-2xl text-[#fbbf24]">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-white">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
                    <p class="text-sm text-[#94a3b8]">Total Transaksi</p>
                    @if ($pendingTransactions > 0)
                        <span class="badge badge-gray mt-1 inline-block">{{ number_format($pendingTransactions, 0, ',', '.') }} menunggu</span>
                    @endif
                </div>
            </a>
            <div class="admin-card p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#4f46e5]/10 flex items-center justify-center text-2xl text-[#818cf8]">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-white">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                    <p class="text-sm text-[#94a3b8]">Total User</p>
                </div>
            </div>
            <div class="admin-card p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#06b6d4]/10 flex items-center justify-center text-2xl text-[#22d3ee]">
                    <i class="fas fa-pen"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-white">{{ number_format($totalContents, 0, ',', '.') }}</p>
                    <p class="text-sm text-[#94a3b8]">Field Konten Landing</p>
                </div>
            </div>
            <div class="admin-card p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#f59e0b]/10 flex items-center justify-center text-2xl text-[#fbbf24]">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-white">{{ number_format($totalSettings, 0, ',', '.') }}</p>
                    <p class="text-sm text-[#94a3b8]">Pengaturan Aktif</p>
                </div>
            </div>
        </div>

        <!-- Latest Transactions -->
        <div class="admin-card overflow-hidden">
            <div class="p-6 pb-4 flex items-center justify-between">
                <h3 class="font-bold text-white text-lg"><i class="fas fa-clock mr-2 text-[#94a3b8]"></i>Transaksi Terbaru</h3>
                <a href="{{ route('admin.transactions.index') }}" class="text-sm font-semibold text-[#818cf8] hover:underline no-underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestTransactions as $transaction)
                            <tr>
                                <td class="font-mono text-xs text-white">{{ $transaction->transaction_code }}</td>
                                <td class="font-semibold text-white">{{ $transaction->customer_name }}</td>
                                <td class="text-[#94a3b8]">{{ $transaction->service_label }}</td>
                                <td class="text-white">{{ $transaction->amount_label }}</td>
                                <td><span class="badge badge-gray">{{ $transaction->status_label }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-[#94a3b8]">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Users -->
        <div class="admin-card overflow-hidden">
            <div class="p-6 pb-4 flex items-center justify-between">
                <h3 class="font-bold text-white text-lg"><i class="fas fa-clock mr-2 text-[#94a3b8]"></i>User Terbaru</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-[#818cf8] hover:underline no-underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestUsers as $user)
                            <tr>
                                <td class="font-semibold text-white">{{ $user->name }}</td>
                                <td class="text-[#94a3b8]">{{ $user->email }}</td>
                                <td class="text-[#94a3b8] text-sm">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-[#94a3b8]">Belum ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.create') }}" class="admin-card p-5 flex items-center gap-4 hover:border-[#4f46e5] transition no-underline">
                <div class="w-12 h-12 rounded-xl bg-[#4f46e5]/10 flex items-center justify-center text-[#818cf8]"><i class="fas fa-user-plus"></i></div>
                <div>
                    <p class="font-bold text-white">Tambah User</p>
                    <p class="text-xs text-[#94a3b8]">Buat akun admin baru</p>
                </div>
            </a>
            <a href="{{ route('admin.contents.index') }}" class="admin-card p-5 flex items-center gap-4 hover:border-[#4f46e5] transition no-underline">
                <div class="w-12 h-12 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]"><i class="fas fa-edit"></i></div>
                <div>
                    <p class="font-bold text-white">Edit Konten</p>
                    <p class="text-xs text-[#94a3b8]">Ubah teks landing page</p>
                </div>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="admin-card p-5 flex items-center gap-4 hover:border-[#4f46e5] transition no-underline">
                <div class="w-12 h-12 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center text-[#fbbf24]"><i class="fas fa-gear"></i></div>
                <div>
                    <p class="font-bold text-white">Pengaturan Situs</p>
                    <p class="text-xs text-[#94a3b8]">Logo, WhatsApp, rate &amp; fee</p>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>
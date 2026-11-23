<x-admin-layout>
    @section('title', 'Rekening Bank')

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Rekening Bank Tujuan Transfer</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Daftar rekening tujuan transfer untuk Top Up &amp; Bayar Invoice. Pelanggan memilih salah satu saat mengisi form order.</p>
        </div>

        @if (session('error'))
            <div class="text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-card p-6">
            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                <div class="w-10 h-10 rounded-xl bg-[#4f46e5]/10 flex items-center justify-center text-[#818cf8]">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">Tambah Rekening</h3>
                    <p class="text-xs text-[#94a3b8]">Contoh: BCA • 1234567890 • a.n. Beli Dolar</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.bank-accounts.store') }}" class="grid sm:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="BCA" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Penerima</label>
                    <input type="text" name="account_name" value="{{ old('account_name') }}" placeholder="Beli Dolar" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nomor Rekening</label>
                    <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="1234567890" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input" />
                </div>
                <div class="sm:col-span-4 pt-1">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Rekening
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                <div class="w-10 h-10 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]">
                    <i class="fas fa-building-columns"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">Daftar Rekening</h3>
                </div>
            </div>

            @if ($accounts->isEmpty())
                <p class="text-sm text-[#94a3b8] text-center py-8">Belum ada rekening. Tambahkan di atas.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Bank</th>
                                <th>Nama Penerima</th>
                                <th>Nomor Rekening</th>
                                <th>Urutan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td class="text-white font-semibold">{{ $account->bank_name }}</td>
                                    <td class="text-white">{{ $account->account_name }}</td>
                                    <td class="text-white font-mono">{{ $account->account_number }}</td>
                                    <td class="text-[#94a3b8]">{{ $account->sort_order }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="text-sm text-[#22d3ee] hover:underline">
                                                <i class="fas fa-pen mr-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.bank-accounts.destroy', $account) }}" onsubmit="return confirm('Hapus rekening ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-400 hover:underline">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

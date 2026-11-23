<x-admin-layout>
    @section('title', 'Metode Pencairan')

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Metode Pencairan (Convert USD → IDR)</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Daftar Bank / E-Wallet tujuan pencairan untuk layanan Convert USD ke IDR. Pelanggan memilih tipe lalu metodenya di form order.</p>
        </div>

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
                    <h3 class="font-bold text-white">Tambah Metode Pencairan</h3>
                    <p class="text-xs text-[#94a3b8]">Contoh: Bank → BCA, E-Wallet → DANA</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.payout-methods.store') }}" class="grid sm:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Tipe</label>
                    <select name="type" class="form-select" required>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Bank / E-Wallet</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="BCA" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input" />
                </div>
                <div class="sm:col-span-3 pt-1">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Metode
                    </button>
                </div>
            </form>
        </div>

        @foreach ($types as $type => $typeLabel)
            <div class="admin-card p-6">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                    <div class="w-10 h-10 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]">
                        <i class="fas {{ $type === 'bank' ? 'fa-building-columns' : 'fa-wallet' }}"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white">{{ $typeLabel }}</h3>
                    </div>
                </div>

                @php $items = $methods->get($type, collect()); @endphp

                @if ($items->isEmpty())
                    <p class="text-sm text-[#94a3b8] text-center py-6">Belum ada {{ $typeLabel }}.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="admin-table w-full">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $method)
                                    <tr>
                                        <td class="text-white font-semibold">{{ $method->name }}</td>
                                        <td class="text-[#94a3b8]">{{ $method->sort_order }}</td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.payout-methods.edit', $method) }}" class="text-sm text-[#22d3ee] hover:underline">
                                                    <i class="fas fa-pen mr-1"></i>Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.payout-methods.destroy', $method) }}" onsubmit="return confirm('Hapus metode ini?')">
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
        @endforeach
    </div>
</x-admin-layout>

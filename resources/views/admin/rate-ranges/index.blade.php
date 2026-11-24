<x-admin-layout>
    @section('title', 'Rate Range')

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Rate Range (Rate per Nominal)</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Atur rate Rupiah per USD berdasarkan rentang nominal. Pelanggan melihat rate ini otomatis saat mengisi nominal di form order.</p>
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
                    <h3 class="font-bold text-white">Tambah Rentang Rate</h3>
                    <p class="text-xs text-[#94a3b8]">Contoh: $11 - $14 → Rp 13.000/USD</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.rate-ranges.store') }}" class="grid sm:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nominal Min (USD)</label>
                    <input type="number" name="min_amount" step="0.01" min="0" value="{{ old('min_amount') }}" placeholder="1" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nominal Max (USD)</label>
                    <input type="number" name="max_amount" step="0.01" min="0" value="{{ old('max_amount') }}" placeholder="10" class="form-input" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Rate (Rp/USD)</label>
                    <input type="number" name="rate" step="0.01" min="0" value="{{ old('rate') }}" placeholder="13000" class="form-input" required />
                </div>
                <div class="sm:col-span-3 pt-1">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Rentang
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                <div class="w-10 h-10 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">Daftar Rentang Rate</h3>
                    <p class="text-xs text-[#94a3b8]">Urutkan dari nominal terkecil.</p>
                </div>
            </div>

            @if ($ranges->isEmpty())
                <p class="text-sm text-[#94a3b8] text-center py-8">Belum ada rentang rate. Tambahkan di atas.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Rentang Nominal</th>
                                <th>Rate (Rp/USD)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranges as $range)
                                <tr>
                                    <td class="text-white font-semibold">${{ number_format((float) $range->min_amount, 0, '.', ',') }} - ${{ number_format((float) $range->max_amount, 0, '.', ',') }}</td>
                                    <td class="text-white">Rp {{ number_format((float) $range->rate, 0, ',', '.') }}/USD</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.rate-ranges.edit', $range) }}" class="text-sm text-[#22d3ee] hover:underline">
                                                <i class="fas fa-pen mr-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.rate-ranges.destroy', $range) }}" onsubmit="return confirm('Hapus rentang ini?')">
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
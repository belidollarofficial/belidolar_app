<x-admin-layout>
    @section('title', 'Transaksi')

    <div class="space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Daftar Transaksi</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Total {{ $transactions->total() }} transaksi.</p>
            </div>
            <a href="{{ route('admin.transactions.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold no-underline inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>
        </div>

        <form method="GET" action="{{ route('admin.transactions.index') }}" class="admin-card p-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari kode, nama, atau nomor..."
                    class="form-input flex-1 min-w-[200px]" />
                <select name="status" class="form-select w-auto min-w-[160px]">
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\Transaction::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold"><i class="fas fa-search mr-1"></i> Cari</button>
                @if ($search !== '' || $status !== '')
                    <a href="{{ route('admin.transactions.index') }}" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold no-underline">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Sumber</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td class="font-mono text-xs text-white">{{ $transaction->transaction_code }}</td>
                                <td>
                                    <p class="font-semibold text-white">{{ $transaction->customer_name }}</p>
                                    <p class="text-xs text-[#94a3b8]">{{ $transaction->customer_phone }}</p>
                                </td>
                                <td class="text-[#94a3b8]">{{ $transaction->service_label }}</td>
                                <td>
                                    <p class="font-semibold text-white">{{ $transaction->amount_label }}</p>
                                    @if ($transaction->hasPayableAmount())
                                        <p class="text-xs text-[#22d3ee]">Dibayar: {{ $transaction->payable_label }}</p>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badge = match ($transaction->status) {
                                            'pending' => 'badge-gray',
                                            'diproses' => 'badge-green',
                                            'selesai' => 'badge-green',
                                            'gagal', 'batal' => 'badge-red',
                                            default => 'badge-gray',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $transaction->status_label }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $transaction->isManual() ? 'badge-green' : 'badge-gray' }}" title="{{ $transaction->source_label }}">
                                        @if ($transaction->isManual()) <i class="fas fa-user-edit mr-1"></i> @else <i class="fas fa-globe mr-1"></i> @endif
                                        {{ $transaction->source_label }}
                                    </span>
                                </td>
                                <td class="text-[#94a3b8] text-sm">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                <td class="text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn-ghost px-4 py-2 rounded-lg inline-flex items-center gap-1.5 no-underline text-sm font-semibold" title="Detail">
                                            <i class="fas fa-eye text-sm"></i> Detail
                                        </a>
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus transaksi {{ $transaction->transaction_code }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-lg inline-flex items-center justify-center bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition" title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-[#94a3b8]">Tidak ada transaksi ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            {{ $transactions->links() }}
        </div>
    </div>
</x-admin-layout>
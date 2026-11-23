<div class="space-y-5">

    @php
        $exportParams = array_filter([
            'from' => $from,
            'to' => $to,
            'service' => $service,
            'status' => $status,
        ]);
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Laporan / Reporting</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Rekapitulasi transaksi berdasarkan rentang tanggal, layanan, dan status.</p>
        </div>
        <a href="{{ route('admin.reports.export', $exportParams) }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2 no-underline">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
    </div>

    <div class="admin-card p-4">
        <div class="flex flex-wrap gap-3">
            <div>
                <label class="block text-xs font-semibold text-[#94a3b8] mb-1">Dari Tanggal</label>
                <input type="date" wire:model.live="from" class="form-input" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#94a3b8] mb-1">Sampai Tanggal</label>
                <input type="date" wire:model.live="to" class="form-input" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#94a3b8] mb-1">Layanan</label>
                <select wire:model.live="service" class="form-select min-w-[180px]">
                    <option value="">Semua Layanan</option>
                    @foreach (\App\Models\Transaction::SERVICES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#94a3b8] mb-1">Status</label>
                <select wire:model.live="status" class="form-select min-w-[160px]">
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\Transaction::STATUSES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($from !== '' || $to !== '' || $service !== '' || $status !== '')
                <div class="flex items-end gap-2">
                    <button type="button" wire:click="resetFilters" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold">
                        <i class="fas fa-times mr-1"></i> Reset
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="admin-card p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#94a3b8]">Total Transaksi</p>
            <p class="text-2xl font-extrabold text-white mt-2">{{ number_format($stats['total'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#94a3b8]">Menunggu</p>
            <p class="text-2xl font-extrabold text-amber-400 mt-2">{{ number_format($stats['pending'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#94a3b8]">Selesai</p>
            <p class="text-2xl font-extrabold text-green-400 mt-2">{{ number_format($stats['selesai'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#94a3b8]">Total Top Up & Invoice</p>
            <p class="text-xl font-extrabold text-white mt-2">Rp {{ number_format($stats['total_idr'], 0, ',', '.') }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#94a3b8]">Total Convert USD</p>
            <p class="text-xl font-extrabold text-white mt-2">${{ number_format($stats['total_usd'], 2, '.', ',') }}</p>
        </div>
    </div>

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
                        <th>Tanggal</th>
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
                                <span class="badge {{ in_array($transaction->status, ['gagal', 'batal'], true) ? 'badge-red' : 'badge-gray' }}">{{ $transaction->status_label }}</span>
                            </td>
                            <td class="text-[#94a3b8] text-sm whitespace-nowrap">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-[#94a3b8]">Tidak ada data untuk filter ini.</td>
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
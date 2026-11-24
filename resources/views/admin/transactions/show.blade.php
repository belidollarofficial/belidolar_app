<x-admin-layout>
    @section('title', 'Detail Transaksi')

    <div class="max-w-4xl space-y-5">
        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-[#94a3b8] hover:text-white transition no-underline inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Transaksi
        </a>

        @if ($errors->any())
            <div class="admin-card p-4 border-l-4 !border-l-red-500">
                <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Info transaksi --}}
        <div class="admin-card p-6">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-[#334155] pb-4 mb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-white font-mono">{{ $transaction->transaction_code }}</h2>
                    <p class="text-sm text-[#94a3b8] mt-0.5">Dibuat {{ $transaction->created_at->format('d M Y H:i') }}</p>
                </div>
                @php
                    $badge = match ($transaction->status) {
                        'pending' => 'badge-gray',
                        'diproses', 'selesai' => 'badge-green',
                        'gagal', 'batal' => 'badge-red',
                        default => 'badge-gray',
                    };
                @endphp
                <span class="badge {{ $badge }} text-sm">{{ $transaction->status_label }}</span>
            </div>

            <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Nama Pelanggan</dt>
                    <dd class="text-white font-semibold">{{ $transaction->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">No. WhatsApp</dt>
                    <dd class="text-white font-semibold">{{ $transaction->customer_phone }}</dd>
                </div>
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Email / PayPal</dt>
                    <dd class="text-white">{{ $transaction->email ?? '-' }}</dd>
                </div>
                @if ($transaction->paypal_account_name)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Nama Akun PayPal</dt>
                        <dd class="text-white">{{ $transaction->paypal_account_name }}</dd>
                    </div>
                @endif
                @if ($transaction->account_type_label)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Jenis Akun</dt>
                        <dd class="text-white">{{ $transaction->account_type_label }}</dd>
                    </div>
                @endif
                @if ($transaction->fee_estimate)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Estimasi Fee</dt>
                        <dd class="text-white font-semibold text-amber-300">{{ $transaction->fee_estimate }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Layanan</dt>
                    <dd class="text-white">{{ $transaction->service_label }}</dd>
                </div>
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Sumber Transaksi</dt>
                    <dd class="text-white">
                        <span class="badge {{ $transaction->isManual() ? 'badge-green' : 'badge-gray' }}">{{ $transaction->source_label }}</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Nominal</dt>
                    <dd class="text-white font-bold">{{ $transaction->amount_label }}</dd>
                </div>
                @if ($transaction->hasPayableAmount())
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">{{ $transaction->payable_caption }} (IDR)</dt>
                        <dd class="text-white font-bold">{{ $transaction->payable_label }}</dd>
                    </div>
                @endif
                @if ($transaction->bank_name)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Bank Tujuan</dt>
                        <dd class="text-white">{{ $transaction->bank_name }} — {{ $transaction->bank_account_number }} <span class="text-[#94a3b8]">(a.n. {{ $transaction->bank_account_name }})</span></dd>
                    </div>
                @endif
                @if ($transaction->payout_method)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Metode Pencairan</dt>
                        <dd class="text-white">{{ $transaction->payout_type_label }} — {{ $transaction->payout_method }}</dd>
                    </div>
                @endif
                @if ($transaction->payout_account_number)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Nomor Rekening / E-Wallet</dt>
                        <dd class="text-white font-mono">{{ $transaction->payout_account_number }}</dd>
                    </div>
                @endif
                @if ($transaction->merchant_name)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Nama Tempat / Merchant</dt>
                        <dd class="text-white">{{ $transaction->merchant_name }}</dd>
                    </div>
                @endif
                @if ($transaction->merchant_url)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Link / URL Situs Belanja</dt>
                        <dd>
                            <a href="{{ $transaction->merchant_url }}" target="_blank" rel="noopener" class="text-[#22d3ee] hover:underline break-all">
                                {{ $transaction->merchant_url }}
                            </a>
                        </dd>
                    </div>
                @endif
                @if ($transaction->payment_method)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Metode Pembayaran</dt>
                        <dd class="text-white">{{ $transaction->payment_method }}</dd>
                    </div>
                @endif
                @if ($transaction->payment_link)
                    <div>
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Link Pembayaran</dt>
                        <dd class="text-white">
                            <a href="{{ $transaction->payment_link }}" target="_blank" rel="noopener" class="text-[#22d3ee] hover:underline break-all">
                                {{ $transaction->payment_link }}
                            </a>
                        </dd>
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Catatan Pelanggan</dt>
                    <dd class="text-white whitespace-pre-wrap">{{ $transaction->notes ?? '-' }}</dd>
                </div>
                @if ($transaction->admin_note)
                    <div class="sm:col-span-2">
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Catatan Admin</dt>
                        <dd class="text-white whitespace-pre-wrap">{{ $transaction->admin_note }}</dd>
                    </div>
                @endif
                @if ($transaction->notified_at)
                    <div class="sm:col-span-2">
                        <dt class="text-[#94a3b8] text-xs uppercase tracking-wide">Notifikasi WA Terakhir</dt>
                        <dd class="text-[#4ade80] font-semibold">{{ $transaction->notified_at->format('d M Y H:i') }}</dd>
                    </div>
                @endif
            </dl>

            @if ($transaction->proof_url)
                <div class="mt-5">
                    <dt class="text-[#94a3b8] text-xs uppercase tracking-wide mb-2">Bukti Transaksi</dt>
                    @if (Str::contains($transaction->proof_path, ['.jpg', '.jpeg', '.png', '.webp']))
                        <div class="max-w-sm">
                            <a href="{{ $transaction->proof_url }}" target="_blank">
                                <img src="{{ $transaction->proof_url }}" alt="Bukti transaksi" class="rounded-xl border border-[#334155] w-full" />
                            </a>
                        </div>
                    @endif
                    <a href="{{ $transaction->proof_url }}" target="_blank" class="text-sm text-[#818cf8] hover:underline no-underline inline-flex items-center gap-1.5 mt-2">
                        <i class="fas fa-external-link-alt"></i> Buka / Unduh Bukti
                    </a>
                </div>
            @endif
        </div>

        {{-- Aksi: notifikasi ke pelanggan --}}
        <div class="admin-card p-6">
            <h3 class="font-extrabold text-white text-lg mb-4"><i class="fas fa-paper-plane mr-2 text-[#22d3ee]"></i>Tindakan</h3>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="sendNotification()"
                    class="inline-flex items-center gap-2 btn-primary px-5 py-2.5 rounded-xl text-sm font-bold">
                    <i class="fas fa-bell"></i> Kirim Notifikasi WA ke {{ $transaction->customer_phone }}
                </button>
            </div>
            <p class="text-xs text-[#94a3b8] mt-3">
                Klik tombol notifikasi untuk membuka chat WhatsApp ke pelanggan dengan ringkasan status + link bukti transaksi (jika ada).
            </p>
        </div>

        {{-- Update status + upload bukti --}}
        <div class="admin-card p-6">
            <h3 class="font-extrabold text-white text-lg mb-4"><i class="fas fa-edit mr-2 text-[#818cf8]"></i>Update Transaksi</h3>

            <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Status Transaksi</label>
                        <select name="status" class="form-select" required>
                            @foreach (\App\Models\Transaction::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($transaction->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Bukti Transaksi / Transfer / Top Up (opsional)</label>
                        <input type="file" name="proof" accept="image/png,image/jpeg,image/webp" class="form-input file:bg-[#334155] file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3" />
                        <p class="text-xs text-[#94a3b8] mt-1">Format JPG / PNG / WebP, maks 2MB. File lama akan diganti.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Catatan Admin (opsional)</label>
                    <textarea name="admin_note" rows="3" class="form-textarea" placeholder="Contoh: Konfirmasi pembayaran diterima...">{{ old('admin_note', $transaction->admin_note) }}</textarea>
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl text-sm font-semibold no-underline">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function sendNotification() {
            var button = event.target.closest('.btn-primary');
            if (!button) return;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyiapkan...';

            fetch('{{ route('admin.transactions.notify', $transaction) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-bell"></i> Kirim Notifikasi WA';
                if (data.success && data.link) {
                    showToast('success', 'Membuka WhatsApp ke pelanggan...');
                    window.open(data.link, '_blank');
                } else {
                    showToast('error', 'Gagal menyiapkan notifikasi.');
                }
            })
            .catch(function () {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-bell"></i> Kirim Notifikasi WA';
                showToast('error', 'Terjadi kesalahan. Coba lagi.');
            });
        }
    </script>
</x-admin-layout>
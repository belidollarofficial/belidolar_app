<x-admin-layout>
    @section('title', 'Tambah Transaksi')

    <div class="max-w-4xl space-y-5">
        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-[#94a3b8] hover:text-white transition no-underline inline-flex items-center gap-1.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Transaksi
        </a>

        <div>
            <h2 class="text-2xl font-extrabold text-white">Tambah Transaksi Manual</h2>
            <p class="text-sm text-[#94a3b8] mt-1">
                Gunakan form ini untuk mencatat transaksi pelanggan yang dilakukan di luar website, agar riwayat tetap tersimpan.
            </p>
        </div>

        @if ($errors->any())
            <div class="admin-card p-4 border-l-4 !border-l-red-500">
                <ul class="list-disc list-inside text-sm text-red-300 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.transactions.store') }}" class="admin-card p-6 space-y-5" enctype="multipart/form-data">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Pelanggan <span class="text-red-400">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-input" placeholder="Nama lengkap pelanggan" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">No. WhatsApp <span class="text-red-400">*</span></label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="form-input" placeholder="0812xxxxxxx" required />
                    <p class="text-xs text-[#94a3b8] mt-1">Akan dinormalisasi otomatis ke format 62.</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Email / Akun PayPal (opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@example.com" />
                </div>
                <div id="accountTypeGroup">
                    <label class="block text-sm font-semibold text-white mb-1.5">Jenis Akun (Top Up)</label>
                    <select name="account_type" class="form-select">
                        <option value="">— Pilih Jenis Akun —</option>
                        @foreach (\App\Models\Transaction::ACCOUNT_TYPES as $value => $label)
                            <option value="{{ $value }}" @selected(old('account_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Layanan <span class="text-red-400">*</span></label>
                    <select name="service" id="serviceSelect" class="form-select" required onchange="toggleServiceFields()">
                        @foreach ($services as $value => $label)
                            <option value="{{ $value }}" @selected(old('service') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nominal (USD) <span class="text-red-400">*</span></label>
                    <input type="number" step="0.01" min="1" name="amount" value="{{ old('amount') }}" class="form-input" placeholder="Contoh: 50" required />
                    <p class="text-xs text-[#94a3b8] mt-1">Nominal dalam USD (dolar).</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Jumlah Dibayar (IDR, opsional)</label>
                    <input type="number" step="1" min="0" name="payable_amount" value="{{ old('payable_amount') }}" class="form-input" placeholder="Contoh: 915000" />
                    <p class="text-xs text-[#94a3b8] mt-1">Nominal Rupiah yang dibayar pelanggan (Top Up, Invoice &amp; Jasa Bayar Online).</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Estimasi Fee (opsional)</label>
                    <input type="text" name="fee_estimate" value="{{ old('fee_estimate') }}" class="form-input" placeholder="Contoh: $5-$15" />
                    <p class="text-xs text-[#94a3b8] mt-1">Khusus Top Up akun Luar Negeri.</p>
                </div>
            </div>

            <div id="paymentLinkGroup">
                <label class="block text-sm font-semibold text-white mb-1.5">Link Pembayaran / Invoice (opsional)</label>
                <input type="url" name="payment_link" value="{{ old('payment_link') }}" class="form-input" placeholder="https://paypal.com/invoice/..." />
            </div>

            <div id="payoutAccountGroup">
                <label class="block text-sm font-semibold text-white mb-1.5">Nomor Rekening / Nomor E-Wallet (Convert)</label>
                <input type="text" name="payout_account_number" value="{{ old('payout_account_number') }}" class="form-input" placeholder="1234567890 atau 0812xxxxxxx" />
            </div>

            <div id="payOnlineGroup" class="space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Nama Tempat / Merchant Belanja</label>
                        <input type="text" name="merchant_name" value="{{ old('merchant_name') }}" class="form-input" placeholder="Amazon, Steam, eBay..." />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Metode Pembayaran</label>
                        <input type="text" name="payment_method" value="{{ old('payment_method') }}" class="form-input" placeholder="PayPal, Kartu Kredit..." />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Link / URL Situs Belanja</label>
                    <input type="url" name="merchant_url" value="{{ old('merchant_url') }}" class="form-input" placeholder="https://www.amazon.com/..." />
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Status <span class="text-red-400">*</span></label>
                <select name="status" class="form-select" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', 'selesai') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-[#94a3b8] mt-1">Untuk transaksi di luar website, biasanya sudah selesai.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Catatan Pelanggan (opsional)</label>
                <textarea name="notes" rows="2" class="form-textarea" placeholder="Catatan dari pelanggan...">{{ old('notes') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Catatan Admin (opsional)</label>
                <textarea name="admin_note" rows="2" class="form-textarea" placeholder="Contoh: Pembayaran diterima via transfer manual...">{{ old('admin_note') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1.5">Bukti Transaksi (opsional)</label>
                <input type="file" name="proof" accept="image/png,image/jpeg,image/webp" class="form-input file:bg-[#334155] file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3" />
                <p class="text-xs text-[#94a3b8] mt-1">Format JPG / PNG / WebP, maks 2MB.</p>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl text-sm font-bold">
                    <i class="fas fa-save mr-1"></i> Simpan Transaksi
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl text-sm font-semibold no-underline">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function toggleServiceFields() {
            var select = document.getElementById('serviceSelect');
            if (!select) return;
            var service = select.value;

            var toggle = function (id, show) {
                var el = document.getElementById(id);
                if (el) el.style.display = show ? 'block' : 'none';
            };

            toggle('paymentLinkGroup', service === 'invoice');
            toggle('accountTypeGroup', service === 'topup');
            toggle('payoutAccountGroup', service === 'convert_usd');
            toggle('payOnlineGroup', service === 'pay_online');
        }
        toggleServiceFields();
    </script>
</x-admin-layout>

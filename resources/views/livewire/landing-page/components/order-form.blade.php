<!-- ===== FORM ORDER (Livewire) ===== -->
<section id="form-order" class="mb-12 scroll-mt-20">
    <div class="max-w-2xl mx-auto">
        <div class="bg-[#1e293b] rounded-2xl card-shadow p-6 md:p-8">
            <div class="flex items-center justify-between border-b border-[#334155] pb-4">
                <h3 class="text-xl font-extrabold text-white flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-[#4f46e5]"></i> Form Order
                </h3>
                <span class="text-xs font-normal text-gray-500 bg-[#334155] px-3 py-1 rounded-full">
                    Respon cepat via WhatsApp
                </span>
            </div>

            @if ($successCode)
                <div class="mt-6 rounded-xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-5 text-center">
                    <div class="text-4xl mb-2 text-emerald-400"><i class="fas fa-check-circle"></i></div>
                    <p class="text-white font-bold">Order {{ $successCode }} berhasil dibuat!</p>
                    <p class="text-sm text-gray-400 mt-1">Mengarahkan ke WhatsApp admin untuk konfirmasi...</p>
                    <a href="{{ $successUrl }}" target="_blank"
                       class="mt-4 inline-flex items-center gap-2 bg-[#25d366] text-white font-bold px-6 py-3 rounded-xl hover:brightness-110 transition">
                        <i class="fab fa-whatsapp"></i> Lanjut ke WhatsApp
                    </a>
                    <div class="mt-3">
                        <button type="button" wire:click="resetForm"
                                class="text-sm text-cyan-300 hover:underline">Buat order baru</button>
                    </div>
                </div>
            @else
                <form wire:submit="submit" class="space-y-4 mt-5">
                    <!-- Honeypot anti-bot (jangan diisi) -->
                    <input type="text" wire:model="website" tabindex="-1" autocomplete="off"
                           aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;">

                    @error('form')
                        <div class="text-sm text-red-400 bg-red-900/20 border border-red-900/40 rounded-xl px-4 py-3">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-user text-[#4f46e5] mr-1"></i> Nama Lengkap
                        </label>
                        <input type="text" wire:model="name" placeholder="Masukkan nama Anda..."
                                class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                        @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fab fa-whatsapp text-[#4f46e5] mr-1"></i> Nomor WhatsApp
                        </label>
                        <input type="tel" wire:model="phone" placeholder="Contoh: 0812xxxxxxx"
                                class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                        <p class="text-xs text-gray-500 mt-1">Notifikasi status transaksi akan dikirim ke nomor ini.</p>
                        @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-cogs text-[#4f46e5] mr-1"></i> Layanan
                        </label>
                        <select wire:model.live="service"
                                class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition">
                            @foreach (\App\Models\Transaction::SERVICES as $value => $label)
                                <option value="{{ $value }}" @selected($service === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('service') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if (in_array($service, ['topup', 'convert_usd'], true))
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="far fa-envelope text-[#4f46e5] mr-1"></i> Email/Username Paypal
                            </label>
                            <input type="text" wire:model="email" placeholder="email@example.com"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-user-circle text-[#4f46e5] mr-1"></i> Nama Akun Paypal
                            </label>
                            <input type="text" wire:model="paypalAccountName" placeholder="Nama pemilik akun PayPal"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            @error('paypalAccountName') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if ($service === 'topup')
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-globe text-[#4f46e5] mr-1"></i> Jenis Akun
                            </label>
                            <select wire:model.live="accountType"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition">
                                @foreach (\App\Models\Transaction::ACCOUNT_TYPES as $value => $label)
                                    <option value="{{ $value }}" @selected($accountType === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('accountType') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if ($service !== 'wd_akun_180')
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-dollar-sign text-[#4f46e5] mr-1"></i>
                            {{ $service === 'pay_online' ? 'Nominal Pembelian' : 'Nominal' }}
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-2.5 text-gray-400 font-bold">$</span>
                            <input type="number" wire:model.live="amount" placeholder="Minimal $1"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl pl-8 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Nominal dalam USD (dolar).
                        </p>
                        @error('amount') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror

                        @if ($this->rateInfo)
                            <div class="mt-3 text-sm rounded-xl border border-[#22d3ee]/30 bg-[#06b6d4]/10 px-4 py-3 text-cyan-200">
                                @if ($this->rateInfo['type'] === 'range')
                                    <div><strong>Rate:</strong> Rp {{ number_format($this->rateInfo['rate'], 0, ',', '.') }}/USD</div>
                                    <span class="text-cyan-300/70">(untuk nominal ${{ number_format($this->rateInfo['min'], 0, '.', ',') }} - ${{ number_format($this->rateInfo['max'], 0, '.', ',') }})</span>
                                    <div class="mt-1">
                                        Estimasi diterima: <strong class="text-cyan-100">Rp {{ number_format($this->rateInfo['estimated'], 0, ',', '.') }}</strong>
                                    </div>
                                @else
                                    <div><strong>Rate:</strong> Rp {{ number_format($this->rateInfo['rate'], 0, ',', '.') }}/USD</div>
                                    <div class="mt-1">
                                        {{ $service === 'pay_online' ? 'Total Bayar' : 'Yang harus dibayar' }}: <strong class="text-cyan-100">Rp {{ number_format($this->rateInfo['estimated'], 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="mt-1">
                                        Biaya Admin:
                                        @if ($this->rateInfo['admin_fee'] > 0)
                                            <strong class="text-amber-100">Rp {{ number_format($this->rateInfo['admin_fee'], 0, ',', '.') }}</strong>
                                        @else
                                            <strong class="text-emerald-200">Gratis</strong>
                                        @endif
                                    </div>
                                    <div class="text-xs text-cyan-300/70 mt-1">
                                        Bebas biaya admin untuk nominal ≥ ${{ number_format($this->rateInfo['admin_fee_threshold'], 0, ',', '.') }}
                                    </div>
                                    @if ($service === 'topup' && $accountType === 'luar_negeri' && $this->feeEstimate)
                                        <div class="mt-1 text-amber-200">
                                            Estimasi Fee : <strong class="text-amber-100">{{ $this->feeEstimate }}</strong>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @elseif (is_numeric($amount) && (float) $amount > 0)
                            <div class="mt-3 text-sm rounded-xl border border-[#f59e0b]/30 bg-[#f59e0b]/10 px-4 py-3 text-amber-200">
                                Rate untuk nominal ini belum tersedia. Hubungi kami via WhatsApp untuk informasi lebih lanjut.
                            </div>
                        @endif
                    </div>
                    @endif

                    @if ($service === 'convert_usd')
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-white mb-1">
                                    <i class="fas fa-wallet text-[#4f46e5] mr-1"></i> Tipe Pencairan
                                </label>
                                <select wire:model.live="payoutType"
                                        class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition">
                                    @foreach (\App\Models\PayoutMethod::TYPES as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('payoutType') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-white mb-1">
                                    <i class="fas fa-university text-[#4f46e5] mr-1"></i> Bank / E-Wallet
                                </label>
                                <select wire:model.live="payoutMethodId"
                                        class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition">
                                    <option value="">— Pilih —</option>
                                    @foreach ($this->payoutMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                    <option value="other" @selected($payoutMethodId === 'other')>
                                        {{ $payoutType === 'ewallet' ? 'E-Wallet Lainnya' : 'Bank Lainnya' }}
                                    </option>
                                </select>
                                @error('payoutMethodId') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if ($payoutMethodId === 'other')
                            <div>
                                <label class="block text-sm font-semibold text-white mb-1">
                                    <i class="fas fa-pen text-[#4f46e5] mr-1"></i> Nama Bank / E-Wallet Lainnya
                                </label>
                                <input type="text" wire:model="payoutMethodOther" placeholder="{{ $payoutType === 'ewallet' ? 'Contoh: OVO, DANA, ShopeePay' : 'Contoh: Bank Jago, SeaBank' }}"
                                        class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                                @error('payoutMethodOther') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-hashtag text-[#4f46e5] mr-1"></i> Nomor Rekening / Nomor E-Wallet
                            </label>
                            <input type="text" wire:model="payoutAccountNumber" placeholder="Contoh: 1234567890 atau 0812xxxxxxx"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            <p class="text-xs text-gray-500 mt-1">Nomor rekening bank atau e-wallet tujuan pencairan.</p>
                            @error('payoutAccountNumber') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if ($service === 'pay_online')
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-store text-[#4f46e5] mr-1"></i> Nama Tempat / Merchant Belanja
                            </label>
                            <input type="text" wire:model="merchantName" placeholder="Contoh: Amazon, eBay, Steam"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            @error('merchantName') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-link text-[#4f46e5] mr-1"></i> Link / URL Situs Belanja
                            </label>
                            <input type="url" wire:model="merchantUrl" placeholder="https://www.amazon.com/..."
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            @error('merchantUrl') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-credit-card text-[#4f46e5] mr-1"></i> Metode Pembayaran
                            </label>
                            <input type="text" wire:model="paymentMethod" placeholder="Contoh: PayPal, Kartu Kredit, Google Pay"
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            <p class="text-xs text-gray-500 mt-1">Metode pembayaran yang ingin digunakan di situs belanja.</p>
                            @error('paymentMethod') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if ($service === 'invoice')
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">
                                <i class="fas fa-link text-[#4f46e5] mr-1"></i> Link Pembayaran
                            </label>
                            <input type="url" wire:model="paymentLink" placeholder="https://paypal.com/invoice/..."
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition" />
                            <p class="text-xs text-gray-500 mt-1">Tempel link invoice PayPal yang ingin dibayarkan.</p>
                            @error('paymentLink') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">
                            <i class="fas fa-pen text-[#4f46e5] mr-1"></i> Catatan (Opsional)
                        </label>
                        <textarea wire:model="notes" rows="2" placeholder="Masukkan catatan tambahan..."
                                    class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] transition resize-none"></textarea>
                        @error('notes') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @php $operasional = $this->operasionalInfo; @endphp
                    @if (! empty($operasional['hours']) || ! empty($operasional['processing_time']))
                        <div class="rounded-xl border border-[#f59e0b]/30 bg-[#f59e0b]/10 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-clock text-[#fbbf24]"></i>
                                <span class="font-bold text-white text-sm">Jam Operasional</span>
                            </div>
                            @if (! empty($operasional['hours']))
                                <ul class="space-y-1 text-sm text-amber-100/90">
                                    @foreach ($operasional['hours'] as $line)
                                        <li><i class="fas fa-circle text-[6px] align-middle mr-2"></i>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if (! empty($operasional['processing_time']))
                                <div class="mt-2 text-sm text-amber-100/90">
                                    <i class="fas fa-hourglass-half mr-1"></i> Rata - rata pesanan di proses:
                                    <strong class="text-amber-50">{{ $operasional['processing_time'] }}</strong>
                                </div>
                            @endif
                            @if (! empty($operasional['note']))
                                <p class="text-xs text-amber-200/80 mt-2">{{ $operasional['note'] }}</p>
                            @endif
                        </div>
                    @endif

                    <label class="flex items-start gap-2.5 text-sm text-gray-300 leading-relaxed select-none">
                        <input type="checkbox" wire:model="termsAccepted"
                            class="mt-0.5 rounded border-[#334155] bg-[#0f1724] text-[#25d366] focus:ring-[#25d366]" />
                        <span>
                            Saya telah membaca dan menyetujui
                            <a href="#syarat-ketentuan" class="text-[#22d3ee] hover:underline">
                                Syarat dan Ketentuan {{ \App\Models\Transaction::SERVICES[$service] ?? 'layanan' }}
                            </a>
                            BeliDolar.id
                        </span>
                    </label>
                    @error('termsAccepted') <p class="text-xs text-red-400">{{ $message }}</p> @enderror

                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                            class="w-full bg-[#25d366] hover:bg-[#1da851] disabled:opacity-60 text-white font-bold py-3.5 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition">
                        <span wire:loading.remove wire:target="submit"><i class="fab fa-whatsapp"></i> Order via WhatsApp</span>
                        <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                    </button>

                    <p class="text-center text-xs text-gray-500 mt-2">
                        <i class="fas fa-lock mr-1"></i> Transaksi aman & terenkripsi
                    </p>
                </form>
            @endif
        </div>
    </div>
</section>

@script
<script>
    $wire.on('order-created', ({ url }) => {
        if (url) {
            setTimeout(() => window.open(url, '_blank'), 1200);
        }
    });
</script>
@endscript

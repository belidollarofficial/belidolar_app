<x-admin-layout>
    @section('title', 'Pengaturan')

    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-extrabold text-white">Pengaturan Situs</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Atur logo, favicon, nomor WhatsApp, rate layanan, dan data umum lainnya.</p>
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

        @php
            $categoryTitles = [
                'general' => 'Informasi Umum',
                'branding' => 'Logo & Favicon',
                'rate' => 'Rate Layanan',
                'operasional' => 'Jam Operasional',
                'fee' => 'Estimasi Fee Top Up',
                'admin_fee' => 'Biaya Admin',
            ];
        @endphp

        @foreach ($settings as $category => $items)
            <div class="admin-card p-6">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                    <div class="w-10 h-10 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center text-[#fbbf24]">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white">{{ $categoryTitles[$category] ?? ucfirst($category) }}</h3>
                        <p class="text-xs text-[#94a3b8]">Kategori: {{ $category }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="grid sm:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')

                    @foreach ($items as $item)
                        @if ($item->type === 'image')
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-white mb-1.5">{{ $item->label }}</label>
                                <div class="flex flex-wrap items-center gap-4">
                                    <div class="w-20 h-20 rounded-xl border border-[#334155] bg-[#0f1724] overflow-hidden flex items-center justify-center shrink-0">
                                        @if ($item->value)
                                            <img src="{{ \App\Support\Branding::storageUrl($item->value) }}"
                                                alt="{{ $item->label }}" class="w-full h-full object-contain p-1.5" />
                                        @else
                                            <img src="{{ asset('images/belidolar-logo.svg') }}"
                                                alt="{{ $item->label }}" class="w-full h-full object-contain p-1.5 opacity-60" />
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-[240px] space-y-2">
                                        <input type="hidden" name="settings[{{ $category }}][{{ $item->key }}]" value="{{ $item->value }}" />
                                        <input type="file" name="files[{{ $category }}][{{ $item->key }}]"
                                            accept=".png,.jpg,.jpeg,.webp,.svg,.ico"
                                            class="form-input file:bg-[#334155] file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3" />

                                        @if ($item->key === 'logo')
                                            <p class="text-xs text-[#94a3b8]">PNG, JPG, WebP, atau SVG. Disarankan rasio 1:1, maksimal 2 MB.</p>
                                        @else
                                            <p class="text-xs text-[#94a3b8]">PNG, JPG, WebP, SVG, atau ICO. Disarankan 32×32 px, maksimal 1 MB.</p>
                                        @endif

                                        @if ($item->value)
                                            <button type="submit"
                                                name="remove_files[{{ $category }}][{{ $item->key }}]"
                                                value="1"
                                                class="inline-flex items-center gap-2 rounded-lg border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-300 transition hover:bg-red-500/20"
                                                onclick="return confirm('Hapus {{ strtolower($item->label) }} dan kembali ke aset bawaan?')">
                                                <i class="fas fa-rotate-left"></i>
                                                Reset {{ $item->key === 'logo' ? 'Logo' : 'Favicon' }}
                                            </button>
                                        @else
                                            <p class="text-xs text-[#64748b]">Saat ini memakai aset bawaan.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-semibold text-white mb-1.5">{{ $item->label }}</label>
                                @if ($item->type === 'number')
                                    <input type="number" step="any" name="settings[{{ $category }}][{{ $item->key }}]"
                                        value="{{ old('settings.'.$category.'.'.$item->key, $item->value) }}" class="form-input" />
                                @else
                                    <input type="text" name="settings[{{ $category }}][{{ $item->key }}]"
                                        value="{{ old('settings.'.$category.'.'.$item->key, $item->value) }}" class="form-input" />
                                @endif
                            </div>
                        @endif
                    @endforeach

                    <div class="sm:col-span-2 pt-2">
                        <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                            <i class="fas fa-save"></i> Simpan {{ $categoryTitles[$category] ?? 'Pengaturan' }}
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</x-admin-layout>

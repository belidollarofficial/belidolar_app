<x-admin-layout>
    @section('title', 'Konten Landing Page')

    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-extrabold text-white">Konten Landing Page</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Ubah teks yang tampil di landing page. Perubahan langsung terlihat di website. Gunakan tombol <span class="text-[#fbbf24] font-semibold">Reset</span> untuk mengembalikan satu item ke nilai bawaan.</p>
        </div>

        @php
            $sectionTitles = [
                'hero' => 'Hero Banner',
                'stats' => 'Banner Statistik',
                'layanan' => 'Layanan Kami',
                'cara_order' => 'Cara Order',
                'testimoni' => 'Testimoni',
                'footer' => 'Footer',
                'syarat_ketentuan' => 'Syarat & Ketentuan',
            ];
        @endphp

        @foreach ($sections as $section => $items)
            <div class="admin-card p-6">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                    <div class="w-10 h-10 rounded-xl bg-[#4f46e5]/10 flex items-center justify-center text-[#818cf8]">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white">{{ $sectionTitles[$section] ?? ucfirst($section) }}</h3>
                        <p class="text-xs text-[#94a3b8]">Section: {{ $section }}</p>
                    </div>
                </div>

                @if ($section === 'syarat_ketentuan')
                    <livewire:admin.terms-content-editor />
                @else
                    <form id="content-form-{{ $section }}" method="POST" action="{{ route('admin.contents.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach ($items as $item)
                                @php
                                    $defaultValue = \App\Support\ContentDefaults::value($item->section, $item->key);
                                @endphp
                                <div class="{{ in_array($item->type, ['textarea', 'wysiwyg'], true) ? 'sm:col-span-2' : '' }}">
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <label class="block text-sm font-semibold text-white">{{ $item->label }}</label>
                                        @if ($defaultValue !== null)
                                            <button type="submit" form="content-reset-{{ $item->id }}"
                                                class="btn-reset"
                                                title="Kembalikan ke nilai default"
                                                @disabled($item->value === $defaultValue)
                                                onclick="return confirm('Kembalikan kolom ini ke nilai default?')">
                                                <i class="fas fa-rotate-left"></i> Reset
                                            </button>
                                        @endif
                                    </div>
                                    @if ($item->type === 'wysiwyg')
                                        @include('admin.partials.rte', [
                                            'name' => 'contents['.$section.']['.$item->key.']',
                                            'value' => old('contents.'.$section.'.'.$item->key, $item->value),
                                            'id' => 'rte-'.$section.'-'.$item->key,
                                        ])
                                        <p class="text-xs text-[#94a3b8] mt-1">Gunakan toolbar untuk memformat teks. HTML berbahaya dibersihkan otomatis saat disimpan.</p>
                                    @elseif ($item->type === 'textarea')
                                        <textarea name="contents[{{ $section }}][{{ $item->key }}]" rows="3"
                                            class="form-textarea">{{ old('contents.'.$section.'.'.$item->key, $item->value) }}</textarea>
                                    @elseif ($item->type === 'number')
                                        <input type="number" step="any" name="contents[{{ $section }}][{{ $item->key }}]"
                                            value="{{ old('contents.'.$section.'.'.$item->key, $item->value) }}" class="form-input" />
                                    @else
                                        <input type="text" name="contents[{{ $section }}][{{ $item->key }}]"
                                            value="{{ old('contents.'.$section.'.'.$item->key, $item->value) }}" class="form-input" />
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                                <i class="fas fa-save"></i> Simpan {{ $sectionTitles[$section] ?? 'Section' }}
                            </button>
                        </div>
                    </form>

                    {{-- Form reset terpisah per item, dihubungkan lewat atribut form="" agar tidak bersarang. --}}
                    @foreach ($items as $item)
                        <form id="content-reset-{{ $item->id }}" method="POST" action="{{ route('admin.contents.reset', $item) }}" class="hidden">
                            @csrf
                            @method('PUT')
                        </form>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</x-admin-layout>

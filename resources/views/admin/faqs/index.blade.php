<x-admin-layout>
    @section('title', 'Pertanyaan Umum (FAQ)')

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Pertanyaan Umum (FAQ)</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Kelola pertanyaan dan jawaban yang tampil di bagian FAQ halaman utama. Pengunjung dapat mengklik pertanyaan untuk melihat jawabannya.</p>
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
                    <h3 class="font-bold text-white">Tambah Pertanyaan</h3>
                    <p class="text-xs text-[#94a3b8]">Tulis pertanyaan yang sering ditanyakan pelanggan.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Pertanyaan</label>
                    <input type="text" name="question" value="{{ old('question') }}" placeholder="Contoh: Berapa lama proses top up?" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Jawaban</label>
                    <textarea name="answer" rows="3" placeholder="Tulis jawaban lengkap..." class="form-textarea" required>{{ old('answer') }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input" />
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-[#e5e7eb] pb-3">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-[#334155] bg-[#0f1724]" @checked(old('is_active', true)) />
                            Tampilkan di halaman publik
                        </label>
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Pertanyaan
                    </button>
                </div>
            </form>
        </div>

        <div class="admin-card p-6">
            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-[#334155]">
                <div class="w-10 h-10 rounded-xl bg-[#06b6d4]/10 flex items-center justify-center text-[#22d3ee]">
                    <i class="fas fa-circle-question"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">Daftar Pertanyaan ({{ $faqs->count() }})</h3>
                </div>
            </div>

            @if ($faqs->isEmpty())
                <p class="text-sm text-[#94a3b8] text-center py-8">Belum ada pertanyaan. Tambahkan di atas.</p>
            @else
                <div class="space-y-3">
                    @foreach ($faqs as $faq)
                        <div class="border border-[#334155] rounded-xl p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-semibold text-white">{{ $faq->question }}</p>
                                    <span class="badge {{ $faq->is_active ? 'badge-green' : 'badge-gray' }}">
                                        {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="text-xs text-[#94a3b8]">Urutan: {{ $faq->sort_order }}</span>
                                </div>
                                <p class="text-sm text-[#94a3b8] mt-1 whitespace-pre-line">{{ $faq->answer }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-sm text-[#22d3ee] hover:underline">
                                    <i class="fas fa-pen mr-1"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-400 hover:underline">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

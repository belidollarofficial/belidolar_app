<x-admin-layout>
    @section('title', 'Edit Pertanyaan Umum')

    <div class="max-w-3xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Edit Pertanyaan Umum</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Ubah pertanyaan, jawaban, atau status tampil.</p>
            </div>
            <a href="{{ route('admin.faqs.index') }}" class="text-sm text-[#22d3ee] hover:underline">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
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
            <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Pertanyaan</label>
                    <input type="text" name="question" value="{{ old('question', $faq->question) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Jawaban</label>
                    <textarea name="answer" rows="6" class="form-textarea" required>{{ old('answer', $faq->answer) }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1.5">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" class="form-input" />
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-[#e5e7eb] pb-3">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-[#334155] bg-[#0f1724]" @checked(old('is_active', $faq->is_active)) />
                            Tampilkan di halaman publik
                        </label>
                    </div>
                </div>

                <div class="pt-1 flex items-center gap-3">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn-ghost inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

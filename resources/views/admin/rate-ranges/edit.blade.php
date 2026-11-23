<x-admin-layout>
    @section('title', 'Edit Rate Range')

    <div class="max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Edit Rentang Rate</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Ubah rentang nominal atau rate Rupiah per USD.</p>
            </div>
            <a href="{{ route('admin.rate-ranges.index') }}" class="text-sm text-[#22d3ee] hover:underline">
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
            <form method="POST" action="{{ route('admin.rate-ranges.update', $range) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nominal Min (USD)</label>
                    <input type="number" name="min_amount" step="0.01" min="0" value="{{ old('min_amount', $range->min_amount) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nominal Max (USD)</label>
                    <input type="number" name="max_amount" step="0.01" min="0" value="{{ old('max_amount', $range->max_amount) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Rate (Rp/USD)</label>
                    <input type="number" name="rate" step="0.01" min="0" value="{{ old('rate', $range->rate) }}" class="form-input" required />
                </div>

                <div class="pt-1 flex items-center gap-3">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.rate-ranges.index') }}" class="btn-ghost inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
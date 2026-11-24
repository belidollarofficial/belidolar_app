<x-admin-layout>
    @section('title', 'Edit Rekening Bank')

    <div class="max-w-2xl space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Edit Rekening Bank</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Ubah data rekening tujuan transfer.</p>
            </div>
            <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-[#22d3ee] hover:underline">
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
            <form method="POST" action="{{ route('admin.bank-accounts.update', $account) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nama Penerima</label>
                    <input type="text" name="account_name" value="{{ old('account_name', $account->account_name) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Nomor Rekening</label>
                    <input type="text" name="account_number" value="{{ old('account_number', $account->account_number) }}" class="form-input" required />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white mb-1.5">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $account->sort_order) }}" min="0" class="form-input" />
                </div>

                <div class="pt-1 flex items-center gap-3">
                    <button type="submit" class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.bank-accounts.index') }}" class="btn-ghost inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

<div class="space-y-5">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-extrabold text-white">Manajemen Testimoni</h2>
            <p class="text-sm text-[#94a3b8] mt-1">Kelola ulasan pelanggan. Ulasan berstatus "Menunggu" muncul setelah disetujui.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.testimonials.index') }}" class="btn-ghost px-4 py-2 rounded-xl text-sm font-semibold no-underline {{ $status === '' ? 'ring-2 ring-[#4f46e5]' : '' }}">Semua</a>
            <a href="{{ route('admin.testimonials.index', ['status' => 'pending']) }}" class="btn-ghost px-4 py-2 rounded-xl text-sm font-semibold no-underline {{ $status === 'pending' ? 'ring-2 ring-[#4f46e5]' : '' }}">
                <i class="fas fa-clock mr-1"></i> Menunggu
            </a>
            <a href="{{ route('admin.testimonials.index', ['status' => 'approved']) }}" class="btn-ghost px-4 py-2 rounded-xl text-sm font-semibold no-underline {{ $status === 'approved' ? 'ring-2 ring-[#4f46e5]' : '' }}">
                <i class="fas fa-check mr-1"></i> Tayang
            </a>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr(trim($testimonial->name), 0, 1) ?: 'A') }}
                                    </div>
                                    <span class="font-semibold text-white">{{ $testimonial->name }}</span>
                                </div>
                            </td>
                            <td class="text-yellow-400 text-sm whitespace-nowrap">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $testimonial->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </td>
                            <td class="text-[#94a3b8] max-w-xs">
                                <p class="line-clamp-2">{{ $testimonial->message }}</p>
                            </td>
                            <td>
                                @if ($testimonial->status === 'pending')
                                    <span class="badge bg-amber-500/15 text-amber-400">Menunggu</span>
                                @else
                                    <span class="badge badge-green">Tayang</span>
                                @endif
                            </td>
                            <td class="text-[#94a3b8] text-sm whitespace-nowrap">{{ $testimonial->created_at->format('d M Y H:i') }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    @if ($testimonial->status === 'pending')
                                        <button type="button" wire:click="approve({{ $testimonial->id }})"
                                                class="btn-ghost px-3 py-2 rounded-lg text-xs font-semibold" title="Setujui tampil">
                                            <i class="fas fa-check mr-1"></i>Setujui
                                        </button>
                                    @else
                                        <button type="button" wire:click="hold({{ $testimonial->id }})"
                                                class="btn-ghost px-3 py-2 rounded-lg text-xs font-semibold" title="Tarik dari halaman">
                                            <i class="fas fa-eye-slash mr-1"></i>Tarik
                                        </button>
                                    @endif
                                    <button type="button" wire:click="delete({{ $testimonial->id }})"
                                            wire:confirm="Hapus testimoni dari {{ $testimonial->name }}?"
                                            class="w-8 h-8 rounded-lg inline-flex items-center justify-center bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-[#94a3b8]">
                                {{ $status !== '' ? 'Tidak ada testimoni dengan status ini.' : 'Belum ada testimoni.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end">
        {{ $testimonials->links() }}
    </div>
</div>
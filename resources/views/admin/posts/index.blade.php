<x-admin-layout>
    @section('title', 'Blog / Artikel')

    <div class="space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-extrabold text-white">Blog / Artikel</h2>
                <p class="text-sm text-[#94a3b8] mt-1">Total {{ $posts->total() }} artikel. Kelola konten blog yang tampil di website.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold no-underline inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Tulis Artikel
            </a>
        </div>

        <form method="GET" action="{{ route('admin.posts.index') }}" class="admin-card p-4">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul atau slug..."
                    class="form-input flex-1 min-w-[200px]" />
                <button type="submit" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold"><i class="fas fa-search mr-1"></i> Cari</button>
                @if ($search !== '')
                    <a href="{{ route('admin.posts.index') }}" class="btn-ghost px-5 py-2.5 rounded-xl text-sm font-semibold no-underline">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>Artikel</th>
                            <th>Status</th>
                            <th>Tanggal Publikasi</th>
                            <th>Dibuat</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gradient-to-br from-[#4f46e5] to-[#06b6d4] shrink-0 flex items-center justify-center text-white">
                                            @if ($post->cover_url)
                                                <img src="{{ $post->cover_url }}" alt="" class="w-full h-full object-cover" />
                                            @else
                                                <i class="fas fa-newspaper"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-white truncate max-w-md">{{ $post->title }}</p>
                                            <p class="text-xs text-[#94a3b8] font-mono truncate max-w-md">/blog/{{ $post->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $post->is_published ? 'badge-green' : 'badge-gray' }}">
                                        {{ $post->is_published ? 'Dipublikasikan' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="text-[#94a3b8] text-sm">
                                    {{ $post->published_at ? $post->published_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="text-[#94a3b8] text-sm">{{ $post->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="inline-flex gap-2">
                                        @if ($post->is_published)
                                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn-ghost px-3 py-2 rounded-lg inline-flex items-center gap-1.5 no-underline text-sm font-semibold" title="Lihat">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost px-4 py-2 rounded-lg inline-flex items-center gap-1.5 no-underline text-sm font-semibold" title="Edit">
                                            <i class="fas fa-pen text-sm"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus artikel {{ $post->title }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-lg inline-flex items-center justify-center bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition" title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-[#94a3b8]">Belum ada artikel. Klik "Tulis Artikel" untuk membuat yang pertama.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end">
            {{ $posts->links() }}
        </div>
    </div>
</x-admin-layout>

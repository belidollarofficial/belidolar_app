<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $posts = Post::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['slug'] = Post::generateUniqueSlug($data['slug'] ?: $data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $this->resolvePublishedAt($request, $data['is_published']);

        if ($request->hasFile('cover')) {
            $data['cover_image'] = $request->file('cover')->store('posts', 'public');
        }

        unset($data['cover']);

        $post = Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel "'.$post->title.'" berhasil ditambahkan.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['slug'] = Post::generateUniqueSlug($data['slug'] ?: $data['title'], $post->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $this->resolvePublishedAt($request, $data['is_published'], $post);

        if ($request->hasFile('cover')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $data['cover_image'] = $request->file('cover')->store('posts', 'public');
        }

        unset($data['cover']);

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel "'.$post->title.'" berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    /**
     * @return array{title: string, slug: ?string, excerpt: ?string, content: string, is_published: bool, published_at: mixed, cover?: mixed}
     */
    protected function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:200000'],
            'published_at' => ['nullable', 'date'],
            'cover' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (trim(strip_tags($data['content'])) === '' && ! str_contains($data['content'], '<img')) {
            throw ValidationException::withMessages([
                'content' => 'Isi artikel wajib diisi.',
            ]);
        }

        return $data + ['slug' => null, 'excerpt' => null, 'published_at' => null];
    }

    protected function resolvePublishedAt(Request $request, bool $isPublished, ?Post $post = null): mixed
    {
        if (! $isPublished) {
            return $post?->published_at;
        }

        $value = $request->input('published_at');

        return filled($value) ? $value : ($post?->published_at ?? now());
    }
}

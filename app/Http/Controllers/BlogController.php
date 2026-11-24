<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResponse;

class BlogController extends Controller
{
    public function index(): ViewResponse
    {
        $posts = Post::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9);

        return view('pages.blog.index', compact('posts'));
    }

    public function show(string $slug): ViewResponse
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        View::share([
            'seoTitle' => $post->title,
            'seoDescription' => $post->summary,
            'seoImage' => $post->cover_url,
            'seoType' => 'article',
        ]);

        return view('pages.blog.show', compact('post'));
    }
}

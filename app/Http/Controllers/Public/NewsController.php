<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])
            ->published()
            ->latest();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = Category::withCount(['posts' => fn ($q) => $q->published()])->get();

        return view('public.news.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['category', 'author', 'images'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementViews();

        $related = Post::with('category')
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest()
            ->take(3)
            ->get();

        return view('public.news.show', compact('post', 'related'));
    }
}

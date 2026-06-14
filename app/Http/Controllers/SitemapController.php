<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $posts      = Post::published()->latest()->get(['slug', 'updated_at']);
        $categories = Category::all(['slug', 'updated_at']);

        $content = view('sitemap', compact('posts', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}

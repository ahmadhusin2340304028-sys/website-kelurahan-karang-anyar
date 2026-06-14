<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Umkm;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q', '');

        if (empty($keyword) || strlen($keyword) < 3) {
            return view('public.search.index', [
                'keyword' => $keyword,
                'posts'   => collect(),
                'umkms'   => collect(),
                'total'   => 0,
            ]);
        }

        $posts = Post::with(['category', 'author'])
            ->published()
            ->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('excerpt', 'like', "%{$keyword}%")
                  ->orWhere('body', 'like', "%{$keyword}%")
                  ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$keyword}%"));
            })
            ->latest()
            ->take(10)
            ->get();

        $umkms = Umkm::approved()
            ->where(function ($q) use ($keyword) {
                $q->where('business_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('business_category', 'like', "%{$keyword}%");
            })
            ->take(10)
            ->get();

        return view('public.search.index', [
            'keyword' => $keyword,
            'posts'   => $posts,
            'umkms'   => $umkms,
            'total'   => $posts->count() + $umkms->count(),
        ]);
    }
}

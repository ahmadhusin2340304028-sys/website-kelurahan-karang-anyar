<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Official;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Umkm;

class HomeController extends Controller
{
    public function index()
    {
        $latestPosts = Post::with(['category', 'author'])
            ->published()
            ->latest()
            ->take(6)
            ->get();

        $announcements = Announcement::active()
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get();

        $galleries = Gallery::active()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $lurah = Official::active()
            ->where('position_level', 'lurah')
            ->first();

        $approvedUmkms = Umkm::approved()
            ->with('images')
            ->latest()
            ->take(6)
            ->get();

        return view('public.home.index', compact(
            'latestPosts', 'announcements', 'galleries', 'lurah', 'approvedUmkms'
        ));
    }
}

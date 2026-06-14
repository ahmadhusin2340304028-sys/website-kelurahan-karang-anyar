<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Announcement, Category, Gallery, Official, Post, Umkm};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts'             => Post::count(),
            'published_posts'   => Post::where('status', 'published')->count(),
            'categories'        => Category::count(),
            'officials'         => Official::where('is_active', true)->count(),
            'galleries'         => Gallery::count(),
            'announcements'     => Announcement::where('is_active', true)->count(),
            'umkms_total'       => Umkm::count(),
            'umkms_pending'     => Umkm::pending()->count(),
            'umkms_approved'    => Umkm::approved()->count(),
        ];

        $recentPosts  = Post::with('category')->latest()->take(5)->get();
        $pendingUmkms = Umkm::pending()->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentPosts', 'pendingUmkms'));
    }
}

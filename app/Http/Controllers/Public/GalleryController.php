<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::active()
            ->orderBy('created_at', 'desc')
            ->paginate(16);

        return view('public.gallery.index', compact('galleries'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Models\{Category, Post, PostImage};
use App\Services\{ImageService, SlugService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(
        private ImageService $imageService,
        private SlugService  $slugService,
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $posts      = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        $data               = $request->validated();
        $data['slug']       = $this->slugService->generate($request->title, Post::class);
        $data['user_id']    = Auth::id();
        $data['published_at'] = $request->status === 'published'
            ? ($request->published_at ?? now())
            : $request->published_at;
        $data['body'] = $this->imageService->normalizeHtmlImages($data['body'], 'posts/body', [
            'max_width' => 1200,
            'max_height' => 900,
            'quality' => 82,
            'format' => 'webp',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->imageService->upload($request->file('thumbnail'), 'posts/thumbnails', [
                'width' => 800,
                'height' => 500,
                'mode' => 'cover',
                'quality' => 82,
                'format' => 'webp',
            ]);
        }
        unset($data['images']);

        $post = Post::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $this->imageService->upload($image, 'posts/images', [
                    'max_width' => 1280,
                    'max_height' => 960,
                    'quality' => 82,
                    'format' => 'webp',
                ]);
                PostImage::create(['post_id' => $post->id, 'image' => $path, 'sort_order' => $index]);
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $post->load('images');
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['slug'] = $this->slugService->generate($request->title, Post::class, $post->id);
        $data['published_at'] = $request->status === 'published'
            ? ($request->published_at ?? $post->published_at ?? now())
            : $request->published_at;
        $data['body'] = $this->imageService->normalizeHtmlImages($data['body'], 'posts/body', [
            'max_width' => 1200,
            'max_height' => 900,
            'quality' => 82,
            'format' => 'webp',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->imageService->replace($post->thumbnail, $request->file('thumbnail'), 'posts/thumbnails', [
                'width' => 800,
                'height' => 500,
                'mode' => 'cover',
                'quality' => 82,
                'format' => 'webp',
            ]);
        }
        unset($data['images']);

        $post->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $this->imageService->upload($image, 'posts/images', [
                    'max_width' => 1280,
                    'max_height' => 960,
                    'quality' => 82,
                    'format' => 'webp',
                ]);
                PostImage::create(['post_id' => $post->id, 'image' => $path, 'sort_order' => $post->images()->count() + $index]);
            }
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $this->imageService->delete($post->thumbnail);
        foreach ($post->images as $img) {
            $this->imageService->delete($img->image);
        }
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function destroyImage(PostImage $image)
    {
        $this->imageService->delete($image->image);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function uploadBodyImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $path = $this->imageService->upload($request->file('image'), 'posts/body', [
            'max_width' => 1200,
            'max_height' => 900,
            'quality' => 82,
            'format' => 'webp',
        ]);

        return response()->json([
            'url' => Storage::url($path),
            'path' => $path,
        ]);
    }
}

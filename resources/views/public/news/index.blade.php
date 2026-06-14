@extends('layouts.public')

@section('title', 'Berita — ' . \App\Models\Setting::get('kelurahan_name'))
@section('meta_description', 'Kumpulan berita dan informasi terkini dari Kelurahan Karang Anyar')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-gov">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Berita</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                <h1 class="section-title h3 mb-4">Berita & Informasi</h1>

                @if($posts->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-newspaper fs-1 d-block mb-3"></i>
                        Belum ada berita tersedia.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($posts as $post)
                        <div class="col-md-6">
                            <div class="card card-news h-100">
                                <a href="{{ route('news.show', $post->slug) }}">
                                    <img src="{{ $post->thumbnail_url }}"
                                         class="card-img-top"
                                         alt="{{ $post->title }}"
                                         loading="lazy">
                                </a>
                                <div class="card-body">
                                    @if($post->category)
                                        <a href="{{ route('news.index', ['category' => $post->category->slug]) }}"
                                           class="badge badge-category text-decoration-none mb-2">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                    <h6 class="card-title fw-bold">
                                        <a href="{{ route('news.show', $post->slug) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($post->title, 75) }}
                                        </a>
                                    </h6>
                                    @if($post->excerpt)
                                        <p class="text-muted small">{{ Str::limit($post->excerpt, 100) }}</p>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent d-flex justify-content-between small text-muted">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                                    <span><i class="bi bi-person me-1"></i>{{ $post->author?->name }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Search --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Cari Berita</h6>
                        <form action="{{ route('search') }}" method="GET" class="d-flex search-form">
                            <input type="search" name="q" class="form-control"
                                   placeholder="Kata kunci..." value="{{ request('q') }}">
                            <button type="submit" class="btn"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Kategori</h6>
                        <a href="{{ route('news.index') }}"
                           class="d-block py-1 text-decoration-none {{ !request('category') ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-grid me-1"></i> Semua Berita
                            <span class="badge bg-secondary float-end">{{ $categories->sum('posts_count') }}</span>
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('news.index', ['category' => $cat->slug]) }}"
                           class="d-block py-1 text-decoration-none {{ request('category') === $cat->slug ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-tag me-1"></i> {{ $cat->name }}
                            <span class="badge bg-secondary float-end">{{ $cat->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('layouts.public')

@section('title', 'Hasil Pencarian: ' . $keyword . ' — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Pencarian</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-6">
                <form action="{{ route('search') }}" method="GET" class="d-flex search-form">
                    <input type="search" name="q" class="form-control form-control-lg"
                           placeholder="Cari berita, UMKM..." value="{{ $keyword }}">
                    <button type="submit" class="btn btn-lg px-4"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        @if(strlen($keyword) > 0 && strlen($keyword) < 3)
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Kata kunci minimal 3 karakter.
            </div>
        @elseif(strlen($keyword) >= 3)
            <p class="text-muted text-center mb-4">
                Ditemukan <strong>{{ $total }} hasil</strong> untuk kata kunci
                "<strong>{{ $keyword }}</strong>"
            </p>

            {{-- Berita --}}
            @if($posts->isNotEmpty())
            <h5 class="section-title mb-3">Berita ({{ $posts->count() }})</h5>
            <div class="row g-3 mb-5">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-news h-100">
                        <a href="{{ route('news.show', $post->slug) }}">
                            <img src="{{ $post->thumbnail_url }}" class="card-img-top"
                                 alt="{{ $post->title }}" style="height:160px; object-fit:cover;" loading="lazy">
                        </a>
                        <div class="card-body">
                            @if($post->category)
                                <span class="badge badge-category mb-1">{{ $post->category->name }}</span>
                            @endif
                            <h6 class="fw-bold">
                                <a href="{{ route('news.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ $post->published_at?->translatedFormat('d M Y') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- UMKM --}}
            @if($umkms->isNotEmpty())
            <h5 class="section-title mb-3">UMKM ({{ $umkms->count() }})</h5>
            <div class="row g-3">
                @foreach($umkms as $umkm)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $umkm->business_photo_url }}" class="card-img-top"
                             alt="{{ $umkm->business_name }}" style="height:160px; object-fit:cover;" loading="lazy">
                        <div class="card-body">
                            <span class="badge bg-success-subtle text-success small mb-1">{{ $umkm->business_category }}</span>
                            <h6 class="fw-bold mb-1">{{ $umkm->business_name }}</h6>
                            <p class="small text-muted">{{ Str::limit($umkm->description, 80) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($total === 0)
            <div class="text-center py-5 text-muted">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                Tidak ada hasil yang ditemukan untuk "<strong>{{ $keyword }}</strong>".
                <div class="mt-3">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm me-2">Lihat Semua Berita</a>
                    <a href="{{ route('umkm.index') }}" class="btn btn-outline-success btn-sm">Lihat Semua UMKM</a>
                </div>
            </div>
            @endif
        @endif
    </div>
</section>
@endsection

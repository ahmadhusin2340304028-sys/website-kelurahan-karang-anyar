@extends('layouts.public')

@section('title', ($post->meta_title ?: $post->title) . ' — ' . \App\Models\Setting::get('kelurahan_name'))
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('og_title', $post->title)
@section('og_description', $post->excerpt)
@section('og_image', $post->thumbnail_url)

@push('styles')
<style>
    .news-article-title {
        line-height: 1.25;
    }

    .article-hero-image {
        width: 100%;
        aspect-ratio: 16 / 9;
        max-height: 450px;
        object-fit: cover;
    }

    .post-body {
        color: #2b2f33;
        font-size: 1.04rem;
        line-height: 1.85;
        overflow-wrap: anywhere;
    }

    .post-body p {
        margin-bottom: 1rem;
        text-align: justify;
    }

    .post-body h2,
    .post-body h3,
    .post-body h4 {
        line-height: 1.35;
        margin: 1.75rem 0 .85rem;
        font-weight: 700;
    }

    .post-body ul,
    .post-body ol {
        margin: 0 0 1rem 1.25rem;
        padding-left: 1rem;
    }

    .post-body li {
        margin-bottom: .35rem;
    }

    .post-body blockquote {
        border-left: 4px solid var(--gov-blue);
        margin: 1.25rem 0;
        padding: .75rem 1rem;
        background: var(--gov-blue-light);
        color: #32465a;
    }

    .post-body img,
    .post-body video,
    .post-body iframe {
        display: block;
        max-width: 100% !important;
        height: auto !important;
        margin: 1.25rem auto;
    }

    .post-body img {
        width: auto !important;
        max-height: 640px;
        object-fit: contain;
        border-radius: 8px;
    }

    .post-body .ql-align-center { text-align: center; }
    .post-body .ql-align-right { text-align: right; }
    .post-body .ql-align-justify { text-align: justify; }
    .post-body .ql-indent-1 { margin-left: 1.5rem; }
    .post-body .ql-indent-2 { margin-left: 3rem; }
    .post-body .ql-indent-3 { margin-left: 4.5rem; }

    @media (max-width: 768px) {
        .post-body {
            font-size: 1rem;
            line-height: 1.75;
        }

        .post-body p {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')

<div class="breadcrumb-gov">
    <div class="container">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Berita</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <article>
                    {{-- Category & Meta --}}
                    <div class="mb-3">
                        @if($post->category)
                            <a href="{{ route('news.index', ['category' => $post->category->slug]) }}"
                               class="badge badge-category text-decoration-none me-2">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        <small class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i>{{ $post->published_at?->translatedFormat('d F Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-person me-1"></i>{{ $post->author?->name }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-eye me-1"></i>{{ number_format($post->views) }} dibaca
                            &nbsp;·&nbsp;
                            <i class="bi bi-clock me-1"></i>{{ $post->reading_time }} menit baca
                        </small>
                    </div>

                    <h1 class="h2 fw-bold mb-4 news-article-title">{{ $post->title }}</h1>

                    {{-- Thumbnail --}}
                    <img src="{{ $post->thumbnail_url }}"
                         alt="{{ $post->title }}"
                         class="img-fluid rounded mb-4 article-hero-image"
                         loading="lazy">

                    {{-- Body --}}
                    <div class="post-body">
                        {!! $post->body !!}
                    </div>

                    {{-- Gallery Images --}}
                    @if($post->images->isNotEmpty())
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Galeri Foto</h6>
                        <div class="row g-2">
                            @foreach($post->images as $img)
                            <div class="col-6 col-md-4">
                                <a href="{{ $img->image_url }}" data-bs-toggle="modal" data-bs-target="#imgModal"
                                   data-img="{{ $img->image_url }}" data-caption="{{ $img->caption }}">
                                    <img src="{{ $img->image_url }}"
                                         alt="{{ $img->caption }}"
                                         class="img-fluid rounded"
                                         style="height:140px; width:100%; object-fit:cover;"
                                         loading="lazy">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Share --}}
                    <div class="mt-4 pt-4 border-top">
                        <span class="fw-semibold me-3">Bagikan:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank" class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                           target="_blank" class="btn btn-sm btn-outline-success me-2">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </article>

                {{-- Related --}}
                @if($related->isNotEmpty())
                <div class="mt-5">
                    <h5 class="section-title mb-4">Berita Terkait</h5>
                    <div class="row g-3">
                        @foreach($related as $r)
                        <div class="col-md-4">
                            <div class="card card-news h-100">
                                <a href="{{ route('news.show', $r->slug) }}">
                                    <img src="{{ $r->thumbnail_url }}" class="card-img-top" alt="{{ $r->title }}"
                                         style="height:140px; object-fit:cover;" loading="lazy">
                                </a>
                                <div class="card-body p-2">
                                    <p class="small fw-semibold mb-1">
                                        <a href="{{ route('news.show', $r->slug) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($r->title, 60) }}
                                        </a>
                                    </p>
                                    <small class="text-muted">{{ $r->published_at?->translatedFormat('d M Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Kategori</h6>
                        <a href="{{ route('news.index') }}" class="d-block py-1 text-dark text-decoration-none">
                            <i class="bi bi-grid me-1"></i> Semua Berita
                        </a>
                        @foreach(\App\Models\Category::withCount('posts')->get() as $cat)
                        <a href="{{ route('news.index', ['category' => $cat->slug]) }}"
                           class="d-block py-1 text-dark text-decoration-none {{ request('category') === $cat->slug ? 'fw-bold text-primary' : '' }}">
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

{{-- Image Modal --}}
<div class="modal fade" id="imgModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <small class="modal-title" id="imgCaption"></small>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="imgSrc" src="" class="w-100" alt="">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('imgModal')?.addEventListener('show.bs.modal', function(e) {
    document.getElementById('imgSrc').src = e.relatedTarget.dataset.img;
    document.getElementById('imgCaption').textContent = e.relatedTarget.dataset.caption || '';
});
</script>
@endpush

@endsection

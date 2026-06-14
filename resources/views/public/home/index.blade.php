@extends('layouts.public')

@section('title', \App\Models\Setting::get('site_name', 'Kelurahan Karang Anyar') . ' — Website Resmi')

@section('content')

{{-- ══ HERO ══ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="text-warning fw-semibold mb-2" style="font-size:0.9rem; letter-spacing:1px;">
                    WEBSITE RESMI
                </p>
                <h1 class="fw-bold mb-3" style="font-size: clamp(1.6rem, 4vw, 2.4rem);">
                    {{ \App\Models\Setting::get('kelurahan_name', 'Kelurahan Karang Anyar') }}
                </h1>
                <p class="mb-4 text-white-75" style="font-size:1.05rem;">
                    {{ \App\Models\Setting::get('site_tagline', 'Melayani masyarakat dengan sepenuh hati untuk kemajuan bersama.') }}
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('news.index') }}" class="btn btn-warning text-dark fw-semibold px-4">
                        <i class="bi bi-newspaper me-2"></i>Berita Terbaru
                    </a>
                    <a href="{{ route('profile.contact') }}" class="btn btn-outline-light px-4">
                        <i class="bi bi-telephone me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <img src="{{ asset('images/hero-illustration.png') }}"
                     alt="Ilustrasi Kelurahan"
                     class="img-fluid"
                     style="max-height: 300px; opacity: 0.9;"
                     onerror="this.src='https://via.placeholder.com/400x300/1a4f8a/ffffff?text=Kelurahan+Karang+Anyar'">
            </div>
        </div>
    </div>
</section>

{{-- ══ STATS BAR ══ --}}
<section class="py-3 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-people-fill text-primary fs-4"></i>
                    <div class="text-start">
                        <div class="fw-bold">{{ \App\Models\Setting::get('jumlah_penduduk', '—') }}</div>
                        <div class="text-muted small">Penduduk</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-house-fill text-success fs-4"></i>
                    <div class="text-start">
                        <div class="fw-bold">{{ \App\Models\Setting::get('jumlah_kk', '—') }}</div>
                        <div class="text-muted small">Kepala Keluarga</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-diagram-2-fill text-warning fs-4"></i>
                    <div class="text-start">
                        <div class="fw-bold">{{ \App\Models\Setting::get('jumlah_rw', '—') }} RW</div>
                        <div class="text-muted small">Rukun Warga</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-grid-fill text-danger fs-4"></i>
                    <div class="text-start">
                        <div class="fw-bold">{{ \App\Models\Setting::get('jumlah_rt', '—') }} RT</div>
                        <div class="text-muted small">Rukun Tetangga</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ SAMBUTAN LURAH ══ --}}
@if($lurah || \App\Models\Setting::get('greeting_lurah'))
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-4">
            @if($lurah)
            <div class="col-lg-3 text-center">
                <img src="{{ $lurah->photo_url }}"
                     alt="{{ $lurah->name }}"
                     class="img-fluid rounded-circle border border-3 border-primary shadow"
                     style="width:160px; height:160px; object-fit:cover;"
                     loading="lazy">
                <h6 class="mt-3 mb-0 fw-bold">{{ $lurah->name }}</h6>
                <small class="text-muted">{{ $lurah->position }}</small>
            </div>
            @endif
            <div class="col-lg-9">
                <h2 class="section-title mb-4">Sambutan Lurah</h2>
                <div class="text-muted lh-lg" style="text-align: justify;">
                    {!! nl2br(e(\App\Models\Setting::get('greeting_lurah', 'Selamat datang di website resmi Kelurahan Karang Anyar. Kami berkomitmen untuk memberikan pelayanan terbaik bagi seluruh warga.'))) !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ══ BERITA TERBARU ══ --}}
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-title">Berita Terbaru</h2>
                <p class="text-muted small mt-2">Informasi dan kegiatan terkini Kelurahan Karang Anyar</p>
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm">
                Semua Berita <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($latestPosts->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-newspaper fs-1"></i>
                <p class="mt-2">Belum ada berita tersedia.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($latestPosts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-news h-100">
                        <a href="{{ route('news.show', $post->slug) }}">
                            <img src="{{ $post->thumbnail_url }}"
                                 class="card-img-top"
                                 alt="{{ $post->title }}"
                                 loading="lazy">
                        </a>
                        <div class="card-body">
                            @if($post->category)
                                <span class="badge badge-category mb-2">{{ $post->category->name }}</span>
                            @endif
                            <h6 class="card-title fw-bold">
                                <a href="{{ route('news.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            @if($post->excerpt)
                                <p class="card-text text-muted small">{{ Str::limit($post->excerpt, 100) }}</p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $post->published_at?->translatedFormat('d M Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-eye me-1"></i>{{ number_format($post->views) }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ══ GALERI ══ --}}
@if($galleries->isNotEmpty())
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-title">Galeri Kegiatan</h2>
                <p class="text-muted small mt-2">Dokumentasi kegiatan kelurahan</p>
            </div>
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-primary btn-sm">
                Semua Foto <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($galleries as $photo)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#galleryModal"
                     data-img="{{ $photo->image_url }}" data-title="{{ $photo->title }}">
                    <img src="{{ $photo->image_url }}"
                         alt="{{ $photo->title }}"
                         loading="lazy"
                         class="w-100">
                    <div class="p-2 bg-white small text-truncate">{{ $photo->title }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Gallery Modal --}}
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="galleryModalTitle">-</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="galleryModalImg" src="" alt="" class="w-100">
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('galleryModal')?.addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('galleryModalImg').src = btn.dataset.img;
    document.getElementById('galleryModalTitle').textContent = btn.dataset.title;
});
</script>
@endpush
@endif

{{-- ══ UMKM ══ --}}
@if($approvedUmkms->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-title">UMKM Kelurahan</h2>
                <p class="text-muted small mt-2">Produk dan jasa unggulan warga Karang Anyar</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('umkm.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Daftarkan UMKM
                </a>
                <a href="{{ route('umkm.index') }}" class="btn btn-outline-primary btn-sm">
                    Semua UMKM <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="row g-4">
            @foreach($approvedUmkms as $umkm)
            <div class="col-md-6 col-lg-4">
                <div class="card umkm-card h-100 border-0 shadow-sm">
                    <img src="{{ $umkm->business_photo_url }}"
                         class="card-img-top"
                         alt="{{ $umkm->business_name }}"
                         loading="lazy">
                    <div class="card-body">
                        <span class="badge bg-success-subtle text-success small mb-1">{{ $umkm->business_category }}</span>
                        <h6 class="fw-bold mb-1">{{ $umkm->business_name }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($umkm->description, 90) }}</p>
                        <p class="small mb-0 text-muted">
                            <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($umkm->address, 60) }}
                        </p>
                    </div>
                    @if($umkm->maps_link)
                    <div class="card-footer bg-transparent border-top">
                        <a href="{{ $umkm->maps_link }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-map me-1"></i>Lihat di Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

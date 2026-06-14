@extends('layouts.public')

@section('title', 'Galeri — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Galeri</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h1 class="section-title h3 mb-5">Galeri Kegiatan</h1>

        @if($galleries->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-images fs-1 d-block mb-3"></i>
                Belum ada foto di galeri.
            </div>
        @else
            <div class="row g-3">
                @foreach($galleries as $photo)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="gallery-item shadow-sm"
                         data-bs-toggle="modal" data-bs-target="#lightbox"
                         data-img="{{ $photo->image_url }}"
                         data-title="{{ $photo->title }}"
                         data-desc="{{ $photo->description }}"
                         data-date="{{ $photo->event_date?->translatedFormat('d F Y') }}">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" loading="lazy" class="w-100">
                        <div class="p-2 bg-white">
                            <p class="small fw-semibold mb-0 text-truncate">{{ $photo->title }}</p>
                            @if($photo->event_date)
                                <small class="text-muted">{{ $photo->event_date->translatedFormat('d M Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</section>

{{-- Lightbox Modal --}}
<div class="modal fade" id="lightbox" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header py-2">
                <div>
                    <h6 class="mb-0 fw-bold" id="lbTitle"></h6>
                    <small class="text-muted" id="lbDate"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="lbImg" src="" alt="" class="w-100">
            </div>
            <div class="modal-footer py-2" id="lbDesc"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('lightbox').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('lbImg').src    = b.dataset.img;
    document.getElementById('lbTitle').textContent = b.dataset.title;
    document.getElementById('lbDate').textContent  = b.dataset.date || '';
    document.getElementById('lbDesc').textContent  = b.dataset.desc || '';
});
</script>
@endpush
@endsection

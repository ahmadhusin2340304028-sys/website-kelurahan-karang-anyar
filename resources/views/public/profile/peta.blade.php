@extends('layouts.public')

@section('title', 'Peta Wilayah — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
            <li class="breadcrumb-item active">Peta Wilayah</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('public.profile.sidebar')
            </div>
            <div class="col-lg-9">
                <h1 class="section-title h3 mb-2">
                    {{ \App\Models\Setting::get('peta_wilayah_judul', 'Peta Wilayah Kelurahan') }}
                </h1>
        @if(\App\Models\Setting::get('peta_wilayah_keterangan'))
            <p class="text-muted mb-4">{{ \App\Models\Setting::get('peta_wilayah_keterangan') }}</p>
        @else
            <div class="mb-4"></div>
        @endif

        @php $petaPath = \App\Models\Setting::get('peta_wilayah'); @endphp

        @if($petaPath)
            <div class="card border-0 shadow-sm p-2">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($petaPath) }}"
                     alt="{{ \App\Models\Setting::get('peta_wilayah_judul', 'Peta Wilayah') }}"
                     class="img-fluid rounded"
                     style="width:100%; object-fit:contain; cursor:zoom-in;"
                     id="petaImg"
                     loading="lazy">
                <div class="text-end mt-2">
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($petaPath) }}"
                       download class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Unduh Peta
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1"
                            id="zoomBtn">
                        <i class="bi bi-zoom-in me-1"></i>Perbesar
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-5 text-muted border rounded bg-light">
                <i class="bi bi-map fs-1 d-block mb-3 opacity-50"></i>
                <p class="mb-0">Peta wilayah belum tersedia.</p>
                <small>Admin dapat mengupload peta di <strong>Pengaturan → Peta Wilayah</strong>.</small>
            </div>
        @endif

        {{-- Maps embed juga ditampilkan di bawah jika ada --}}
        @if(\App\Models\Setting::get('maps_embed'))
        <div class="mt-5">
            <h5 class="fw-bold mb-3"><i class="bi bi-pin-map me-2 text-primary"></i>Lokasi di Google Maps</h5>
            <div class="rounded overflow-hidden border shadow-sm" style="height:400px;">
                {!! \App\Models\Setting::get('maps_embed') !!}
            </div>
        </div>
        @endif
            </div>
        </div>
    </div>
</section>

{{-- Lightbox zoom modal --}}
<div class="modal fade" id="petaModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                @if($petaPath)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($petaPath) }}"
                     alt="Peta Wilayah" class="img-fluid rounded w-100">
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('zoomBtn')?.addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('petaModal')).show();
});
document.getElementById('petaImg')?.addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('petaModal')).show();
});
</script>
@endpush
@endsection

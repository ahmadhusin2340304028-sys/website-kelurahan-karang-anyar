@extends('layouts.public')

@section('title', 'UMKM — ' . \App\Models\Setting::get('kelurahan_name'))
@section('meta_description', 'Daftar UMKM unggulan warga Kelurahan Karang Anyar')

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">UMKM</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        {{-- Header --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h1 class="section-title h3">Daftar UMKM</h1>
                <p class="text-muted small">Produk & jasa unggulan warga Karang Anyar</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('umkm.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Daftarkan UMKM Anda
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('umkm.index') }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="search" name="search" class="form-control"
                       placeholder="Cari nama UMKM atau deskripsi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    @if(request()->anyFilled(['search','category']))
                        <a href="{{ route('umkm.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Results --}}
        @if($umkms->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-shop fs-1 d-block mb-3"></i>
                @if(request()->anyFilled(['search','category']))
                    Tidak ada UMKM yang sesuai pencarian.
                @else
                    Belum ada UMKM yang terdaftar.
                @endif
            </div>
        @else
            <div class="row g-4">
                @foreach($umkms as $umkm)
                <div class="col-md-6 col-lg-4">
                    <div class="card umkm-card h-100 border-0 shadow-sm">
                        <img src="{{ $umkm->business_photo_url }}"
                             class="card-img-top"
                             alt="{{ $umkm->business_name }}"
                             loading="lazy">
                        <div class="card-body">
                            <span class="badge bg-primary-subtle text-primary small mb-2">{{ $umkm->business_category }}</span>
                            <h6 class="fw-bold mb-1">{{ $umkm->business_name }}</h6>
                            <p class="small text-muted mb-1"><i class="bi bi-person me-1"></i>{{ $umkm->owner_name }}</p>
                            <p class="small text-muted mb-2">{{ Str::limit($umkm->description, 100) }}</p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-geo-alt me-1"></i>{{ Str::limit($umkm->address, 70) }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top d-flex gap-2">
                            @if($umkm->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->phone) }}"
                               target="_blank" class="btn btn-sm btn-success flex-grow-1">
                                <i class="bi bi-whatsapp me-1"></i>WA
                            </a>
                            @endif
                            @if($umkm->maps_link)
                            <a href="{{ $umkm->maps_link }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                <i class="bi bi-map me-1"></i>Maps
                            </a>
                            @endif
                        </div>
                        {{-- Product images --}}
                        @if($umkm->images->isNotEmpty())
                        <div class="d-flex gap-1 p-2">
                            @foreach($umkm->images->take(3) as $img)
                            <img src="{{ $img->image_url }}" alt="Produk"
                                 class="rounded" style="width:60px; height:60px; object-fit:cover;" loading="lazy">
                            @endforeach
                            @if($umkm->images->count() > 3)
                                <div class="rounded bg-dark d-flex align-items-center justify-content-center text-white small"
                                     style="width:60px; height:60px;">
                                    +{{ $umkm->images->count() - 3 }}
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $umkms->links() }}
            </div>
        @endif
    </div>
</section>
@endsection

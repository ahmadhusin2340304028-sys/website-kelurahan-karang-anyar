@extends('layouts.public')

@section('title', 'ASN Kelurahan — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
            <li class="breadcrumb-item active">ASN</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h1 class="section-title h3 mb-5 text-center center">ASN Kelurahan</h1>

        @forelse($officials as $levelKey => $group)
        <div class="mb-5">
            <h5 class="fw-bold text-primary border-bottom border-primary pb-2 mb-4">
                <i class="bi bi-person-badge me-2"></i>{{ $levels[$levelKey] ?? ucfirst($levelKey) }}
            </h5>
            <div class="row g-4 justify-content-center">
                @foreach($group as $official)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm text-center py-4 official-card h-100">
                        <div class="d-flex justify-content-center mb-3">
                            <img src="{{ $official->photo_url }}"
                                 alt="{{ $official->name }}"
                                 loading="lazy">
                        </div>
                        <div class="card-body pt-0">
                            <h6 class="fw-bold mb-1">{{ $official->name }}</h6>
                            <p class="text-muted small mb-0">{{ $official->position }}</p>
                            @if($official->phone)
                                <a href="tel:{{ $official->phone }}"
                                   class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-telephone me-1"></i>{{ $official->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            Data ASN belum tersedia.
        </div>
        @endforelse
    </div>
</section>
@endsection

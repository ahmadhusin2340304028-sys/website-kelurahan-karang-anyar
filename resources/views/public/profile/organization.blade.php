@extends('layouts.public')

@section('title', 'Struktur Organisasi — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
            <li class="breadcrumb-item active">Struktur Organisasi</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h1 class="section-title h3 mb-5 text-center center">Struktur Organisasi</h1>

        @if($structure)
            <div class="text-center">
                <h5 class="fw-bold mb-3">{{ $structure->title }}</h5>
                @if($structure->description)
                    <p class="text-muted mb-4">{{ $structure->description }}</p>
                @endif
                <img src="{{ $structure->image_url }}"
                     alt="{{ $structure->title }}"
                     class="img-fluid rounded shadow"
                     loading="lazy"
                     style="max-width: 900px; width: 100%;">
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>
                Bagan struktur organisasi belum tersedia.
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.public')

@section('title', 'Profil Kelurahan — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Profil Kelurahan</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded">
                            <a href="{{ route('profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile') ? 'active' : '' }}">
                                <i class="bi bi-building me-2"></i>Profil Kelurahan
                            </a>
                            <a href="{{ route('profile.history') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.history') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2"></i>Sejarah
                            </a>
                            <a href="{{ route('profile.vision') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.vision') ? 'active' : '' }}">
                                <i class="bi bi-eye me-2"></i>Visi & Misi
                            </a>
                            <a href="{{ route('profile.officials') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.officials') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i>Pejabat
                            </a>
                            <a href="{{ route('profile.peta') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.peta') ? 'active' : '' }}">
                            <i class="bi bi-map me-2"></i>Peta Wilayah
                        </a>
                        <a href="{{ route('profile.organization') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.organization') ? 'active' : '' }}">
                                <i class="bi bi-diagram-3 me-2"></i>Struktur Organisasi
                            </a>
                            <a href="{{ route('profile.contact') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.contact') ? 'active' : '' }}">
                                <i class="bi bi-telephone me-2"></i>Kontak
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <h1 class="section-title h3 mb-4">Profil Kelurahan</h1>

                @php $profile = \App\Models\Setting::get('profile') @endphp
                @if($profile)
                    <div class="lh-lg text-muted" style="text-align: justify;">
                        {!! nl2br(e($profile)) !!}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi profil kelurahan belum tersedia. Silakan hubungi admin.
                    </div>
                @endif

                <div class="row g-3 mt-4">
                    <div class="col-md-6">
                        <div class="card border-start border-primary border-3 bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-geo-alt me-2"></i>Alamat</h6>
                                <p class="mb-0 small">{{ \App\Models\Setting::get('address', 'Belum diatur') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-start border-success border-3 bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-2"><i class="bi bi-clock me-2"></i>Jam Pelayanan</h6>
                                <p class="mb-0 small">{{ \App\Models\Setting::get('office_hours', 'Belum diatur') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

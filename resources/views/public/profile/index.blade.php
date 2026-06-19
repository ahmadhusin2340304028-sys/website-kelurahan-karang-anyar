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
                @include('public.profile.sidebar')
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

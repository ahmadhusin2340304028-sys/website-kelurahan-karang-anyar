@extends('layouts.public')

@section('title', 'Kontak — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Kontak</li>
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
                <h1 class="section-title h3 mb-5">Hubungi Kami</h1>

                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">Informasi Kontak</h5>

                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                     style="width:48px; height:48px;">
                                    <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Alamat</h6>
                                <p class="text-muted mb-0 small">{{ \App\Models\Setting::get('address', 'Belum diatur') }}</p>
                            </div>
                        </div>

                        @if(\App\Models\Setting::get('phone'))
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                                     style="width:48px; height:48px;">
                                    <i class="bi bi-telephone-fill text-success fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Telepon</h6>
                                <a href="tel:{{ \App\Models\Setting::get('phone') }}"
                                   class="text-muted small text-decoration-none">
                                    {{ \App\Models\Setting::get('phone') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('email'))
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                                     style="width:48px; height:48px;">
                                    <i class="bi bi-envelope-fill text-warning fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <a href="mailto:{{ \App\Models\Setting::get('email') }}"
                                   class="text-muted small text-decoration-none">
                                    {{ \App\Models\Setting::get('email') }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(\App\Models\Setting::get('office_hours'))
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center"
                                     style="width:48px; height:48px;">
                                    <i class="bi bi-clock-fill text-info fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Jam Pelayanan</h6>
                                <p class="text-muted mb-0 small">{{ \App\Models\Setting::get('office_hours') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                @if(\App\Models\Setting::get('maps_embed'))
                    <div class="rounded overflow-hidden shadow-sm" style="height: 400px;">
                        {!! \App\Models\Setting::get('maps_embed') !!}
                    </div>
                @else
                    <div class="card border-0 shadow-sm d-flex align-items-center justify-content-center"
                         style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-map fs-1 d-block mb-3"></i>
                            Peta lokasi belum diatur.<br>
                            <small>Admin dapat menambahkan embed Google Maps di pengaturan.</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

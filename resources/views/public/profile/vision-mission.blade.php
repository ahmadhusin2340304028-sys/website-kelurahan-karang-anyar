@extends('layouts.public')

@section('title', 'Visi & Misi — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
            <li class="breadcrumb-item active">Visi & Misi</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h1 class="section-title h3 mb-5 center text-center">Visi & Misi</h1>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid var(--gov-blue) !important;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:64px; height:64px;">
                                <i class="bi bi-eye-fill text-white fs-3"></i>
                            </div>
                            <h4 class="fw-bold text-primary">VISI</h4>
                        </div>
                        @php $vision = \App\Models\Setting::get('vision') @endphp
                        @if($vision)
                            <p class="text-muted lh-lg text-center" style="font-size: 1.05rem; font-style: italic;">
                                "{{ $vision }}"
                            </p>
                        @else
                            <p class="text-muted text-center">Visi belum diatur.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid var(--gov-gold) !important;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:64px; height:64px;">
                                <i class="bi bi-bullseye text-white fs-3"></i>
                            </div>
                            <h4 class="fw-bold" style="color: var(--gov-gold);">MISI</h4>
                        </div>
                        @php $mission = \App\Models\Setting::get('mission') @endphp
                        @if($mission)
                            <div class="text-muted lh-lg">
                                {!! nl2br(e($mission)) !!}
                            </div>
                        @else
                            <p class="text-muted text-center">Misi belum diatur.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

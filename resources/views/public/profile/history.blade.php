@extends('layouts.public')

@section('title', 'Sejarah — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profil</a></li>
            <li class="breadcrumb-item active">Sejarah</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="section-title h3 mb-4">Sejarah Kelurahan</h1>

                @php $history = \App\Models\Setting::get('history') @endphp
                @if($history)
                    <div class="lh-lg text-muted" style="text-align: justify; font-size: 1.02rem;">
                        {!! nl2br(e($history)) !!}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi sejarah kelurahan belum tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-sm p-5">
                    <div class="mb-4">
                        <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:80px; height:80px;">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        </div>
                        <h3 class="fw-bold">Pendaftaran Berhasil!</h3>
                        <p class="text-muted">
                            Data UMKM Anda telah berhasil dikirim dan sedang menunggu verifikasi dari petugas kelurahan.
                            Proses verifikasi membutuhkan waktu 1–3 hari kerja.
                        </p>
                    </div>
                    <div class="alert alert-info small text-start">
                        <i class="bi bi-info-circle me-2"></i>
                        Setelah diverifikasi, usaha Anda akan tampil di halaman UMKM website ini.
                        Untuk informasi lebih lanjut, hubungi kantor kelurahan.
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('umkm.index') }}" class="btn btn-primary">
                            <i class="bi bi-shop me-2"></i>Lihat Daftar UMKM
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

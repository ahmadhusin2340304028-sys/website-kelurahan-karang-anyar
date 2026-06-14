@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="py-4">
    <h4 class="fw-bold mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
    <p class="text-muted small mb-4">
        <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
    </p>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        {{-- Berita --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['posts'] }}</div>
                        <div class="text-muted small">Total Berita</div>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success"><i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>
                        {{ $stats['published_posts'] }} dipublikasi
                    </small>
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['categories'] }}</div>
                        <div class="text-muted small">Kategori</div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('admin.categories.index') }}" class="small text-primary text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>

        {{-- Pejabat --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['officials'] }}</div>
                        <div class="text-muted small">Pejabat Aktif</div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('admin.officials.index') }}" class="small text-primary text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>

        {{-- Galeri --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-images"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['galleries'] }}</div>
                        <div class="text-muted small">Foto Galeri</div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('admin.galleries.index') }}" class="small text-primary text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>

        {{-- Pengumuman --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-secondary-subtle text-secondary">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['announcements'] }}</div>
                        <div class="text-muted small">Pengumuman Aktif</div>
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('admin.announcements.index') }}" class="small text-primary text-decoration-none">Kelola →</a>
                </div>
            </div>
        </div>

        {{-- UMKM Total --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $stats['umkms_approved'] }}</div>
                        <div class="text-muted small">UMKM Disetujui</div>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">dari {{ $stats['umkms_total'] }} total</small>
                </div>
            </div>
        </div>

        {{-- UMKM Pending --}}
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card p-3 {{ $stats['umkms_pending'] > 0 ? 'border-warning border' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning">{{ $stats['umkms_pending'] }}</div>
                        <div class="text-muted small">UMKM Menunggu</div>
                    </div>
                </div>
                @if($stats['umkms_pending'] > 0)
                <div class="mt-2">
                    <a href="{{ route('admin.umkms.index', ['status' => 'pending']) }}"
                       class="btn btn-warning btn-sm w-100 py-1">
                        <i class="bi bi-eye me-1"></i>Tinjau Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent & Pending --}}
    <div class="row g-4">
        {{-- Berita Terbaru --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-newspaper me-2 text-primary"></i>Berita Terbaru</h6>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus me-1"></i>Tambah
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentPosts->isEmpty())
                        <div class="text-center py-4 text-muted small">Belum ada berita</div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($recentPosts as $post)
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="list-group-item list-group-item-action border-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 me-2">
                                    <p class="mb-0 fw-semibold small">{{ Str::limit($post->title, 55) }}</p>
                                    <small class="text-muted">{{ $post->category?->name ?? '—' }} · {{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-secondary' }} flex-shrink-0">
                                    {{ $post->status === 'published' ? 'Tayang' : 'Draft' }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-end">
                    <a href="{{ route('admin.posts.index') }}" class="small text-primary text-decoration-none">
                        Lihat semua berita →
                    </a>
                </div>
            </div>
        </div>

        {{-- UMKM Pending --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-shop me-2 text-warning"></i>UMKM Menunggu Verifikasi</h6>
                    <span class="badge bg-warning text-dark">{{ $stats['umkms_pending'] }}</span>
                </div>
                <div class="card-body p-0">
                    @if($pendingUmkms->isEmpty())
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-check-circle text-success d-block mb-1 fs-4"></i>
                            Tidak ada UMKM menunggu verifikasi
                        </div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($pendingUmkms as $umkm)
                        <a href="{{ route('admin.umkms.show', $umkm) }}"
                           class="list-group-item list-group-item-action border-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-0 fw-semibold small">{{ $umkm->business_name }}</p>
                                    <small class="text-muted">{{ $umkm->owner_name }} · {{ $umkm->business_category }}</small>
                                </div>
                                <small class="text-muted">{{ $umkm->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-end">
                    <a href="{{ route('admin.umkms.index') }}" class="small text-primary text-decoration-none">
                        Kelola semua UMKM →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

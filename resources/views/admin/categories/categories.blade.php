@extends('layouts.admin')

@section('title', 'Kelola Galeri')
@section('breadcrumb', 'Galeri')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Galeri</h4>
        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Foto
        </a>
    </div>

    <div class="row g-3">
        @forelse($galleries as $gallery)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div style="position:relative;">
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}"
                         class="card-img-top" style="height:160px; object-fit:cover;" loading="lazy">
                    <span class="badge {{ $gallery->is_active ? 'bg-success' : 'bg-secondary' }}"
                          style="position:absolute; top:8px; right:8px;">
                        {{ $gallery->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="card-body py-2">
                    <h6 class="fw-semibold small mb-1">{{ Str::limit($gallery->title, 40) }}</h6>
                    @if($gallery->event_date)
                        <small class="text-muted">{{ $gallery->event_date->translatedFormat('d M Y') }}</small>
                    @endif
                </div>
                <div class="card-footer bg-transparent border-top d-flex gap-1 py-2">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}"
                       class="btn btn-sm btn-outline-primary flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST"
                          onsubmit="return confirm('Hapus foto ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-images fs-1 d-block mb-3"></i>
            Belum ada foto di galeri.
        </div>
        @endforelse
    </div>

    @if($galleries->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $galleries->links() }}</div>
    @endif
</div>
@endsection
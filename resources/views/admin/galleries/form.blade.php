@extends('layouts.admin')

@section('title', isset($gallery) ? 'Edit Foto Galeri' : 'Tambah Foto Galeri')
@section('breadcrumb', 'Galeri')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ isset($gallery) ? 'Edit Foto' : 'Tambah Foto Galeri' }}</h4>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($gallery) ? route('admin.galleries.update', $gallery) : route('admin.galleries.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($gallery)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Foto <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $gallery->title ?? '') }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Foto {{ isset($gallery) ? '(kosongkan jika tidak ingin mengganti)' : '' }} <span class="text-danger">{{ isset($gallery) ? '' : '*' }}</span></label>
                                @if(isset($gallery) && $gallery->image)
                                    <div class="mb-2">
                                        <img src="{{ $gallery->image_url }}" alt="" class="img-fluid rounded"
                                             style="max-height:200px;" id="imgPreview">
                                    </div>
                                @else
                                    <img src="" alt="" class="img-fluid rounded mb-2 d-none" style="max-height:200px;" id="imgPreview">
                                @endif
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/webp" id="imgInput"
                                       {{ !isset($gallery) ? 'required' : '' }}>
                                <small class="text-muted">JPG/PNG/WEBP, max 2MB</small>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Kegiatan</label>
                                <input type="date" name="event_date" class="form-control"
                                       value="{{ old('event_date', isset($gallery) ? $gallery->event_date?->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" rows="2" class="form-control"
                                          placeholder="Keterangan foto (opsional)">{{ old('description', $gallery->description ?? '') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                           id="isActive"
                                           {{ old('is_active', $gallery->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Tampilkan di website</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('imgInput').addEventListener('change', function() {
    const prev = document.getElementById('imgPreview');
    if (this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
    }
});
</script>
@endpush
@endsection

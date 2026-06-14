@extends('layouts.admin')

@section('title', isset($organization) ? 'Edit Struktur Organisasi' : 'Upload Bagan Struktur')
@section('breadcrumb', 'Struktur Organisasi')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ isset($organization) ? 'Edit Bagan' : 'Upload Bagan Struktur' }}</h4>
        <a href="{{ route('admin.organization.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($organization) ? route('admin.organization.update', $organization) : route('admin.organization.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($organization)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $organization->title ?? 'Struktur Organisasi Kelurahan Karang Anyar') }}"
                                       required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Gambar Bagan
                                    {{ isset($organization) ? '(kosongkan jika tidak mengganti)' : '' }}
                                    <span class="text-danger">{{ isset($organization) ? '' : '*' }}</span>
                                </label>
                                @if(isset($organization) && $organization->image)
                                    <div class="mb-2">
                                        <img src="{{ $organization->image_url }}" alt="Bagan"
                                             class="img-fluid rounded border" style="max-height:250px;"
                                             id="imgPreview">
                                    </div>
                                @else
                                    <img id="imgPreview" src="" alt="" class="img-fluid rounded border mb-2 d-none"
                                         style="max-height:250px;">
                                @endif
                                <input type="file" name="image"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/webp"
                                       id="imgInput"
                                       {{ !isset($organization) ? 'required' : '' }}>
                                <small class="text-muted">Format: JPG/PNG/WEBP, max 3MB. Gunakan gambar resolusi tinggi agar terbaca jelas.</small>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="description" rows="2" class="form-control"
                                          placeholder="Keterangan tambahan (opsional)">{{ old('description', $organization->description ?? '') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           value="1" id="isActive"
                                           {{ old('is_active', $organization->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Jadikan bagan aktif (tampil di website)
                                    </label>
                                </div>
                                <small class="text-muted ms-4">Mengaktifkan bagan ini akan menonaktifkan bagan lainnya.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.organization.index') }}" class="btn btn-outline-secondary">Batal</a>
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

@extends('layouts.admin')

@section('title', isset($official) ? 'Edit Pejabat' : 'Tambah Pejabat')
@section('breadcrumb', isset($official) ? 'Edit Pejabat' : 'Tambah Pejabat')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ isset($official) ? 'Edit Pejabat' : 'Tambah Pejabat' }}</h4>
        <a href="{{ route('admin.officials.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($official) ? route('admin.officials.update', $official) : route('admin.officials.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($official)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $official->name ?? '') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Urutan Tampil</label>
                                <input type="number" name="sort_order" class="form-control" min="0"
                                       value="{{ old('sort_order', $official->sort_order ?? 0) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror"
                                       value="{{ old('position', $official->position ?? '') }}"
                                       placeholder="Contoh: Lurah Karang Anyar" required>
                                @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Level Jabatan <span class="text-danger">*</span></label>
                                <select name="position_level" class="form-select @error('position_level') is-invalid @enderror" required>
                                    <option value="">-- Pilih Level --</option>
                                    @foreach($positionLevels as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('position_level', $official->position_level ?? '') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $official->phone ?? '') }}"
                                       placeholder="Opsional">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Foto</label>
                                @if(isset($official) && $official->photo)
                                    <div class="mb-2">
                                        <img src="{{ $official->photo_url }}" alt=""
                                             class="rounded-circle" style="width:80px; height:80px; object-fit:cover;"
                                             id="photoPreview">
                                    </div>
                                @else
                                    <img src="" alt="" class="rounded-circle mb-2 d-none"
                                         style="width:80px; height:80px; object-fit:cover;" id="photoPreview">
                                @endif
                                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/webp" id="photoInput">
                                <small class="text-muted">JPG/PNG/WEBP, max 1MB. Optimal: foto persegi 300×300px</small>
                                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           value="1" id="isActive"
                                           {{ old('is_active', $official->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Tampilkan di website</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.officials.index') }}" class="btn btn-outline-secondary">Batal</a>
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
document.getElementById('photoInput').addEventListener('change', function() {
    const prev = document.getElementById('photoPreview');
    if (this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
    }
});
</script>
@endpush
@endsection

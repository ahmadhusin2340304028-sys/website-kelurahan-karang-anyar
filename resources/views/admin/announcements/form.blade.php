@extends('layouts.admin')

@section('title', isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman')
@section('breadcrumb', 'Pengumuman')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}</h4>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
                          method="POST">
                        @csrf
                        @if(isset($announcement)) @method('PUT') @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $announcement->title ?? '') }}"
                                       placeholder="Judul pengumuman yang jelas dan singkat" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                                <textarea name="content" rows="6"
                                          class="form-control @error('content') is-invalid @enderror"
                                          placeholder="Tulis isi pengumuman secara lengkap..." required>{{ old('content', $announcement->content ?? '') }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', isset($announcement) ? $announcement->start_date->format('Y-m-d') : today()->format('Y-m-d')) }}"
                                       required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Berakhir <small class="text-muted">(Opsional)</small></label>
                                <input type="date" name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', isset($announcement) ? $announcement->end_date?->format('Y-m-d') : '') }}">
                                <small class="text-muted">Kosongkan jika pengumuman tidak memiliki batas akhir.</small>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           value="1" id="isActive"
                                           {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Aktifkan pengumuman
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

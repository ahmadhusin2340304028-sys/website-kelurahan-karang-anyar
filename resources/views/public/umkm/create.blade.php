@extends('layouts.public')

@section('title', 'Daftarkan UMKM — ' . \App\Models\Setting::get('kelurahan_name'))

@section('content')
<div class="breadcrumb-gov">
    <div class="container">
        <nav><ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('umkm.index') }}">UMKM</a></li>
            <li class="breadcrumb-item active">Daftar UMKM</li>
        </ol></nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-shop me-2"></i>Pendaftaran UMKM</h5>
                        <small class="text-white-50">Isi formulir berikut untuk mendaftarkan usaha Anda</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info small mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Data UMKM yang Anda kirimkan akan diverifikasi oleh petugas kelurahan sebelum ditampilkan di website.
                        </div>

                        <form action="{{ route('umkm.store') }}" method="POST" enctype="multipart/form-data"
                              id="umkmForm" novalidate>
                            @csrf

                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Data Pemilik</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                                           value="{{ old('owner_name') }}" placeholder="Nama lengkap pemilik" required>
                                    @error('owner_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor HP <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}" placeholder="Contoh: 08123456789" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <small class="text-muted">(Opsional)</small></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="email@contoh.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Data Usaha</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama UMKM / Usaha <span class="text-danger">*</span></label>
                                    <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror"
                                           value="{{ old('business_name') }}" placeholder="Nama usaha Anda" required>
                                    @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori Usaha <span class="text-danger">*</span></label>
                                    <select name="business_category" class="form-select @error('business_category') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" {{ old('business_category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('business_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi Usaha <span class="text-danger">*</span></label>
                                    <textarea name="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Jelaskan usaha Anda secara singkat (produk/jasa yang ditawarkan, keunggulan, dll)" required>{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Usaha <span class="text-danger">*</span></label>
                                    <textarea name="address" rows="2"
                                              class="form-control @error('address') is-invalid @enderror"
                                              placeholder="Alamat lengkap tempat usaha" required>{{ old('address') }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Link Google Maps <small class="text-muted">(Opsional)</small></label>
                                    <input type="url" name="maps_link" class="form-control @error('maps_link') is-invalid @enderror"
                                           value="{{ old('maps_link') }}" placeholder="https://maps.google.com/...">
                                    @error('maps_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Foto</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Foto Usaha / Tempat</label>
                                    <input type="file" name="business_photo" class="form-control @error('business_photo') is-invalid @enderror"
                                           accept="image/jpeg,image/png,image/webp" id="businessPhotoInput">
                                    <small class="text-muted">Format: JPG/PNG/WEBP, max 2MB</small>
                                    @error('business_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <img id="businessPhotoPreview" src="" alt="" class="img-fluid rounded mt-2 d-none" style="max-height:150px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Foto Produk <small class="text-muted">(Maks. 5 foto)</small></label>
                                    <input type="file" name="product_images[]" multiple class="form-control @error('product_images') is-invalid @enderror"
                                           accept="image/jpeg,image/png,image/webp" id="productImagesInput">
                                    <small class="text-muted">Format: JPG/PNG/WEBP, max 2MB per foto</small>
                                    @error('product_images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div id="productPreviews" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-send me-2"></i>Kirim Pendaftaran UMKM
                                </button>
                                <a href="{{ route('umkm.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Preview foto usaha
document.getElementById('businessPhotoInput').addEventListener('change', function() {
    const preview = document.getElementById('businessPhotoPreview');
    if (this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.classList.remove('d-none');
    }
});

// Preview foto produk
document.getElementById('productImagesInput').addEventListener('change', function() {
    const container = document.getElementById('productPreviews');
    container.innerHTML = '';
    [...this.files].slice(0, 5).forEach(file => {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'rounded';
        img.style.cssText = 'height:80px; width:80px; object-fit:cover;';
        container.appendChild(img);
    });
});

// Disable submit button on submit
document.getElementById('umkmForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
});
</script>
@endpush
@endsection

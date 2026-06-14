@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="py-4">
    <h4 class="fw-bold mb-4">Pengaturan Website</h4>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <ul class="nav nav-tabs mb-4" id="settingTabs">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general">Umum</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-profile">Profil Kelurahan</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-contact">Kontak</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stats">Statistik</button></li>
        </ul>

        <div class="tab-content">
            {{-- TAB: Umum --}}
            <div class="tab-pane fade show active" id="tab-general">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Informasi Website</h6></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Website</label>
                                <input type="text" name="site_name" class="form-control"
                                       value="{{ $settings['site_name'] ?? '' }}"
                                       placeholder="Kelurahan Karang Anyar">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Kelurahan</label>
                                <input type="text" name="kelurahan_name" class="form-control"
                                       value="{{ $settings['kelurahan_name'] ?? '' }}"
                                       placeholder="Kelurahan Karang Anyar" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tagline</label>
                                <input type="text" name="site_tagline" class="form-control"
                                       value="{{ $settings['site_tagline'] ?? '' }}"
                                       placeholder="Melayani masyarakat dengan sepenuh hati">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Website (SEO)</label>
                                <textarea name="site_description" rows="2" class="form-control"
                                          placeholder="Deskripsi singkat untuk mesin pencari">{{ $settings['site_description'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Logo</label>
                                @if(!empty($settings['logo']))
                                    <div class="mb-2">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo']) }}"
                                             alt="Logo" height="50" class="border rounded p-1">
                                    </div>
                                @endif
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <small class="text-muted">Format: PNG/SVG/JPG. Optimal: 200×60px</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Favicon</label>
                                @if(!empty($settings['favicon']))
                                    <div class="mb-2">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['favicon']) }}"
                                             alt="Favicon" height="32" class="border rounded p-1">
                                    </div>
                                @endif
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                                <small class="text-muted">Format: PNG/ICO. 32×32px</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Profil --}}
            <div class="tab-pane fade" id="tab-profile">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Profil & Konten</h6></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Sejarah Kelurahan</label>
                                <textarea name="history" rows="5" class="form-control">{{ $settings['history'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Visi</label>
                                <textarea name="vision" rows="3" class="form-control">{{ $settings['vision'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Misi</label>
                                <textarea name="mission" rows="3" class="form-control">{{ $settings['mission'] ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Profil Singkat</label>
                                <textarea name="profile" rows="3" class="form-control">{{ $settings['profile'] ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Sambutan Lurah</label>
                                <textarea name="greeting_lurah" rows="5" class="form-control"
                                          placeholder="Tuliskan sambutan dari Bapak/Ibu Lurah...">{{ $settings['greeting_lurah'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Kontak --}}
            <div class="tab-pane fade" id="tab-contact">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Informasi Kontak</h6></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Kantor</label>
                                <textarea name="address" rows="2" class="form-control" required>{{ $settings['address'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ $settings['phone'] ?? '' }}" placeholder="Contoh: (0551) 123456">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Kantor</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ $settings['email'] ?? '' }}" placeholder="kelurahan@example.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Jam Pelayanan</label>
                                <input type="text" name="office_hours" class="form-control"
                                       value="{{ $settings['office_hours'] ?? '' }}"
                                       placeholder="Senin–Jumat: 07.30–15.30 WIT">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Google Maps Embed</label>
                                <textarea name="maps_embed" rows="3" class="form-control"
                                          placeholder='<iframe src="https://maps.google.com/..." ...></iframe>'>{{ $settings['maps_embed'] ?? '' }}</textarea>
                                <small class="text-muted">Salin kode embed dari Google Maps (klik Bagikan → Sematkan peta)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: Statistik --}}
            <div class="tab-pane fade" id="tab-stats">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Data Statistik Kelurahan</h6></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Penduduk</label>
                                <input type="text" name="jumlah_penduduk" class="form-control"
                                       value="{{ $settings['jumlah_penduduk'] ?? '' }}" placeholder="Contoh: 5.234">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Kepala Keluarga</label>
                                <input type="text" name="jumlah_kk" class="form-control"
                                       value="{{ $settings['jumlah_kk'] ?? '' }}" placeholder="Contoh: 1.245">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah RW</label>
                                <input type="text" name="jumlah_rw" class="form-control"
                                       value="{{ $settings['jumlah_rw'] ?? '' }}" placeholder="Contoh: 6">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah RT</label>
                                <input type="text" name="jumlah_rt" class="form-control"
                                       value="{{ $settings['jumlah_rt'] ?? '' }}" placeholder="Contoh: 24">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

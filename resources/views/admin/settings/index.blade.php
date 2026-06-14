@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="py-4">
    <h4 class="fw-bold mb-4">Pengaturan Website</h4>

    {{--
        PENTING: Tab tombol HARUS type="button" agar tidak men-trigger submit form.
        Sebelumnya bug: <button> tanpa type di dalam <form> = type="submit" by default.
    --}}
    <ul class="nav nav-tabs mb-0" id="settingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" type="button" role="tab"
                    data-bs-toggle="tab" data-bs-target="#tab-general"
                    aria-controls="tab-general" aria-selected="true">
                <i class="bi bi-gear me-1"></i>Umum
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" role="tab"
                    data-bs-toggle="tab" data-bs-target="#tab-profile"
                    aria-controls="tab-profile" aria-selected="false">
                <i class="bi bi-building me-1"></i>Profil Kelurahan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" role="tab"
                    data-bs-toggle="tab" data-bs-target="#tab-contact"
                    aria-controls="tab-contact" aria-selected="false">
                <i class="bi bi-telephone me-1"></i>Kontak
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" role="tab"
                    data-bs-toggle="tab" data-bs-target="#tab-map"
                    aria-controls="tab-map" aria-selected="false">
                <i class="bi bi-map me-1"></i>Peta Wilayah
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" role="tab"
                    data-bs-toggle="tab" data-bs-target="#tab-stats"
                    aria-controls="tab-stats" aria-selected="false">
                <i class="bi bi-bar-chart me-1"></i>Statistik
            </button>
        </li>
    </ul>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Simpan tab aktif supaya setelah submit kembali ke tab yang sama --}}
        <input type="hidden" name="_active_tab" id="activeTabInput" value="{{ old('_active_tab', 'tab-general') }}">

        <div class="tab-content border border-top-0 rounded-bottom bg-white p-4 shadow-sm">

            {{-- ══ TAB: UMUM ══════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                    <i class="bi bi-gear me-2"></i>Informasi Umum Website
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Website <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control"
                               value="{{ $settings['site_name'] ?? '' }}"
                               placeholder="Kelurahan Karang Anyar" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Kelurahan <span class="text-danger">*</span></label>
                        <input type="text" name="kelurahan_name" class="form-control"
                               value="{{ $settings['kelurahan_name'] ?? '' }}"
                               placeholder="Kelurahan Karang Anyar" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tagline / Slogan</label>
                        <input type="text" name="site_tagline" class="form-control"
                               value="{{ $settings['site_tagline'] ?? '' }}"
                               placeholder="Melayani masyarakat dengan sepenuh hati">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Website
                            <small class="text-muted fw-normal">(untuk mesin pencari / SEO)</small>
                        </label>
                        <textarea name="site_description" rows="2" class="form-control"
                                  placeholder="Deskripsi singkat 120–160 karakter">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo Website</label>
                        <div class="mb-2">
                            @if(!empty($settings['logo']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo']) }}"
                                     alt="Logo" class="border rounded p-1 bg-white"
                                     style="height:48px; max-width:200px; object-fit:contain;"
                                     id="logoPreview">
                            @else
                                <div class="border rounded p-2 text-muted small d-inline-block" id="logoPlaceholder">
                                    <i class="bi bi-image me-1"></i>Belum ada logo
                                </div>
                                <img src="" alt="" class="border rounded p-1 d-none" id="logoPreview"
                                     style="height:48px; max-width:200px; object-fit:contain;">
                            @endif
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*" id="logoInput">
                        <small class="text-muted">Format: PNG/SVG/JPG. Rekomendasi: 200×60px, latar transparan.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Favicon</label>
                        <div class="mb-2">
                            @if(!empty($settings['favicon']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['favicon']) }}"
                                     alt="Favicon" class="border rounded p-1"
                                     style="height:32px; width:32px; object-fit:contain;"
                                     id="faviconPreview">
                            @else
                                <div class="border rounded p-2 text-muted small d-inline-block" id="faviconPlaceholder">
                                    <i class="bi bi-image me-1"></i>Belum ada favicon
                                </div>
                                <img src="" alt="" class="border rounded p-1 d-none" id="faviconPreview"
                                     style="height:32px; width:32px; object-fit:contain;">
                            @endif
                        </div>
                        <input type="file" name="favicon" class="form-control" accept="image/*" id="faviconInput">
                        <small class="text-muted">Format: PNG/ICO. Ukuran: 32×32px atau 64×64px.</small>
                    </div>
                </div>
            </div>

            {{-- ══ TAB: PROFIL KELURAHAN ══════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-profile" role="tabpanel">
                <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                    <i class="bi bi-building me-2"></i>Profil & Konten Kelurahan
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Sejarah Kelurahan</label>
                        <textarea name="history" rows="6" class="form-control"
                                  placeholder="Tuliskan sejarah singkat kelurahan...">{{ $settings['history'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Visi</label>
                        <textarea name="vision" rows="4" class="form-control"
                                  placeholder="Visi kelurahan...">{{ $settings['vision'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Misi</label>
                        <textarea name="mission" rows="4" class="form-control"
                                  placeholder="Tulis tiap poin misi di baris baru...">{{ $settings['mission'] ?? '' }}</textarea>
                        <small class="text-muted">Tiap poin misi di baris baru, contoh: "1. ..."</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Profil Singkat Kelurahan</label>
                        <textarea name="profile" rows="4" class="form-control"
                                  placeholder="Deskripsi singkat kelurahan untuk halaman profil...">{{ $settings['profile'] ?? '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Sambutan Lurah</label>
                        <textarea name="greeting_lurah" rows="6" class="form-control"
                                  placeholder="Tuliskan sambutan dari Bapak/Ibu Lurah...">{{ $settings['greeting_lurah'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ══ TAB: KONTAK ════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                    <i class="bi bi-telephone me-2"></i>Informasi Kontak & Lokasi
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Kantor <span class="text-danger">*</span></label>
                        <textarea name="address" rows="2" class="form-control" required>{{ $settings['address'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ $settings['phone'] ?? '' }}" placeholder="(0551) 123456">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Kantor</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control"
                                   value="{{ $settings['email'] ?? '' }}" placeholder="kelurahan@example.com">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Jam Pelayanan</label>
                        <input type="text" name="office_hours" class="form-control"
                               value="{{ $settings['office_hours'] ?? '' }}"
                               placeholder="Senin–Jumat: 07.30–15.30 WIT">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Google Maps Embed
                            <small class="text-muted fw-normal">(untuk tampilan peta di halaman Kontak)</small>
                        </label>
                        <textarea name="maps_embed" rows="4" class="form-control font-monospace"
                                  placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ $settings['maps_embed'] ?? '' }}</textarea>
                        <div class="alert alert-light border mt-2 py-2 small">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            <strong>Cara mendapatkan kode embed:</strong>
                            Buka <a href="https://maps.google.com" target="_blank">Google Maps</a>
                            → Cari lokasi kelurahan → Klik <strong>Bagikan</strong> → Tab
                            <strong>Sematkan Peta</strong> → Salin kode <code>&lt;iframe&gt;</code> dan tempel di sini.
                        </div>
                    </div>

                    {{-- Preview peta jika sudah diisi --}}
                    @if(!empty($settings['maps_embed']))
                    <div class="col-12">
                        <label class="form-label fw-semibold text-muted small">PREVIEW PETA SAAT INI</label>
                        <div class="rounded overflow-hidden border" style="height:250px;">
                            {!! $settings['maps_embed'] !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ══ TAB: PETA WILAYAH ══════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-map" role="tabpanel">
                <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                    <i class="bi bi-map me-2"></i>Peta Wilayah Kelurahan
                </h6>

                <div class="alert alert-info mb-4">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Peta Wilayah</strong> berbeda dengan peta Google Maps interaktif.
                    Di sini Anda upload <strong>gambar/foto peta wilayah kelurahan</strong>
                    (bisa berupa peta administratif, peta RW/RT, atau denah wilayah) untuk
                    ditampilkan di halaman profil website.
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Upload Gambar Peta Wilayah</label>

                        {{-- Preview peta yang sudah ada --}}
                        @if(!empty($settings['peta_wilayah']))
                            <div class="mb-3">
                                <label class="small text-muted fw-semibold d-block mb-1">PETA SAAT INI</label>
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['peta_wilayah']) }}"
                                     alt="Peta Wilayah"
                                     class="img-fluid rounded border shadow-sm"
                                     style="max-height:300px; width:100%; object-fit:contain; background:#f8f9fa;"
                                     id="petaPreview">
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Peta sudah diupload
                                    </span>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="hapus_peta_wilayah" value="1" id="hapusPeta">
                                        <label class="form-check-label text-danger small" for="hapusPeta">
                                            Hapus peta wilayah yang ada
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border rounded p-4 text-center text-muted mb-3 bg-light"
                                 id="petaPlaceholder">
                                <i class="bi bi-map fs-1 d-block mb-2 opacity-25"></i>
                                <p class="mb-0 small">Belum ada peta wilayah diupload</p>
                            </div>
                            <img src="" alt="" class="img-fluid rounded border mb-3 d-none"
                                 style="max-height:300px; width:100%; object-fit:contain;" id="petaPreview">
                        @endif

                        <input type="file" name="peta_wilayah" class="form-control" id="petaInput"
                               accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-1">
                            Format: JPG/PNG/WEBP, maks. 5MB.
                            Gunakan resolusi tinggi agar teks di peta terbaca.
                        </small>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Keterangan Peta</label>
                        <input type="text" name="peta_wilayah_judul" class="form-control mb-3"
                               value="{{ $settings['peta_wilayah_judul'] ?? '' }}"
                               placeholder="Contoh: Peta Wilayah Kelurahan Karang Anyar">

                        <label class="form-label fw-semibold">Deskripsi / Keterangan Tambahan</label>
                        <textarea name="peta_wilayah_keterangan" rows="3" class="form-control"
                                  placeholder="Contoh: Kelurahan Karang Anyar terdiri dari 6 RW dan 24 RT...">{{ $settings['peta_wilayah_keterangan'] ?? '' }}</textarea>

                        <div class="card border-0 bg-light mt-4">
                            <div class="card-body py-3">
                                <h6 class="fw-bold small mb-2"><i class="bi bi-question-circle me-1 text-primary"></i>Cara Mendapatkan Peta Wilayah</h6>
                                <ul class="small text-muted mb-0 ps-3">
                                    <li class="mb-1">Minta ke Dinas Tata Ruang / BPS setempat</li>
                                    <li class="mb-1">Unduh dari <a href="https://tanahair.indonesia.go.id" target="_blank">Ina-Geoportal</a></li>
                                    <li class="mb-1">Buat screenshot dari Google Earth / Google Maps</li>
                                    <li>Scan peta cetak yang sudah ada</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ TAB: STATISTIK ═════════════════════════════════════ --}}
            <div class="tab-pane fade" id="tab-stats" role="tabpanel">
                <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                    <i class="bi bi-bar-chart me-2"></i>Data Statistik Kelurahan
                </h6>
                <p class="text-muted small mb-4">
                    Data ini ditampilkan di bagian statistik pada halaman beranda website.
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-people me-1 text-primary"></i>Jumlah Penduduk
                        </label>
                        <input type="text" name="jumlah_penduduk" class="form-control"
                               value="{{ $settings['jumlah_penduduk'] ?? '' }}"
                               placeholder="Contoh: 5.234 jiwa">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-house me-1 text-success"></i>Jumlah Kepala Keluarga
                        </label>
                        <input type="text" name="jumlah_kk" class="form-control"
                               value="{{ $settings['jumlah_kk'] ?? '' }}"
                               placeholder="Contoh: 1.245 KK">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-diagram-2 me-1 text-warning"></i>Jumlah RW
                        </label>
                        <input type="text" name="jumlah_rw" class="form-control"
                               value="{{ $settings['jumlah_rw'] ?? '' }}"
                               placeholder="Contoh: 6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-grid me-1 text-danger"></i>Jumlah RT
                        </label>
                        <input type="text" name="jumlah_rt" class="form-control"
                               value="{{ $settings['jumlah_rt'] ?? '' }}"
                               placeholder="Contoh: 24">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-arrows-fullscreen me-1 text-secondary"></i>Luas Wilayah
                        </label>
                        <input type="text" name="luas_wilayah" class="form-control"
                               value="{{ $settings['luas_wilayah'] ?? '' }}"
                               placeholder="Contoh: 2,45 km²">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar me-1 text-info"></i>Tahun Berdiri
                        </label>
                        <input type="text" name="tahun_berdiri" class="form-control"
                               value="{{ $settings['tahun_berdiri'] ?? '' }}"
                               placeholder="Contoh: 1987">
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}

        {{-- Tombol Simpan --}}
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x me-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// ── Ingat tab aktif ─────────────────────────────────────────────────────────
const STORAGE_KEY = 'settings_active_tab';

// Restore tab dari sessionStorage atau dari nilai yang dikembalikan server
document.addEventListener('DOMContentLoaded', function () {
    const savedTab = sessionStorage.getItem(STORAGE_KEY)
                     || document.getElementById('activeTabInput').value
                     || 'tab-general';

    const tabEl = document.querySelector(`[data-bs-target="#${savedTab}"]`);
    if (tabEl) {
        new bootstrap.Tab(tabEl).show();
    }
});

// Simpan tab saat berpindah & update hidden input
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (btn) {
    btn.addEventListener('shown.bs.tab', function (e) {
        const target = e.target.dataset.bsTarget.replace('#', '');
        sessionStorage.setItem(STORAGE_KEY, target);
        document.getElementById('activeTabInput').value = target;
    });
});

// ── Preview Logo ─────────────────────────────────────────────────────────────
document.getElementById('logoInput').addEventListener('change', function () {
    const prev = document.getElementById('logoPreview');
    const ph   = document.getElementById('logoPlaceholder');
    if (this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    }
});

// ── Preview Favicon ───────────────────────────────────────────────────────────
document.getElementById('faviconInput').addEventListener('change', function () {
    const prev = document.getElementById('faviconPreview');
    const ph   = document.getElementById('faviconPlaceholder');
    if (this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    }
});

// ── Preview Peta Wilayah ──────────────────────────────────────────────────────
document.getElementById('petaInput').addEventListener('change', function () {
    const prev = document.getElementById('petaPreview');
    const ph   = document.getElementById('petaPlaceholder');
    if (this.files[0]) {
        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    }
});
</script>
@endpush

@extends('layouts.admin')

@section('title', isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman')
@section('breadcrumb', 'Pengumuman')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    /* Editor Quill */
    #quillEditor { min-height: 220px; font-size: 0.97rem; }
    .ql-toolbar.ql-snow { border-radius: 6px 6px 0 0; border-color: #dee2e6; background: #f8f9fa; }
    .ql-container.ql-snow { border-radius: 0 0 6px 6px; border-color: #dee2e6; }
    .ql-container.ql-snow.is-invalid,
    .ql-toolbar.ql-snow.is-invalid { border-color: #dc3545; }

    /* Preview card */
    .preview-card {
        border-left: 4px solid #c8a951;
        background: linear-gradient(90deg,#fffbea,#fff9e0);
    }
    .preview-content {
        white-space: pre-wrap;
        font-size: .95rem;
        line-height: 1.75;
        color: #2d2d2d;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                {{ isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
            </h4>
            <p class="text-muted small mb-0 mt-1">
                {{ isset($announcement) ? 'Perbarui isi dan masa berlaku pengumuman.' : 'Buat pengumuman baru yang akan tampil di banner website publik.' }}
            </p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">

        {{-- ── Kolom Kiri: Form ─────────────────────────────────────── --}}
        <div class="col-lg-8">
            <form action="{{ isset($announcement)
                                ? route('admin.announcements.update', $announcement)
                                : route('admin.announcements.store') }}"
                  method="POST" id="annForm">
                @csrf
                @if(isset($announcement)) @method('PUT') @endif

                {{-- Judul --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <label class="form-label fw-semibold">
                            Judul Pengumuman <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="titleInput"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $announcement->title ?? '') }}"
                               placeholder="Tulis judul yang jelas dan singkat"
                               maxlength="255"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-end">
                            <small class="text-muted" id="titleCounter">0/255</small>
                        </div>
                    </div>
                </div>

                {{-- Isi Pengumuman --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-text-paragraph me-2 text-primary"></i>
                            Isi Pengumuman <span class="text-danger">*</span>
                        </h6>
                    </div>
                    <div class="card-body pb-0">
                        {{-- Toolbar mode selector --}}
                        <div class="btn-group btn-group-sm mb-3" role="group">
                            <input type="radio" class="btn-check" name="editorMode"
                                   id="modeRich" value="rich" checked>
                            <label class="btn btn-outline-secondary" for="modeRich">
                                <i class="bi bi-type-bold me-1"></i>Rich Text
                            </label>
                            <input type="radio" class="btn-check" name="editorMode"
                                   id="modePlain" value="plain">
                            <label class="btn btn-outline-secondary" for="modePlain">
                                <i class="bi bi-code me-1"></i>Teks Biasa
                            </label>
                        </div>

                        {{-- Quill editor (rich mode) --}}
                        <div id="richEditorWrap">
                            <div id="quillEditor">{!! old('content_html', isset($announcement) ? nl2br(e($announcement->content)) : '') !!}</div>
                        </div>

                        {{-- Plain textarea (plain mode) --}}
                        <div id="plainEditorWrap" class="d-none">
                            <textarea name="content_plain"
                                      id="plainEditor"
                                      rows="9"
                                      class="form-control"
                                      placeholder="Tulis isi pengumuman...">{{ old('content', $announcement->content ?? '') }}</textarea>
                        </div>

                        {{-- Hidden field yang dikirim ke server --}}
                        <textarea name="content"
                                  id="contentHidden"
                                  class="d-none @error('content') is-invalid @enderror">{{ old('content', $announcement->content ?? '') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Gunakan mode <strong>Rich Text</strong> untuk memformat teks (tebal, daftar, dll.)
                            atau <strong>Teks Biasa</strong> untuk konten sederhana.
                        </small>
                    </div>
                </div>

                {{-- Masa Berlaku --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-range me-2 text-primary"></i>
                            Masa Berlaku
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="start_date"
                                       id="startDate"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', isset($announcement) ? $announcement->start_date->format('Y-m-d') : today()->format('Y-m-d')) }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Tanggal Berakhir
                                    <small class="text-muted fw-normal">(opsional)</small>
                                </label>
                                <input type="date"
                                       name="end_date"
                                       id="endDate"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', isset($announcement) ? $announcement->end_date?->format('Y-m-d') : '') }}">
                                <small class="text-muted">
                                    Kosongkan jika tidak ada batas akhir.
                                </small>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Info durasi --}}
                        <div class="mt-3 p-2 rounded bg-light border small text-muted" id="durationInfo" style="display:none;">
                            <i class="bi bi-clock-history me-1"></i>
                            <span id="durationText"></span>
                        </div>
                    </div>
                </div>

                {{-- Status aktif --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox"
                                   name="is_active" value="1"
                                   id="isActive"
                                   {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive">
                                Aktifkan pengumuman
                            </label>
                        </div>
                        <small class="text-muted ms-4 d-block mt-1">
                            Pengumuman aktif akan muncul di banner website jika dalam masa berlaku.
                        </small>
                    </div>
                </div>

                {{-- Tombol simpan --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        <i class="bi bi-save me-2"></i>
                        {{ isset($announcement) ? 'Simpan Perubahan' : 'Simpan Pengumuman' }}
                    </button>
                </div>

            </form>
        </div>

        {{-- ── Kolom Kanan: Preview & Info ──────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Preview tampilan publik --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-eye me-2 text-info"></i>
                        Preview Tampilan
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Begini tampilan pengumuman di banner website:
                    </p>
                    {{-- Simulasi banner --}}
                    <div class="preview-card rounded p-3 mb-3">
                        <div class="d-flex align-items-start gap-2">
                            <span class="badge bg-warning text-dark flex-shrink-0 mt-1">
                                <i class="bi bi-megaphone-fill"></i>
                            </span>
                            <div>
                                <div class="fw-semibold small" id="previewTitle" style="color:#2d2d2d;">
                                    {{ old('title', $announcement->title ?? 'Judul pengumuman...') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Preview isi --}}
                    <div class="mb-1">
                        <small class="text-muted fw-semibold text-uppercase">Isi Pengumuman:</small>
                    </div>
                    <div class="preview-content border rounded p-2 bg-light" id="previewContent">
                        {{ old('content', $announcement->content ?? 'Isi pengumuman akan tampil di sini...') }}
                    </div>
                </div>
            </div>

            {{-- Panduan --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb me-2 text-warning"></i>
                        Panduan
                    </h6>
                </div>
                <div class="card-body small text-muted">
                    <ul class="ps-3 mb-0">
                        <li class="mb-2">
                            <strong>Judul</strong> tampil di ticker banner — buat singkat dan jelas (maks. 100 karakter disarankan).
                        </li>
                        <li class="mb-2">
                            <strong>Isi</strong> tampil saat pengunjung menekan tombol <em>"Selengkapnya"</em>.
                        </li>
                        <li class="mb-2">
                            Atur <strong>Tanggal Mulai</strong> dan <strong>Berakhir</strong> agar pengumuman tampil otomatis sesuai jadwal.
                        </li>
                        <li>
                            Pengumuman dengan status <span class="badge bg-success">Aktif Tayang</span> langsung terlihat oleh pengunjung.
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// ── Quill rich text editor ────────────────────────────────────────────────
const quill = new Quill('#quillEditor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'blockquote'],
            ['clean']
        ]
    },
    placeholder: 'Tulis isi pengumuman di sini...'
});

// Mode editor: rich / plain
const modeRich  = document.getElementById('modeRich');
const modePlain = document.getElementById('modePlain');
const richWrap  = document.getElementById('richEditorWrap');
const plainWrap = document.getElementById('plainEditorWrap');
const plainTA   = document.getElementById('plainEditor');
const hiddenTA  = document.getElementById('contentHidden');

function syncToHidden() {
    if (modeRich.checked) {
        // Ambil teks murni dari Quill (bukan HTML) untuk disimpan di DB
        hiddenTA.value = quill.getText().trim();
    } else {
        hiddenTA.value = plainTA.value;
    }
}

modeRich.addEventListener('change', () => {
    if (modeRich.checked) {
        // Pindahkan isi plain ke Quill
        quill.setText(plainTA.value);
        richWrap.classList.remove('d-none');
        plainWrap.classList.add('d-none');
    }
});
modePlain.addEventListener('change', () => {
    if (modePlain.checked) {
        // Pindahkan isi Quill ke plain
        plainTA.value = quill.getText().trim();
        richWrap.classList.add('d-none');
        plainWrap.classList.remove('d-none');
    }
});

// ── Preview realtime ──────────────────────────────────────────────────────
const titleInput   = document.getElementById('titleInput');
const previewTitle = document.getElementById('previewTitle');
const previewCont  = document.getElementById('previewContent');

titleInput.addEventListener('input', () => {
    previewTitle.textContent = titleInput.value || 'Judul pengumuman...';
    document.getElementById('titleCounter').textContent =
        titleInput.value.length + '/255';
});
// Init counter
document.getElementById('titleCounter').textContent =
    titleInput.value.length + '/255';

quill.on('text-change', () => {
    previewCont.textContent = quill.getText().trim() || 'Isi pengumuman akan tampil di sini...';
});
plainTA.addEventListener('input', () => {
    previewCont.textContent = plainTA.value || 'Isi pengumuman akan tampil di sini...';
});

// ── Info durasi ───────────────────────────────────────────────────────────
const startDate    = document.getElementById('startDate');
const endDate      = document.getElementById('endDate');
const durationInfo = document.getElementById('durationInfo');
const durationText = document.getElementById('durationText');

function updateDuration() {
    if (!startDate.value) { durationInfo.style.display = 'none'; return; }
    const start = new Date(startDate.value);
    const today = new Date();
    today.setHours(0,0,0,0);

    if (endDate.value) {
        const end = new Date(endDate.value);
        const days = Math.round((end - start) / 86400000);
        if (days < 0) {
            durationText.textContent = '⚠ Tanggal berakhir sebelum tanggal mulai!';
        } else {
            durationText.textContent = `Pengumuman berlaku selama ${days} hari.`;
        }
    } else {
        if (start <= today) {
            durationText.textContent = 'Pengumuman berlaku tanpa batas akhir.';
        } else {
            const daysToStart = Math.round((start - today) / 86400000);
            durationText.textContent = `Pengumuman akan mulai ${daysToStart} hari lagi, tanpa batas akhir.`;
        }
    }
    durationInfo.style.display = 'block';
}
startDate.addEventListener('change', updateDuration);
endDate.addEventListener('change', updateDuration);
updateDuration();

// ── Sinkronisasi sebelum submit ───────────────────────────────────────────
document.getElementById('annForm').addEventListener('submit', function (e) {
    syncToHidden();
    if (!hiddenTA.value.trim()) {
        e.preventDefault();
        alert('Isi pengumuman tidak boleh kosong.');
        return;
    }
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
});
</script>
@endpush
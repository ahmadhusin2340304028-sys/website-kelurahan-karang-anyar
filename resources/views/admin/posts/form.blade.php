@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Berita' : 'Tambah Berita')
@section('breadcrumb', isset($post) ? 'Edit Berita' : 'Tambah Berita')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { min-height: 350px; font-size: 1rem; }
    .ql-editor { min-height: 350px; line-height: 1.75; }
    .ql-editor p { margin-bottom: .8rem; }
    .ql-editor img {
        display: block;
        max-width: 100%;
        height: auto;
        max-height: 520px;
        object-fit: contain;
        margin: .85rem auto;
        border-radius: 6px;
    }
    .image-preview-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px,1fr)); gap: 8px; }
    .image-preview-item { position: relative; }
    .image-preview-item img { width: 100%; height: 100px; object-fit: cover; border-radius: 6px; }
    .image-preview-item .remove-img { position: absolute; top: 4px; right: 4px; background: rgba(220,53,69,0.9);
        color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px;
        font-size: 12px; cursor: pointer; line-height: 1; display: flex; align-items: center; justify-content: center; }
</style>
@endpush

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">{{ isset($post) ? 'Edit Berita' : 'Tambah Berita' }}</h4>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <form action="{{ isset($post) ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        @if(isset($post)) @method('PUT') @endif

        <div class="row g-4">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Judul --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="titleInput"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   value="{{ old('title', $post->title ?? '') }}"
                                   placeholder="Masukkan judul berita yang menarik" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ringkasan / Excerpt</label>
                            <textarea name="excerpt" rows="2"
                                      class="form-control @error('excerpt') is-invalid @enderror"
                                      placeholder="Ringkasan singkat berita (tampil di daftar berita)">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                            @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Isi Berita --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Isi Berita <span class="text-danger">*</span></h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="quillEditor">{!! old('body', $post->body ?? '') !!}</div>
                        <textarea name="body" id="bodyInput" class="d-none">{{ old('body', $post->body ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Galeri Tambahan --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Foto Tambahan / Galeri</h6>
                    </div>
                    <div class="card-body">
                        {{-- Existing images --}}
                        @if(isset($post) && $post->images->isNotEmpty())
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">FOTO SAAT INI</label>
                            <div class="image-preview-grid" id="existingImages">
                                @foreach($post->images as $img)
                                <div class="image-preview-item" id="imgItem{{ $img->id }}">
                                    <img src="{{ $img->image_url }}" alt="">
                                    <button type="button" class="remove-img" onclick="deleteImage({{ $img->id }}, this)">×</button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <label class="form-label fw-semibold">Upload Foto Baru</label>
                        <input type="file" name="images[]" multiple class="form-control"
                               accept="image/jpeg,image/png,image/webp" id="imagesInput">
                        <small class="text-muted">JPG/PNG/WEBP, maks 5MB per foto. File akan dikompres otomatis.</small>
                        <div class="image-preview-grid mt-3" id="newImagePreviews"></div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-search me-2"></i>SEO Meta</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control"
                                   value="{{ old('meta_title', $post->meta_title ?? '') }}"
                                   placeholder="Kosongkan untuk menggunakan judul berita">
                            <small class="text-muted">Optimal: 50–60 karakter</small>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control"
                                      placeholder="Deskripsi singkat untuk mesin pencari">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                            <small class="text-muted">Optimal: 120–160 karakter</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                {{-- Publish --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Publikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="draft" {{ old('status', $post->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Dipublikasi</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tanggal Publish</label>
                            <input type="datetime-local" name="published_at" class="form-control"
                                   value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>{{ isset($post) ? 'Simpan Perubahan' : 'Simpan Berita' }}
                            </button>
                            @if(isset($post))
                            <a href="{{ route('news.show', $post->slug) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="bi bi-eye me-2"></i>Lihat Berita
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Kategori</h6>
                    </div>
                    <div class="card-body">
                        <select name="category_id" class="form-select">
                            <option value="">— Tanpa Kategori —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <a href="{{ route('admin.categories.create') }}" class="small text-primary text-decoration-none">
                                + Tambah kategori baru
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Thumbnail Berita</h6>
                    </div>
                    <div class="card-body">
                        @if(isset($post) && $post->thumbnail)
                        <img src="{{ $post->thumbnail_url }}" alt="" class="img-fluid rounded mb-2 w-100"
                             style="height:160px; object-fit:cover;" id="thumbPreview">
                        @else
                        <img src="" alt="" class="img-fluid rounded mb-2 w-100 d-none"
                             style="height:160px; object-fit:cover;" id="thumbPreview">
                        @endif
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp" id="thumbInput">
                        <small class="text-muted">JPG/PNG/WEBP, maks 5MB. Disimpan otomatis 800x500px.</small>
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const maxImageSize = 5 * 1024 * 1024;
const editorImageUploadUrl = @json(route('admin.posts.body-image'));
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Quill editor
const quill = new Quill('#quillEditor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [1,2,3,false] }],
            ['bold','italic','underline','strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link','image','blockquote'],
            [{ align: [] }],
            ['clean']
        ]
    }
});

async function uploadEditorImage(file) {
    if (!file || !file.type.startsWith('image/')) {
        throw new Error('File harus berupa gambar.');
    }

    if (file.size > maxImageSize) {
        throw new Error('Ukuran gambar maksimal 5MB.');
    }

    const formData = new FormData();
    formData.append('image', file);

    const response = await fetch(editorImageUploadUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    });

    if (!response.ok) {
        throw new Error('Gagal mengunggah gambar.');
    }

    return response.json();
}

function insertEditorImage(url) {
    const range = quill.getSelection(true);
    quill.insertEmbed(range.index, 'image', url, 'user');
    quill.setSelection(range.index + 1, 0, 'silent');
}

quill.getModule('toolbar').addHandler('image', () => {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/jpeg,image/png,image/webp';
    input.onchange = async () => {
        try {
            const data = await uploadEditorImage(input.files[0]);
            insertEditorImage(data.url);
        } catch (error) {
            alert(error.message);
        }
    };
    input.click();
});

quill.root.addEventListener('paste', async (event) => {
    const files = [...(event.clipboardData?.items || [])]
        .filter(item => item.kind === 'file' && item.type.startsWith('image/'))
        .map(item => item.getAsFile())
        .filter(Boolean);
    const hasText = (event.clipboardData?.getData('text/plain') || '').trim().length > 0;

    if (!files.length || hasText) return;

    event.preventDefault();

    for (const file of files) {
        try {
            const data = await uploadEditorImage(file);
            insertEditorImage(data.url);
        } catch (error) {
            alert(error.message);
        }
    }
});

quill.root.addEventListener('drop', async (event) => {
    const files = [...(event.dataTransfer?.files || [])]
        .filter(file => file.type.startsWith('image/'));

    if (!files.length) return;

    event.preventDefault();

    for (const file of files) {
        try {
            const data = await uploadEditorImage(file);
            insertEditorImage(data.url);
        } catch (error) {
            alert(error.message);
        }
    }
});

// Sync Quill to hidden textarea on submit
document.getElementById('postForm').addEventListener('submit', function() {
    quill.root.querySelectorAll('img').forEach(img => {
        img.removeAttribute('width');
        img.removeAttribute('height');
        img.removeAttribute('style');
        img.classList.add('post-content-image');
    });

    document.getElementById('bodyInput').value = quill.root.innerHTML;
});

// Thumbnail preview
document.getElementById('thumbInput').addEventListener('change', function() {
    const prev = document.getElementById('thumbPreview');
    if (this.files[0]) {
        if (this.files[0].size > maxImageSize) {
            alert('Ukuran thumbnail maksimal 5MB.');
            this.value = '';
            return;
        }

        prev.src = URL.createObjectURL(this.files[0]);
        prev.classList.remove('d-none');
    }
});

// New images preview
document.getElementById('imagesInput').addEventListener('change', function() {
    const container = document.getElementById('newImagePreviews');
    container.innerHTML = '';

    if ([...this.files].some(file => file.size > maxImageSize)) {
        alert('Ukuran setiap foto maksimal 5MB.');
        this.value = '';
        return;
    }

    [...this.files].forEach(file => {
        const div = document.createElement('div');
        div.className = 'image-preview-item';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        div.appendChild(img);
        container.appendChild(div);
    });
});

// Delete existing image via AJAX
async function deleteImage(id, btn) {
    if (!confirm('Hapus foto ini?')) return;
    try {
        const res = await fetch(`/admin/posts/images/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            document.getElementById(`imgItem${id}`).remove();
        }
    } catch(e) {
        alert('Gagal menghapus foto.');
    }
}
</script>
@endpush

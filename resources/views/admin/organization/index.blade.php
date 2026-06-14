@extends('layouts.admin')

@section('title', 'Struktur Organisasi')
@section('breadcrumb', 'Struktur Organisasi')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Struktur Organisasi</h4>
        <a href="{{ route('admin.organization.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Upload Bagan Baru
        </a>
    </div>

    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle me-2"></i>
        Hanya <strong>satu</strong> bagan struktur yang akan ditampilkan di website (yang berstatus Aktif).
        Upload bagan baru dan aktifkan untuk menggantikan yang lama.
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Preview</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Diunggah</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($structures as $structure)
                        <tr>
                            <td class="ps-3">
                                <img src="{{ $structure->image_url }}" alt="{{ $structure->title }}"
                                     class="rounded" style="height:50px; width:80px; object-fit:cover;"
                                     loading="lazy">
                            </td>
                            <td class="fw-semibold small">{{ $structure->title }}</td>
                            <td class="small text-muted">{{ Str::limit($structure->description, 50) ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $structure->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $structure->is_active ? 'Aktif (Ditampilkan)' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $structure->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.organization.edit', $structure) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.organization.destroy', $structure) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Hapus bagan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-diagram-3 fs-1 d-block mb-3"></i>
                                Belum ada bagan struktur organisasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($structures->hasPages())
        <div class="card-footer bg-white">{{ $structures->links() }}</div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Data ASN')
@section('breadcrumb', 'Data ASN')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Data ASN</h4>
        <a href="{{ route('admin.officials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Data ASN
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Urutan</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Level</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($officials as $official)
                        <tr>
                            <td class="ps-3 text-muted small text-center">{{ $official->sort_order }}</td>
                            <td>
                                <img src="{{ $official->photo_url }}" alt=""
                                     class="rounded-circle" style="width:42px; height:42px; object-fit:cover;">
                            </td>
                            <td class="fw-semibold small">{{ $official->name }}</td>
                            <td class="small">{{ $official->position }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $official->position_level_label }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $official->phone ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $official->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $official->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.officials.edit', $official) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.officials.destroy', $official) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Hapus data ASN ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted">Belum ada data ASN.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($officials->hasPages())
        <div class="card-footer bg-white">
            {{ $officials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

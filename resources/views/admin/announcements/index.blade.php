@extends('layouts.admin')

@section('title', 'Kelola Pengumuman')
@section('breadcrumb', 'Pengumuman')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Pengumuman</h4>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Pengumuman
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Judul</th>
                            <th>Mulai</th>
                            <th>Berakhir</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $ann)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold small">{{ Str::limit($ann->title, 60) }}</div>
                                <small class="text-muted">{{ Str::limit(strip_tags($ann->content), 60) }}</small>
                            </td>
                            <td class="small">{{ $ann->start_date->format('d/m/Y') }}</td>
                            <td class="small">{{ $ann->end_date ? $ann->end_date->format('d/m/Y') : '—' }}</td>
                            <td>
                                @php
                                    $isCurrentlyActive = $ann->is_active
                                        && $ann->start_date->lte(now())
                                        && (!$ann->end_date || $ann->end_date->gte(now()));
                                @endphp
                                <span class="badge {{ $isCurrentlyActive ? 'bg-success' : ($ann->is_active ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $isCurrentlyActive ? 'Aktif Tayang' : ($ann->is_active ? 'Terjadwal' : 'Nonaktif') }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.announcements.edit', $ann) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pengumuman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($announcements->hasPages())
        <div class="card-footer bg-white">{{ $announcements->links() }}</div>
        @endif
    </div>
</div>
@endsection

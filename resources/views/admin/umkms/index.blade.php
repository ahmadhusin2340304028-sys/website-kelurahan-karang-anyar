@extends('layouts.admin')

@section('title', 'Kelola UMKM')
@section('breadcrumb', 'UMKM')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola UMKM</h4>
        <div class="d-flex gap-2">
            <span class="badge bg-warning text-dark py-2 px-3">
                <i class="bi bi-hourglass me-1"></i>
                {{ \App\Models\Umkm::pending()->count() }} Menunggu
            </span>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="search" name="search" class="form-control"
                           placeholder="Cari nama UMKM atau pemilik..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('admin.umkms.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Foto</th>
                            <th>Nama UMKM</th>
                            <th>Pemilik</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Dikirim</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($umkms as $umkm)
                        <tr class="{{ $umkm->isPending() ? 'table-warning' : '' }}">
                            <td class="ps-3 small text-muted">{{ $umkms->firstItem() + $loop->index }}</td>
                            <td>
                                <img src="{{ $umkm->business_photo_url }}" alt=""
                                     class="rounded" style="width:50px; height:40px; object-fit:cover;">
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $umkm->business_name }}</div>
                                <small class="text-muted">{{ Str::limit($umkm->address, 40) }}</small>
                            </td>
                            <td class="small">{{ $umkm->owner_name }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $umkm->business_category }}</span>
                            </td>
                            <td>{!! $umkm->status_badge !!}</td>
                            <td class="small text-muted">{{ $umkm->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.umkms.show', $umkm) }}"
                                       class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($umkm->isPending())
                                    <form action="{{ route('admin.umkms.approve', $umkm) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"
                                                onclick="return confirm('Setujui UMKM ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" title="Tolak"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal"
                                            data-id="{{ $umkm->id }}" data-name="{{ $umkm->business_name }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endif
                                    <form action="{{ route('admin.umkms.destroy', $umkm) }}" method="POST"
                                          onsubmit="return confirm('Hapus data UMKM ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted">Belum ada data UMKM.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($umkms->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $umkms->firstItem() }}–{{ $umkms->lastItem() }} dari {{ $umkms->total() }}</small>
            {{ $umkms->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak UMKM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Anda akan menolak pengajuan: <strong id="rejectName"></strong></p>
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" rows="3" class="form-control" required
                              placeholder="Jelaskan alasan penolakan kepada pemilik UMKM..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>Tolak UMKM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('rejectModal').addEventListener('show.bs.modal', function(e) {
    const btn  = e.relatedTarget;
    const id   = btn.dataset.id;
    const name = btn.dataset.name;
    document.getElementById('rejectName').textContent = name;
    document.getElementById('rejectForm').action = `/admin/umkms/${id}/reject`;
});
</script>
@endpush
@endsection

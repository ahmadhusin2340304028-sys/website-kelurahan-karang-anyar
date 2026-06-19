@extends('layouts.admin')

@section('title', 'Kelola Pengumuman')
@section('breadcrumb', 'Pengumuman')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Pengumuman</h4>
            <p class="text-muted small mb-0 mt-1">
                Pengumuman aktif akan tampil di banner website publik.
            </p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Pengumuman
        </a>
    </div>

    {{-- Stats bar --}}
    @php
        $totalAktif = $announcements->where('is_active', true)->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="fs-3 fw-bold text-primary">{{ $announcements->total() }}</div>
                <div class="text-muted small">Total Pengumuman</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="fs-3 fw-bold text-success">{{ $totalAktif }}</div>
                <div class="text-muted small">Aktif (halaman ini)</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-admin table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width:40px">#</th>
                            <th>Judul & Isi</th>
                            <th style="width:120px">Mulai</th>
                            <th style="width:120px">Berakhir</th>
                            <th style="width:130px">Status</th>
                            <th class="text-end pe-3" style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $ann)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>

                            {{-- Judul & preview isi --}}
                            <td>
                                <div class="fw-semibold">{{ $ann->title }}</div>
                                <div class="text-muted small mt-1"
                                     style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;max-width:480px;">
                                    {{ $ann->content }}
                                </div>
                            </td>

                            <td class="small">{{ $ann->start_date->format('d/m/Y') }}</td>
                            <td class="small">{{ $ann->end_date ? $ann->end_date->format('d/m/Y') : '<span class="text-muted">—</span>' }}</td>

                            {{-- Status badge --}}
                            <td>
                                @php
                                    $isLive = $ann->is_active
                                        && $ann->start_date->lte(now())
                                        && (!$ann->end_date || $ann->end_date->gte(now()));
                                    $isScheduled = $ann->is_active && $ann->start_date->gt(now());
                                @endphp
                                @if($isLive)
                                    <span class="badge bg-success">
                                        <i class="bi bi-broadcast me-1"></i>Aktif Tayang
                                    </span>
                                @elseif($isScheduled)
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-clock me-1"></i>Terjadwal
                                    </span>
                                @elseif($ann->is_active)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass me-1"></i>Menunggu
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-pause-circle me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    {{-- Tombol lihat detail --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info"
                                            title="Lihat Detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal"
                                            data-id="{{ $ann->id }}"
                                            data-title="{{ $ann->title }}"
                                            data-content="{{ $ann->content }}"
                                            data-start="{{ $ann->start_date->translatedFormat('d F Y') }}"
                                            data-end="{{ $ann->end_date?->translatedFormat('d F Y') }}"
                                            data-active="{{ $ann->is_active ? '1' : '0' }}"
                                            data-status="{{ $isLive ? 'Aktif Tayang' : ($isScheduled ? 'Terjadwal' : ($ann->is_active ? 'Menunggu' : 'Nonaktif')) }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.announcements.edit', $ann) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $ann) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus pengumuman \`{{ addslashes($ann->title) }}\`?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-megaphone fs-1 d-block mb-3 opacity-25"></i>
                                Belum ada pengumuman. Klik <strong>Tambah Pengumuman</strong> untuk membuat yang pertama.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($announcements->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $announcements->firstItem() }}–{{ $announcements->lastItem() }}
                dari {{ $announcements->total() }} pengumuman
            </small>
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ══ Modal Detail Pengumuman ══════════════════════════════════════════════ --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header" style="background:linear-gradient(135deg,#1a4f8a,#123a6a);color:#fff;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-megaphone-fill fs-5"></i>
                    <h5 class="modal-title mb-0 fw-bold" id="detailModalTitle">Pengumuman</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="filter:invert(1)"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4">

                {{-- Status badge --}}
                <div class="mb-3" id="detailModalBadge"></div>

                {{-- Meta info --}}
                <div class="d-flex flex-wrap gap-3 mb-4 p-3 rounded"
                     style="background:#f8f9fa;font-size:.85rem;">
                    <span>
                        <i class="bi bi-calendar-check me-1 text-success"></i>
                        <strong>Mulai:</strong>
                        <span id="detailModalStart"></span>
                    </span>
                    <span id="detailModalEndWrap">
                        <i class="bi bi-calendar-x me-1 text-danger"></i>
                        <strong>Berakhir:</strong>
                        <span id="detailModalEnd"></span>
                    </span>
                </div>

                {{-- Isi --}}
                <div class="mb-1">
                    <label class="small fw-semibold text-muted text-uppercase">Isi Pengumuman</label>
                </div>
                <div id="detailModalContent"
                     style="font-size:.97rem;line-height:1.8;color:#2d2d2d;white-space:pre-wrap;
                            border-left:3px solid #1a4f8a;padding:12px 16px;background:#f0f4ff;
                            border-radius:0 6px 6px 0;">
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer justify-content-between">
                <a id="detailModalEditBtn" href="#" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Pengumuman
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('detailModal').addEventListener('show.bs.modal', function (e) {
    const btn     = e.relatedTarget;
    const id      = btn.dataset.id;
    const title   = btn.dataset.title;
    const content = btn.dataset.content;
    const start   = btn.dataset.start;
    const end     = btn.dataset.end;
    const active  = btn.dataset.active;
    const status  = btn.dataset.status;

    // Isi konten modal
    document.getElementById('detailModalTitle').textContent   = title;
    document.getElementById('detailModalContent').textContent = content;
    document.getElementById('detailModalStart').textContent   = start;

    // Tanggal berakhir
    const endWrap = document.getElementById('detailModalEndWrap');
    if (end && end !== '') {
        endWrap.classList.remove('d-none');
        document.getElementById('detailModalEnd').textContent = end;
    } else {
        endWrap.classList.add('d-none');
    }

    // Badge status
    const badgeMap = {
        'Aktif Tayang' : 'bg-success',
        'Terjadwal'    : 'bg-info text-dark',
        'Menunggu'     : 'bg-warning text-dark',
        'Nonaktif'     : 'bg-secondary',
    };
    const badgeClass = badgeMap[status] || 'bg-secondary';
    document.getElementById('detailModalBadge').innerHTML =
        `<span class="badge ${badgeClass} px-3 py-2">
            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;vertical-align:middle;"></i>
            ${status}
         </span>`;

    // Tombol edit
    document.getElementById('detailModalEditBtn').href =
        `{{ url('admin/announcements') }}/${id}/edit`;
});
</script>
@endpush
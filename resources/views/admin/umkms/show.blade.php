@extends('layouts.admin')

@section('title', 'Detail UMKM: ' . $umkm->business_name)
@section('breadcrumb', 'Detail UMKM')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Detail UMKM</h4>
        <a href="{{ route('admin.umkms.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">{{ $umkm->business_name }}</h6>
                    {!! $umkm->status_badge !!}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted fw-semibold d-block">NAMA PEMILIK</label>
                            <span>{{ $umkm->owner_name }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-semibold d-block">KATEGORI USAHA</label>
                            <span class="badge bg-primary">{{ $umkm->business_category }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-semibold d-block">NOMOR HP</label>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->phone) }}" target="_blank">
                                {{ $umkm->phone }}
                            </a>
                        </div>
                        @if($umkm->email)
                        <div class="col-md-6">
                            <label class="small text-muted fw-semibold d-block">EMAIL</label>
                            <a href="mailto:{{ $umkm->email }}">{{ $umkm->email }}</a>
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="small text-muted fw-semibold d-block">ALAMAT</label>
                            <span>{{ $umkm->address }}</span>
                        </div>
                        @if($umkm->maps_link)
                        <div class="col-12">
                            <label class="small text-muted fw-semibold d-block">LINK GOOGLE MAPS</label>
                            <a href="{{ $umkm->maps_link }}" target="_blank">{{ Str::limit($umkm->maps_link, 60) }}</a>
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="small text-muted fw-semibold d-block">DESKRIPSI USAHA</label>
                            <p class="mb-0">{{ $umkm->description }}</p>
                        </div>
                        @if($umkm->isRejected() && $umkm->rejection_reason)
                        <div class="col-12">
                            <div class="alert alert-danger mb-0">
                                <strong>Alasan Penolakan:</strong> {{ $umkm->rejection_reason }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Foto Usaha & Produk</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($umkm->business_photo)
                        <div class="col-md-4">
                            <label class="small text-muted fw-semibold d-block mb-1">FOTO USAHA</label>
                            <img src="{{ $umkm->business_photo_url }}" alt="" class="img-fluid rounded"
                                 style="height:180px; width:100%; object-fit:cover;">
                        </div>
                        @endif
                        @foreach($umkm->images as $img)
                        <div class="col-md-4">
                            <label class="small text-muted fw-semibold d-block mb-1">FOTO PRODUK {{ $loop->iteration }}</label>
                            <img src="{{ $img->image_url }}" alt="" class="img-fluid rounded"
                                 style="height:180px; width:100%; object-fit:cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Tindakan Moderasi</h6></div>
                <div class="card-body d-grid gap-2">
                    @if($umkm->isPending() || $umkm->isRejected())
                    <form action="{{ route('admin.umkms.approve', $umkm) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui UMKM ini?')">
                            <i class="bi bi-check-circle me-2"></i>Setujui UMKM
                        </button>
                    </form>
                    @endif

                    @if($umkm->isPending() || $umkm->isApproved())
                    <button type="button" class="btn btn-danger"
                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle me-2"></i>Tolak UMKM
                    </button>
                    @endif

                    <hr>
                    <form action="{{ route('admin.umkms.destroy', $umkm) }}" method="POST"
                          onsubmit="return confirm('Hapus permanen data UMKM ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Hapus Data
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Informasi</h6></div>
                <div class="card-body small">
                    <div class="mb-2"><span class="text-muted">Dikirim:</span> {{ $umkm->created_at->translatedFormat('d F Y, H:i') }}</div>
                    @if($umkm->approved_at)
                    <div class="mb-2"><span class="text-muted">Disetujui:</span> {{ $umkm->approved_at->translatedFormat('d F Y, H:i') }}</div>
                    @endif
                    <div><span class="text-muted">Foto Produk:</span> {{ $umkm->images->count() }} foto</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-danger">Tolak UMKM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.umkms.reject', $umkm) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" rows="3" class="form-control" required
                              placeholder="Jelaskan alasan penolakan...">{{ $umkm->rejection_reason }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak UMKM</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Kategori Berita</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-admin table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Jumlah Berita</th>
                        <th>Deskripsi</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $cat->name }}</td>
                        <td><code class="small">{{ $cat->slug }}</code></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">{{ $cat->posts_count }} berita</span>
                        </td>
                        <td class="small text-muted">{{ Str::limit($cat->description, 50) ?? '—' }}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus kategori {{ $cat->name }}? Berita terkait tidak akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        {{ $cat->posts_count > 0 ? 'disabled title=Kategori masih digunakan' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="card-footer bg-white">{{ $categories->links() }}</div>
        @endif
    </div>
</div>
@endsection

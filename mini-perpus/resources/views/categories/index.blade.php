@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Kategori Buku</h1>
            <p class="text-muted mb-0">Kelola kategori untuk pengelompokan buku.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-perpus">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card">
        <div class="card-header py-3">
            {{-- Form pencarian (GET) --}}
            <form action="{{ route('categories.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari nama kategori...">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-secondary">Cari</button>
                    @if ($search)
                        <a href="{{ route('categories.index') }}" class="btn btn-link text-decoration-none">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Buku</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-muted">{{ \Illuminate\Support\Str::limit($category->description ?? '-', 80) }}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill text-bg-light border">{{ $category->books_count }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline"
                                      data-confirm="Yakin ingin menghapus kategori &quot;{{ $category->name }}&quot;?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                {{ $search ? 'Kategori tidak ditemukan.' : 'Belum ada kategori. Silakan tambah kategori baru.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="card-footer bg-white pt-3">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection

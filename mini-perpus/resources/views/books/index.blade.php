@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Daftar Buku</h1>
            <p class="text-muted mb-0">Kelola data inventaris buku perpustakaan.</p>
        </div>
        <a href="{{ route('books.create') }}" class="btn btn-perpus">
            <i class="bi bi-plus-lg me-1"></i> Tambah Buku
        </a>
    </div>

    <div class="card">
        <div class="card-header py-3">
            {{-- Form pencarian & filter kategori (GET) --}}
            <form action="{{ route('books.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari judul atau penulis...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="category" class="form-select" aria-label="Filter kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-secondary flex-grow-1">Terapkan</button>
                    @if ($search || $categoryId)
                        <a href="{{ route('books.index') }}" class="btn btn-link text-decoration-none">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th class="text-center">Tahun Terbit</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $books->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            {{-- Nama kategori ditampilkan sebagai teks melalui relasi belongsTo --}}
                            <td><span class="badge badge-category">{{ $book->category->name }}</span></td>
                            <td class="text-center">{{ $book->published_year }}</td>
                            <td class="text-center">
                                @if ($book->stock > 0)
                                    {{ $book->stock }}
                                @else
                                    <span class="badge text-bg-danger">Habis</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline"
                                      data-confirm="Yakin ingin menghapus buku &quot;{{ $book->title }}&quot;?">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                {{ ($search || $categoryId) ? 'Buku tidak ditemukan.' : 'Belum ada data buku. Silakan tambah buku baru.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($books->hasPages())
            <div class="card-footer bg-white pt-3">
                {{ $books->links() }}
            </div>
        @endif
    </div>
@endsection

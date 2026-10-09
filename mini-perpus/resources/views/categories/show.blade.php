@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between gap-3">
            <div>
                <h1 class="h4 page-title mb-1">{{ $category->name }}</h1>
                <p class="text-muted mb-2">{{ $category->description ?? 'Tidak ada deskripsi.' }}</p>
                <span class="badge rounded-pill text-bg-light border">{{ $books->total() }} buku dalam kategori ini</span>
            </div>
            <div class="d-flex gap-2 align-items-start">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ route('books.create', ['category' => $category->id]) }}" class="btn btn-perpus">
                    <i class="bi bi-plus-lg"></i> Tambah Buku
                </a>
            </div>
        </div>
    </div>

    {{-- Daftar buku milik kategori ini: hasil relasi hasMany ($category->books()) --}}
    <div class="card">
        <div class="card-header py-3">
            <h2 class="h6 mb-0 fw-semibold">Daftar Buku dalam Kategori "{{ $category->name }}"</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th class="text-center">Tahun Terbit</th>
                        <th class="text-center">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $books->firstItem() + $loop->index }}</td>
                            <td><a href="{{ route('books.show', $book) }}" class="fw-semibold text-decoration-none">{{ $book->title }}</a></td>
                            <td>{{ $book->author }}</td>
                            <td class="text-center">{{ $book->published_year }}</td>
                            <td class="text-center">{{ $book->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada buku dalam kategori ini.</td>
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

    <a href="{{ route('categories.index') }}" class="btn btn-link px-0 mt-3 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar kategori
    </a>
@endsection

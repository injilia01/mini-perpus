@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <h1 class="h3 page-title mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Ringkasan inventaris buku perpustakaan fakultas.</p>
    </div>

    {{-- Kartu statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-tags"></i></span>
                    <div>
                        <div class="text-muted small">Total Kategori</div>
                        <div class="fs-3 fw-bold">{{ number_format($totalCategories, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-icon bg-success-subtle text-success"><i class="bi bi-journal-bookmark"></i></span>
                    <div>
                        <div class="text-muted small">Total Judul Buku</div>
                        <div class="fs-3 fw-bold">{{ number_format($totalBooks, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="stat-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-stack"></i></span>
                    <div>
                        <div class="text-muted small">Total Stok (Eksemplar)</div>
                        <div class="fs-3 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Jumlah buku per kategori --}}
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h2 class="h6 mb-0 fw-semibold">Buku per Kategori</h2>
                    <a href="{{ route('categories.index') }}" class="small text-decoration-none">Lihat semua</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('categories.show', $category) }}" class="text-decoration-none text-body">
                                {{ $category->name }}
                            </a>
                            <span class="badge rounded-pill text-bg-light border">{{ $category->books_count }} buku</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center py-4">Belum ada kategori.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Buku terbaru --}}
        <div class="col-12 col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h2 class="h6 mb-0 fw-semibold">Buku Terbaru Ditambahkan</h2>
                    <a href="{{ route('books.create') }}" class="btn btn-sm btn-perpus">
                        <i class="bi bi-plus-lg"></i> Tambah Buku
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th class="text-end">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestBooks as $book)
                                <tr>
                                    <td>
                                        <a href="{{ route('books.show', $book) }}" class="fw-semibold text-decoration-none">{{ $book->title }}</a>
                                        <div class="small text-muted">{{ $book->author }}</div>
                                    </td>
                                    <td><span class="badge badge-category">{{ $book->category->name }}</span></td>
                                    <td class="text-end">{{ $book->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

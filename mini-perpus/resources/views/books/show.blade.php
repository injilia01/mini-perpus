@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <h1 class="h5 page-title mb-0"><i class="bi bi-journal-bookmark me-1"></i> {{ $book->title }}</h1>
                    <div class="d-flex gap-2">
                        <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST"
                              data-confirm="Yakin ingin menghapus buku &quot;{{ $book->title }}&quot;?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">Judul</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $book->title }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Penulis</dt>
                        <dd class="col-sm-8">{{ $book->author }}</dd>

                        {{-- Relasi belongsTo: $book->category --}}
                        <dt class="col-sm-4 text-muted fw-normal">Kategori</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('categories.show', $book->category) }}" class="badge badge-category text-decoration-none">
                                {{ $book->category->name }}
                            </a>
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal">Tahun Terbit</dt>
                        <dd class="col-sm-8">{{ $book->published_year }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Stok</dt>
                        <dd class="col-sm-8">{{ $book->stock }} eksemplar</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Ditambahkan</dt>
                        <dd class="col-sm-8">{{ $book->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Terakhir Diubah</dt>
                        <dd class="col-sm-8 mb-0">{{ $book->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <a href="{{ route('books.index') }}" class="btn btn-link px-0 mt-3 text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke daftar buku
            </a>
        </div>
    </div>
@endsection

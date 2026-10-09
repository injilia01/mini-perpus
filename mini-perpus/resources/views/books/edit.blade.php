@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header py-3">
                    <h1 class="h5 page-title mb-0"><i class="bi bi-pencil-square me-1"></i> Edit Buku</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        @include('books.partials.form', ['book' => $book])

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-perpus"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

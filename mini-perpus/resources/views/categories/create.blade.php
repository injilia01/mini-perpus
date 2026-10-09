@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header py-3">
                    <h1 class="h5 page-title mb-0"><i class="bi bi-plus-circle me-1"></i> Tambah Kategori Baru</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" novalidate>
                        @csrf
                        @include('categories.partials.form', ['category' => null])

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-perpus"><i class="bi bi-save me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

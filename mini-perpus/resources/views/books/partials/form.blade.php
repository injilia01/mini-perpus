{{--
    Form buku yang dipakai bersama oleh halaman create & edit.
    Variabel: $book (null saat create), $categories (koleksi kategori dari database)
    Atribut "novalidate" pada <form> membuat validasi sepenuhnya dilakukan di server (Controller).
--}}
@if ($errors->any())
    <div class="alert alert-danger py-2">
        <i class="bi bi-exclamation-circle me-1"></i> Data belum valid, periksa kembali isian yang ditandai merah.
    </div>
@endif

@if ($categories->isEmpty())
    <div class="alert alert-warning">
        Belum ada kategori. <a href="{{ route('categories.create') }}" class="alert-link">Tambahkan kategori</a> terlebih dahulu sebelum menambah buku.
    </div>
@endif

{{-- Dropdown kategori: opsi ditarik dinamis dari tabel categories --}}
<div class="mb-3">
    <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected((string) old('category_id', $book->category_id ?? request('category')) === (string) $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="title" class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
    <input type="text" id="title" name="title"
           value="{{ old('title', $book->title ?? '') }}"
           class="form-control @error('title') is-invalid @enderror"
           placeholder="Contoh: Clean Code">
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="author" class="form-label fw-semibold">Penulis <span class="text-danger">*</span></label>
    <input type="text" id="author" name="author"
           value="{{ old('author', $book->author ?? '') }}"
           class="form-control @error('author') is-invalid @enderror"
           placeholder="Contoh: Robert C. Martin">
    @error('author')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-12 col-sm-6 mb-3">
        <label for="published_year" class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
        <input type="text" inputmode="numeric" id="published_year" name="published_year"
               value="{{ old('published_year', $book->published_year ?? '') }}"
               class="form-control @error('published_year') is-invalid @enderror"
               placeholder="Contoh: {{ now()->year }}">
        @error('published_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-sm-6 mb-4">
        <label for="stock" class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
        <input type="number" id="stock" name="stock" min="0"
               value="{{ old('stock', $book->stock ?? '') }}"
               class="form-control @error('stock') is-invalid @enderror"
               placeholder="Jumlah eksemplar">
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

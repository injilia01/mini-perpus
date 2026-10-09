{{--
    Form kategori yang dipakai bersama oleh halaman create & edit.
    Variabel: $category (null saat create, berisi model saat edit)
    Atribut "novalidate" sengaja dipakai agar validasi sepenuhnya dilakukan di server (Controller).
--}}
@if ($errors->any())
    <div class="alert alert-danger py-2">
        <i class="bi bi-exclamation-circle me-1"></i> Data belum valid, periksa kembali isian yang ditandai merah.
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name"
           value="{{ old('name', $category->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Contoh: Pemrograman" autofocus>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Maksimal 100 karakter dan tidak boleh sama dengan kategori lain.</div>
</div>

<div class="mb-4">
    <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
    <textarea id="description" name="description" rows="4"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="Keterangan singkat tentang kategori ini">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * [R] Menampilkan tabel daftar buku beserta nama kategorinya.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $categoryId = $request->query('category');

        $books = Book::with('category')  // eager loading relasi belongsTo (menghindari N+1 query)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->latest('id')  // data terbaru tampil paling atas
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories', 'search', 'categoryId'));
    }

    /**
     * [C] Menampilkan form tambah buku.
     * Daftar kategori untuk dropdown diambil secara dinamis dari database.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    /**
     * [C] Memvalidasi dan menyimpan buku baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $book = Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', "Buku \"{$book->title}\" berhasil ditambahkan.");
    }

    /**
     * [R] Menampilkan detail satu buku beserta kategorinya.
     */
    public function show(Book $book): View
    {
        $book->load('category');

        return view('books.show', compact('book'));
    }

    /**
     * [U] Menampilkan form edit buku.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * [U] Memvalidasi dan memperbarui seluruh data buku.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', "Buku \"{$book->title}\" berhasil diperbarui.");
    }

    /**
     * [D] Menghapus data buku dari sistem.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $title = $book->title;
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', "Buku \"{$title}\" berhasil dihapus.");
    }

    /**
     * Aturan validasi server-side untuk form buku (dipakai oleh store & update).
     *
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'category_id' => ['bail', 'required', 'integer', 'exists:categories,id'],
            'title' => ['bail', 'required', 'string', 'max:255'],
            'author' => ['bail', 'required', 'string', 'max:100'],
            'published_year' => ['bail', 'required', 'numeric', 'integer', 'min:1000', 'max:'.now()->year],
            'stock' => ['bail', 'required', 'integer', 'min:0'],
        ];
    }

    /**
     * Pesan error validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.integer' => 'Kategori yang dipilih tidak valid.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan di database.',
            'title.required' => 'Judul buku wajib diisi.',
            'title.string' => 'Judul buku harus berupa teks.',
            'title.max' => 'Judul buku maksimal :max karakter.',
            'author.required' => 'Nama penulis wajib diisi.',
            'author.string' => 'Nama penulis harus berupa teks.',
            'author.max' => 'Nama penulis maksimal :max karakter.',
            'published_year.required' => 'Tahun terbit wajib diisi.',
            'published_year.numeric' => 'Tahun terbit harus berupa angka.',
            'published_year.integer' => 'Tahun terbit harus berupa bilangan bulat (tanpa koma).',
            'published_year.min' => 'Tahun terbit minimal tahun :min.',
            'published_year.max' => 'Tahun terbit tidak boleh melebihi tahun :max.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari :min.',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Pesan error validasi dalam Bahasa Indonesia.
     *
     * @var array<string, string>
     */
    private array $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.string' => 'Nama kategori harus berupa teks.',
        'name.max' => 'Nama kategori maksimal :max karakter.',
        'name.unique' => 'Nama kategori ":input" sudah terdaftar, gunakan nama lain.',
        'description.string' => 'Deskripsi harus berupa teks.',
        'description.max' => 'Deskripsi maksimal :max karakter.',
    ];

    /**
     * [R] Menampilkan tabel daftar kategori.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $categories = Category::withCount('books')  // menghitung jumlah buku per kategori (books_count)
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * [C] Menampilkan form tambah kategori baru.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * [C] Memvalidasi dan menyimpan kategori baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['bail', 'required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], $this->messages);

        $category = Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil ditambahkan.");
    }

    /**
     * [R] Menampilkan detail kategori beserta daftar bukunya (relasi hasMany).
     */
    public function show(Category $category): View
    {
        $books = $category->books()->orderBy('title')->paginate(10);

        return view('categories.show', compact('category', 'books'));
    }

    /**
     * [U] Menampilkan form edit kategori.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * [U] Memvalidasi dan memperbarui data kategori.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'bail', 'required', 'string', 'max:100',
                // unique, tetapi abaikan data kategori ini sendiri agar nama lama tetap boleh dipakai
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ], $this->messages);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    /**
     * [D] Menghapus kategori.
     * Kategori yang masih memiliki buku TIDAK boleh dihapus (menjaga integritas relasi).
     */
    public function destroy(Category $category): RedirectResponse
    {
        $bookCount = $category->books()->count();

        // Lapisan 1: pengecekan di level aplikasi sebelum menghapus.
        if ($bookCount > 0) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih memiliki {$bookCount} buku. Pindahkan atau hapus buku tersebut terlebih dahulu.");
        }

        // Lapisan 2: jika foreign key constraint di database tetap menolak, tangkap error-nya.
        try {
            $category->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Kategori \"{$category->name}\" gagal dihapus karena masih digunakan oleh data buku.");
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil dihapus.");
    }
}

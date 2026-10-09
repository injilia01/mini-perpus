<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Pemrograman']);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->category->id,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'published_year' => 2008,
            'stock' => 5,
        ], $overrides);
    }

    public function test_daftar_buku_menampilkan_nama_kategori_bukan_id(): void
    {
        Book::create($this->validData());

        $this->get(route('books.index'))
            ->assertOk()
            ->assertSee('Clean Code')
            ->assertSee('Pemrograman');
    }

    public function test_form_tambah_buku_memuat_dropdown_kategori_dari_database(): void
    {
        Category::create(['name' => 'Jaringan Komputer']);

        $this->get(route('books.create'))
            ->assertOk()
            ->assertSee('name="category_id"', false)
            ->assertSee('Pemrograman')
            ->assertSee('Jaringan Komputer');
    }

    public function test_buku_baru_dapat_disimpan(): void
    {
        $this->post(route('books.store'), $this->validData())
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('books', ['title' => 'Clean Code', 'category_id' => $this->category->id]);
    }

    public function test_validasi_field_wajib(): void
    {
        $this->post(route('books.store'), [])
            ->assertSessionHasErrors(['category_id', 'title', 'author', 'published_year', 'stock']);

        $this->assertDatabaseCount('books', 0);
    }

    public function test_judul_maksimal_255_karakter(): void
    {
        $this->post(route('books.store'), $this->validData(['title' => str_repeat('a', 256)]))
            ->assertSessionHasErrors(['title' => 'Judul buku maksimal 255 karakter.']);
    }

    public function test_tahun_terbit_harus_angka(): void
    {
        $this->post(route('books.store'), $this->validData(['published_year' => 'dua ribu']))
            ->assertSessionHasErrors(['published_year' => 'Tahun terbit harus berupa angka.']);
    }

    public function test_kategori_harus_ada_di_database(): void
    {
        $this->post(route('books.store'), $this->validData(['category_id' => 999]))
            ->assertSessionHasErrors(['category_id' => 'Kategori yang dipilih tidak ditemukan di database.']);
    }

    public function test_stok_tidak_boleh_negatif(): void
    {
        $this->post(route('books.store'), $this->validData(['stock' => -1]))
            ->assertSessionHasErrors(['stock' => 'Stok tidak boleh kurang dari 0.']);
    }

    public function test_buku_dapat_diperbarui(): void
    {
        $book = Book::create($this->validData());
        $other = Category::create(['name' => 'Rekayasa Perangkat Lunak']);

        $this->put(route('books.update', $book), $this->validData([
            'category_id' => $other->id,
            'title' => 'Clean Code (Edisi Revisi)',
            'stock' => 10,
        ]))->assertRedirect(route('books.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'category_id' => $other->id,
            'title' => 'Clean Code (Edisi Revisi)',
            'stock' => 10,
        ]);
    }

    public function test_buku_dapat_dihapus(): void
    {
        $book = Book::create($this->validData());

        $this->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_model_book_menggunakan_belongs_to(): void
    {
        $book = Book::create($this->validData());

        $this->assertInstanceOf(BelongsTo::class, $book->category());
        $this->assertSame('Pemrograman', $book->category->name);
    }
}

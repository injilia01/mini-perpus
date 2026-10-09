<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(Category $category, array $attributes = []): Book
    {
        return $category->books()->create(array_merge([
            'title' => 'Buku Uji',
            'author' => 'Penulis Uji',
            'published_year' => 2020,
            'stock' => 1,
        ], $attributes));
    }

    public function test_halaman_daftar_kategori_menampilkan_data(): void
    {
        Category::create(['name' => 'Pemrograman']);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Pemrograman');
    }

    public function test_kategori_baru_dapat_disimpan(): void
    {
        $this->post(route('categories.store'), [
            'name' => 'Basis Data',
            'description' => 'Buku tentang database',
        ])->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Basis Data']);
    }

    public function test_nama_kategori_wajib_diisi(): void
    {
        $this->post(route('categories.store'), ['name' => ''])
            ->assertSessionHasErrors(['name' => 'Nama kategori wajib diisi.']);

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_nama_kategori_harus_unik(): void
    {
        Category::create(['name' => 'Pemrograman']);

        $this->post(route('categories.store'), ['name' => 'Pemrograman'])
            ->assertSessionHasErrors(['name' => 'Nama kategori "Pemrograman" sudah terdaftar, gunakan nama lain.']);

        $this->assertDatabaseCount('categories', 1);
    }

    public function test_update_boleh_memakai_nama_sendiri_tetapi_tidak_nama_kategori_lain(): void
    {
        $category = Category::create(['name' => 'Pemrograman']);
        Category::create(['name' => 'Jaringan']);

        // Nama tetap sama + deskripsi diubah -> berhasil
        $this->put(route('categories.update', $category), [
            'name' => 'Pemrograman',
            'description' => 'Deskripsi baru',
        ])->assertRedirect(route('categories.index'))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'description' => 'Deskripsi baru']);

        // Memakai nama kategori lain -> ditolak
        $this->put(route('categories.update', $category), ['name' => 'Jaringan'])
            ->assertSessionHasErrors('name');
    }

    public function test_kategori_yang_masih_memiliki_buku_tidak_dapat_dihapus(): void
    {
        $category = Category::create(['name' => 'Pemrograman']);
        $this->makeBook($category);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_kategori_tanpa_buku_dapat_dihapus(): void
    {
        $category = Category::create(['name' => 'Sistem Operasi']);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_detail_kategori_menampilkan_buku_miliknya(): void
    {
        $category = Category::create(['name' => 'Sastra']);
        $this->makeBook($category, ['title' => 'Bumi Manusia']);

        $this->get(route('categories.show', $category))
            ->assertOk()
            ->assertSee('Bumi Manusia');
    }

    public function test_model_category_menggunakan_has_many(): void
    {
        $category = Category::create(['name' => 'Sastra']);
        $this->makeBook($category);

        $this->assertInstanceOf(HasMany::class, $category->books());
        $this->assertCount(1, $category->books);
    }
}

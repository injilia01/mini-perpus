<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Mengisi data awal buku, dikelompokkan per kategori.
     * Tahun terbit yang dipakai adalah tahun terbit edisi pertama.
     */
    public function run(): void
    {
        $booksPerCategory = [
            'Pemrograman' => [
                ['title' => 'Clean Code: A Handbook of Agile Software Craftsmanship', 'author' => 'Robert C. Martin', 'published_year' => 2008, 'stock' => 5],
                ['title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt, David Thomas', 'published_year' => 1999, 'stock' => 3],
                ['title' => 'Refactoring: Improving the Design of Existing Code', 'author' => 'Martin Fowler', 'published_year' => 1999, 'stock' => 2],
                ['title' => 'Introduction to Algorithms', 'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest', 'published_year' => 1990, 'stock' => 4],
            ],
            'Basis Data' => [
                ['title' => 'Designing Data-Intensive Applications', 'author' => 'Martin Kleppmann', 'published_year' => 2017, 'stock' => 3],
                ['title' => 'SQL Antipatterns', 'author' => 'Bill Karwin', 'published_year' => 2010, 'stock' => 2],
            ],
            'Jaringan Komputer' => [
                ['title' => 'TCP/IP Illustrated, Volume 1: The Protocols', 'author' => 'W. Richard Stevens', 'published_year' => 1994, 'stock' => 2],
            ],
            'Kecerdasan Buatan' => [
                ['title' => 'Artificial Intelligence: A Modern Approach', 'author' => 'Stuart Russell, Peter Norvig', 'published_year' => 1995, 'stock' => 3],
                ['title' => 'Deep Learning', 'author' => 'Ian Goodfellow, Yoshua Bengio, Aaron Courville', 'published_year' => 2016, 'stock' => 2],
            ],
            'Sastra Indonesia' => [
                ['title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'published_year' => 1980, 'stock' => 6],
                ['title' => 'Ronggeng Dukuh Paruk', 'author' => 'Ahmad Tohari', 'published_year' => 1982, 'stock' => 4],
                ['title' => 'Cantik Itu Luka', 'author' => 'Eka Kurniawan', 'published_year' => 2002, 'stock' => 3],
                ['title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'published_year' => 2005, 'stock' => 7],
            ],
        ];

        foreach ($booksPerCategory as $categoryName => $books) {
            $category = Category::where('name', $categoryName)->firstOrFail();

            foreach ($books as $book) {
                // Membuat buku melalui relasi hasMany: category_id terisi otomatis.
                $category->books()->firstOrCreate(
                    ['title' => $book['title']],
                    $book
                );
            }
        }
    }
}

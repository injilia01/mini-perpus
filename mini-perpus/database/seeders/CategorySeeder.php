<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Mengisi data awal kategori buku.
     * firstOrCreate() dipakai agar seeder aman dijalankan berulang kali (tidak duplikat).
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pemrograman', 'description' => 'Buku tentang bahasa pemrograman, algoritma, dan praktik pengembangan perangkat lunak.'],
            ['name' => 'Basis Data', 'description' => 'Buku tentang perancangan, pengelolaan, dan optimasi basis data.'],
            ['name' => 'Jaringan Komputer', 'description' => 'Buku tentang protokol, arsitektur, dan administrasi jaringan komputer.'],
            ['name' => 'Kecerdasan Buatan', 'description' => 'Buku tentang kecerdasan buatan, machine learning, dan deep learning.'],
            ['name' => 'Sastra Indonesia', 'description' => 'Novel dan karya sastra penulis Indonesia.'],
            ['name' => 'Sistem Operasi', 'description' => null],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}

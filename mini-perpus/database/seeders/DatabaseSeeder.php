<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Jalankan dengan: php artisan migrate --seed  (atau php artisan db:seed)
     */
    public function run(): void
    {
        // Urutan penting: kategori dibuat lebih dulu karena buku membutuhkan category_id.
        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
        ]);
    }
}

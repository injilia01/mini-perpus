<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel "books" sesuai rancangan ERD.
     * Relasi: categories (1) ---- (N) books
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();                               // [PK] BIGINT UNSIGNED, Auto Increment

            // [FK] category_id -> categories.id
            // restrictOnDelete(): database MENOLAK penghapusan kategori yang masih dipakai oleh buku.
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('title', 255);               // VARCHAR(255)
            $table->string('author', 100);              // VARCHAR(100)
            $table->integer('published_year');          // INTEGER
            $table->integer('stock');                   // INTEGER
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

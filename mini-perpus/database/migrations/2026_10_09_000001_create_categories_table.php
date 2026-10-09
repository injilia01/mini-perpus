<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel "categories" sesuai rancangan ERD.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                               // [PK] BIGINT UNSIGNED, Auto Increment
            $table->string('name', 100)->unique();      // VARCHAR(100), nama kategori tidak boleh kembar
            $table->text('description')->nullable();    // TEXT, boleh kosong (Nullable)
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Membatalkan migration (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

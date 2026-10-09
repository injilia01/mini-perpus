<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'published_year',
        'stock',
    ];

    /**
     * Konversi tipe data otomatis saat atribut dibaca dari database.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
            'stock' => 'integer',
        ];
    }

    /**
     * Relasi kebalikan (inverse) dari One-to-Many:
     * setiap Buku hanya merujuk pada satu Kategori.
     * Contoh pemakaian: $book->category->name
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

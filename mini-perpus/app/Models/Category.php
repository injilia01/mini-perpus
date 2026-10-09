<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Kolom yang boleh diisi secara massal (mass assignment),
     * misalnya lewat Category::create($data) atau $category->update($data).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi One-to-Many: satu Kategori memiliki banyak Buku.
     * Contoh pemakaian: $category->books
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}

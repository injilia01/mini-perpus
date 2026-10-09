# Mini-Perpus — Sistem Manajemen Inventaris Perpustakaan

Project UTS Mata Kuliah **Framework Programming**
Program Studi S1 Teknik Informatika — Fakultas Teknik, Universitas Negeri Manado

| | |
|---|---|
| **Nama** | Claudio Lantang |
| **NIM** | 23210109 |
| **Video Demo** | _(tempel link YouTube / Google Drive)_ |

Aplikasi web untuk mendata **kategori buku** dan **daftar buku** perpustakaan fakultas. Dibangun dengan
Laravel menerapkan arsitektur **MVC**, **Eloquent ORM** (relasi One-to-Many), dan **Blade Template Engine**.

---

## Teknologi

- Laravel 12 (PHP **8.2 atau lebih baru**)
- MySQL / MariaDB
- Blade Template Engine (Template Inheritance)
- Bootstrap 5.3.8 + Bootstrap Icons 1.13.2 via CDN (jsDelivr)

## Fitur (Matriks Fungsionalitas)

| Modul | Create | Read | Update | Delete |
|---|---|---|---|---|
| **Kategori** | Form tambah kategori | Tabel daftar kategori (+ jumlah buku, pencarian) | Form edit kategori | Hapus kategori — **ditolak** dengan pesan error jika kategori masih memiliki buku |
| **Buku** | Form tambah buku dengan **dropdown kategori dinamis** dari database | Tabel daftar buku menampilkan **nama kategori (teks)**, pencarian & filter kategori | Form edit seluruh data buku | Hapus data buku |

Fitur tambahan: halaman Dashboard (ringkasan statistik), halaman detail kategori (daftar buku di dalamnya)
dan detail buku, pagination, notifikasi sukses/gagal, konfirmasi sebelum menghapus.

## Rancangan Database

```
categories                         books
-----------------------------      ------------------------------------------
id           BIGINT PK AI    1 ─── N id              BIGINT PK AI
name         VARCHAR(100)          category_id     BIGINT FK -> categories.id
description  TEXT NULL             title           VARCHAR(255)
created_at / updated_at            author          VARCHAR(100)
                                   published_year  INTEGER
                                   stock           INTEGER
                                   created_at / updated_at
```

Relasi **One-to-Many**: satu kategori memiliki banyak buku, setiap buku hanya merujuk pada satu kategori.
Foreign key memakai `restrictOnDelete()` sehingga database juga menolak penghapusan kategori yang masih dipakai.

## Struktur MVC

| Lapisan | File |
|---|---|
| **Migration** | `database/migrations/2026_10_09_000001_create_categories_table.php`, `database/migrations/2026_10_09_000002_create_books_table.php` |
| **Model** | `app/Models/Category.php` (`hasMany`), `app/Models/Book.php` (`belongsTo`) |
| **Controller** | `app/Http/Controllers/CategoryController.php`, `app/Http/Controllers/BookController.php`, `app/Http/Controllers/DashboardController.php` |
| **Route** | `routes/web.php` (`Route::resource`) |
| **View (Blade)** | `resources/views/layouts/app.blade.php` (layout induk), `resources/views/categories/*`, `resources/views/books/*`, `resources/views/dashboard.blade.php` |
| **Seeder** | `database/seeders/CategorySeeder.php`, `database/seeders/BookSeeder.php` |
| **Database dump** | `mini_perpus.sql` (root folder project) |

## Cara Menjalankan

1. **Install dependency**
   ```bash
   composer install
   ```
2. **Salin file environment dan buat application key**
   ```bash
   cp .env.example .env        # Windows (CMD): copy .env.example .env
   php artisan key:generate
   ```
3. **Siapkan database** — buat database kosong bernama `mini_perpus` (misalnya lewat phpMyAdmin),
   lalu sesuaikan `DB_USERNAME` / `DB_PASSWORD` di file `.env`. Pilih salah satu:
   - Import file `mini_perpus.sql` ke database `mini_perpus`, **atau**
   - Jalankan migration + data awal:
     ```bash
     php artisan migrate --seed
     ```
4. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```
   Buka <http://127.0.0.1:8000>

## Daftar Route

| Method | URI | Nama Route | Controller |
|---|---|---|---|
| GET | `/` | dashboard | DashboardController@index |
| GET | `/categories` | categories.index | CategoryController@index |
| GET | `/categories/create` | categories.create | CategoryController@create |
| POST | `/categories` | categories.store | CategoryController@store |
| GET | `/categories/{category}` | categories.show | CategoryController@show |
| GET | `/categories/{category}/edit` | categories.edit | CategoryController@edit |
| PUT/PATCH | `/categories/{category}` | categories.update | CategoryController@update |
| DELETE | `/categories/{category}` | categories.destroy | CategoryController@destroy |
| GET | `/books` | books.index | BookController@index |
| GET | `/books/create` | books.create | BookController@create |
| POST | `/books` | books.store | BookController@store |
| GET | `/books/{book}` | books.show | BookController@show |
| GET | `/books/{book}/edit` | books.edit | BookController@edit |
| PUT/PATCH | `/books/{book}` | books.update | BookController@update |
| DELETE | `/books/{book}` | books.destroy | BookController@destroy |

Cek langsung dengan `php artisan route:list`.

## Validasi Server-Side (di Controller)

| Field | Aturan |
|---|---|
| `categories.name` | `required`, `string`, `max:100`, `unique` (saat update, nama milik kategori itu sendiri diabaikan) |
| `categories.description` | `nullable`, `string`, `max:1000` |
| `books.category_id` | `required`, `integer`, `exists:categories,id` |
| `books.title` | `required`, `string`, `max:255` |
| `books.author` | `required`, `string`, `max:100` |
| `books.published_year` | `required`, `numeric`, `integer`, `min:1000`, `max:<tahun berjalan>` |
| `books.stock` | `required`, `integer`, `min:0` |

Pesan error berbahasa Indonesia ditampilkan di bawah setiap input dengan directive `@error`.
Form memakai atribut `novalidate` agar validasi benar-benar dilakukan oleh server, bukan oleh browser.

## Pengujian Otomatis

```bash
php artisan test
```

Tersedia 22 test (folder `tests/Feature`) yang menguji CRUD kategori & buku, aturan validasi,
penolakan hapus kategori yang masih memiliki buku, serta relasi `hasMany` / `belongsTo`.

## Kesesuaian dengan Spesifikasi UTS

- [x] Routing terstruktur dengan `Route::resource`, tanpa query database di dalam route
- [x] `CategoryController` dan `BookController` terpisah
- [x] Tabel dibuat dengan Migration, tanpa raw SQL (`DB::statement`), seluruh query memakai Eloquent
- [x] `Category` → `hasMany()`, `Book` → `belongsTo()`
- [x] Template Inheritance Blade: `@extends('layouts.app')`, `@section`, `@yield`
- [x] UI rapi dan responsif dengan Bootstrap via CDN
- [x] Validasi di Controller + pesan error dengan `@error`
- [x] Database dump `.sql` di root folder project

<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Mini-Perpus
|--------------------------------------------------------------------------
| Route hanya meneruskan request ke Controller (tidak ada query database di sini).
| Route::resource otomatis membuat 7 rute CRUD:
| index, create, store, show, edit, update, destroy.
| Cek daftar lengkapnya dengan: php artisan route:list
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('categories', CategoryController::class);
Route::resource('books', BookController::class);

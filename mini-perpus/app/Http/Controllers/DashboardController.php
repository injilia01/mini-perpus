<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Halaman ringkasan inventaris perpustakaan.
     */
    public function index(): View
    {
        $totalCategories = Category::count();
        $totalBooks = Book::count();
        $totalStock = (int) Book::sum('stock');

        $categories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->orderBy('name')
            ->get();

        $latestBooks = Book::with('category')->latest('id')->take(5)->get();

        return view('dashboard', compact(
            'totalCategories',
            'totalBooks',
            'totalStock',
            'categories',
            'latestBooks'
        ));
    }
}

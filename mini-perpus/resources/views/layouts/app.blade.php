<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name', 'Mini-Perpus') }}</title>

    {{-- CSS Framework via CDN: Bootstrap 5 + Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css"
          integrity="sha384-nWN3KqaaVcbEsCjLSV+2jMjh62Iny/+mN3srM8TZeM8N6aj7eDl5wwYggjAJ4/sJ"
          crossorigin="anonymous">

    <style>
        :root {
            --perpus-navy: #10224a;
            --perpus-navy-soft: #1d3466;
            --perpus-gold: #e2b43b;
            --perpus-bg: #f3f5f9;
        }
        body { background-color: var(--perpus-bg); }
        .navbar-perpus { background-color: var(--perpus-navy); }
        .navbar-perpus .navbar-brand { font-weight: 700; letter-spacing: .3px; }
        .navbar-perpus .navbar-brand i { color: var(--perpus-gold); }
        .navbar-perpus .nav-link { color: rgba(255, 255, 255, .75); }
        .navbar-perpus .nav-link:hover,
        .navbar-perpus .nav-link.active { color: #fff; }
        .navbar-perpus .nav-link.active { box-shadow: inset 0 -3px 0 var(--perpus-gold); }
        .page-title { color: var(--perpus-navy); font-weight: 700; }
        .card { border: 0; box-shadow: 0 1px 3px rgba(16, 34, 74, .08); }
        .card-header { background-color: #fff; border-bottom: 1px solid #e8ebf2; }
        .table thead th { font-size: .8rem; text-transform: uppercase; letter-spacing: .4px;
                          color: #5b6478; background-color: #f8f9fc; white-space: nowrap; }
        .btn-perpus { background-color: var(--perpus-navy); border-color: var(--perpus-navy); color: #fff; }
        .btn-perpus:hover, .btn-perpus:focus { background-color: var(--perpus-navy-soft);
                          border-color: var(--perpus-navy-soft); color: #fff; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: inline-flex;
                     align-items: center; justify-content: center; font-size: 1.4rem; }
        .badge-category { background-color: #e9eefb; color: var(--perpus-navy); font-weight: 600; }
        .text-gold { color: var(--perpus-gold); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Navigasi utama (responsif: menjadi menu hamburger di layar kecil) --}}
    <nav class="navbar navbar-expand-md navbar-dark navbar-perpus">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-book-half me-1"></i> Mini-Perpus
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                           href="{{ route('categories.index') }}"><i class="bi bi-tags me-1"></i> Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('books.*') ? 'active' : '' }}"
                           href="{{ route('books.index') }}"><i class="bi bi-journal-bookmark me-1"></i> Buku</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">

        {{-- Pesan notifikasi (flash message) dari Controller --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-start" role="alert">
                <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- Konten utama setiap halaman --}}
        @yield('content')
    </main>

    <footer class="py-3 bg-white border-top mt-auto">
        <div class="container small text-muted d-flex flex-column flex-sm-row justify-content-between gap-1">
            <span>&copy; {{ date('Y') }} Mini-Perpus &middot; Sistem Manajemen Inventaris Perpustakaan Fakultas</span>
            <span>Dibangun dengan Laravel {{ app()->version() }}</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
    <script>
        // Konfirmasi sebelum menghapus data: berlaku untuk semua <form data-confirm="...">
        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (! window.confirm(form.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

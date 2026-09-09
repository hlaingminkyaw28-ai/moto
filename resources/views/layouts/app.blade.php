<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Moto Service WebApp') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/moto.css') }}" rel="stylesheet">
</head>
<body>
@php($nav = [
    ['dashboard', 'Dashboard', 'bi-speedometer2'],
    ['customers.index', 'Customers', 'bi-people'],
    ['motorcycles.index', 'Motorcycles', 'bi-bicycle'],
    ['items.index', 'Items', 'bi-box-seam'],
    ['invoices.index', 'Invoices', 'bi-receipt'],
    ['reports.index', 'Reports', 'bi-graph-up'],
    ['settings.edit', 'Settings', 'bi-gear'],
])
@php($mobileNav = [
    ['dashboard', 'Dashboard', 'bi-speedometer2'],
    ['customers.index', 'Customers', 'bi-people'],
    ['invoices.index', 'Invoices', 'bi-receipt'],
    ['reports.index', 'Reports', 'bi-graph-up'],
    ['settings.edit', 'Settings', 'bi-gear'],
])

<div class="app-shell">
    <nav class="navbar navbar-dark shadow-sm app-topbar no-print">
        <div class="container-fluid py-2">
            <a class="navbar-brand fw-bold app-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-motorcycle me-2"></i>Moto Service WebApp
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                @auth
                    <div class="text-white-50 small d-none d-md-block">Welcome, {{ auth()->user()->name }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="desktop-only">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                @endauth
                <button class="btn btn-outline-light btn-sm mobile-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-3 app-content">
        <div class="row g-3">
            <aside class="col-lg-2 desktop-only no-print">
                <div class="sidebar app-sidebar glass-card p-3">
                    <div class="fw-bold mb-3">Main Menu</div>
                    <div class="d-grid gap-2">
                        @foreach($nav as [$routeName, $label, $icon])
                            <a class="sidebar-link {{ request()->routeIs($routeName) ? 'active' : '' }}" href="{{ route($routeName) }}">
                                <i class="bi {{ $icon }}"></i><span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <main class="col-12 col-lg-10">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm glass-card border-0">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm glass-card border-0">
                        <div class="fw-semibold mb-1">Please fix the following:</div>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            @auth
                <div class="glass-card p-3 mb-3">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-soft small mb-3">Admin account</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100">Logout</button>
                    </form>
                </div>
            @endauth
            <div class="d-grid gap-2">
                @foreach($nav as [$routeName, $label, $icon])
                <a class="sidebar-link sidebar-link-light {{ request()->routeIs($routeName) ? 'active' : '' }}" href="{{ route($routeName) }}">
                    <i class="bi {{ $icon }}"></i><span>{{ $label }}</span>
                </a>
            @endforeach
            </div>
        </div>
    </div>

    <div class="mobile-bottom-nav d-flex gap-1 no-print">
    @foreach($mobileNav as [$routeName, $label, $icon])
        <a class="{{ request()->routeIs($routeName) ? 'active' : '' }}" href="{{ route($routeName) }}">
            <i class="bi {{ $icon }}"></i>
            <span>{{ $label }}</span>
        </a>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

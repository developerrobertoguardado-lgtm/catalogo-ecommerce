<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>document.documentElement.dataset.theme = localStorage.getItem('ecommerce-theme') === 'dark' ? 'dark' : 'light';</script>
    <title>{{ $title ?? 'Admin' }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
</head>
<body class="admin-shell" style="{{ \App\Models\StoreSetting::brandCss() }}">
    @php
        $tienda = \App\Models\StoreSetting::current();
        $navItems = [
            ['route' => 'admin.productos.index', 'pattern' => 'admin.productos.*', 'icon' => 'box', 'label' => 'Productos'],
            ['route' => 'admin.categorias.index', 'pattern' => 'admin.categorias.*', 'icon' => 'tag', 'label' => 'Categorías'],
            ['route' => 'admin.pedidos.index', 'pattern' => 'admin.pedidos.*', 'icon' => 'receipt', 'label' => 'Pedidos'],
            ['route' => 'admin.configuracion.edit', 'pattern' => 'admin.configuracion.*', 'icon' => 'settings', 'label' => 'Configuración'],
        ];
    @endphp

    <nav class="navbar admin-topbar sticky-top">
        <div class="container-fluid">
            <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-label="Abrir menú">
                <x-icon name="menu" class="icon-md" />
            </button>

            <span class="navbar-brand mb-0 d-flex align-items-center gap-2 admin-brand">
                <span class="avatar-circle brand-mark">
                    {{ \Illuminate\Support\Str::substr(config('app.name'), 0, 1) }}
                </span>
                @if ($tienda->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($tienda->logo_path) }}" alt="{{ $tienda->store_name }}" class="store-logo admin-store-logo">
                @else
                    {{ $tienda->store_name }}
                @endif
            </span>

            <div class="d-flex align-items-center gap-2 ms-auto">
                <x-theme-toggle />
                <div class="dropdown">
                    <button class="btn user-menu dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <span class="avatar-circle user-avatar">
                            {{ \Illuminate\Support\Str::substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </span>
                        <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                    <x-icon name="logout" class="icon-sm" />
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start admin-sidebar" tabindex="-1" id="sidebarOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">{{ config('app.name') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav nav-pills flex-column p-3 admin-nav">
                @foreach ($navItems as $item)
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                            <x-icon :name="$item['icon']" class="icon-sm" />
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 d-none d-lg-block admin-sidebar min-vh-100 p-0">
                <ul class="nav nav-pills flex-column p-3 sticky-top admin-nav" style="top: 4rem;">
                    @foreach ($navItems as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                                <x-icon :name="$item['icon']" class="icon-sm" />
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <main class="col-lg-10 ms-sm-auto px-4 py-4">
                <x-alertas />

                <h1 class="h3 fw-bold mb-4">{{ $title ?? 'Admin' }}</h1>

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

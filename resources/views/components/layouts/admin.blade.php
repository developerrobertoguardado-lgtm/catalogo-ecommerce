<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    @php
        $navItems = [
            ['route' => 'admin.productos.index', 'pattern' => 'admin.productos.*', 'icon' => 'box', 'label' => 'Productos'],
            ['route' => 'admin.categorias.index', 'pattern' => 'admin.categorias.*', 'icon' => 'tag', 'label' => 'Categorías'],
            ['route' => 'admin.pedidos.index', 'pattern' => 'admin.pedidos.*', 'icon' => 'receipt', 'label' => 'Pedidos'],
            ['route' => 'admin.configuracion.edit', 'pattern' => 'admin.configuracion.*', 'icon' => 'settings', 'label' => 'Configuración'],
        ];
    @endphp

    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                <x-icon name="menu" class="icon-md" />
            </button>

            <span class="navbar-brand mb-0 d-flex align-items-center gap-2">
                <span class="avatar-circle bg-primary text-white" style="width:2rem;height:2rem;font-size:.85rem;">
                    {{ \Illuminate\Support\Str::substr(config('app.name'), 0, 1) }}
                </span>
                {{ config('app.name') }}
            </span>

            <div class="dropdown ms-auto">
                <button class="btn btn-dark dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <span class="avatar-circle bg-primary-subtle text-primary-emphasis">
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
    </nav>

    <div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="sidebarOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">{{ config('app.name') }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav nav-pills flex-column p-2">
                @foreach ($navItems as $item)
                    <li class="nav-item">
                        <a href="{{ route($item['route']) }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($item['pattern']) ? 'active' : 'text-white-50' }}">
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
            <div class="col-lg-2 d-none d-lg-block bg-dark min-vh-100 p-0">
                <ul class="nav nav-pills flex-column p-2 sticky-top" style="top: 4rem;">
                    @foreach ($navItems as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($item['pattern']) ? 'active' : 'text-white-50' }}">
                                <x-icon :name="$item['icon']" class="icon-sm" />
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <main class="col-lg-10 ms-sm-auto px-4 py-4">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <h1 class="h3 fw-bold mb-4">{{ $title ?? 'Admin' }}</h1>

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

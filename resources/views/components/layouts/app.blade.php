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
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="{{ \App\Models\StoreSetting::brandCss() }}">
    @php $tienda = \App\Models\StoreSetting::current(); @endphp

    <header class="site-header">
        <div class="site-header-bar container">
            <a class="navbar-brand m-0 p-0" href="{{ route('catalogo.index') }}">
                @if ($tienda->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($tienda->logo_path) }}" alt="{{ $tienda->store_name }}" class="store-logo">
                @else
                    <span class="site-brand-name">{{ $tienda->store_name }}</span>
                @endif
            </a>

            <form method="GET" action="{{ route('catalogo.index') }}" class="site-search flex-grow-1" role="search">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><x-icon name="search" class="icon-sm" /></span>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ request('nombre') }}"
                        placeholder="Buscar producto..."
                        class="form-control border-start-0"
                        aria-label="Buscar producto"
                    >
                </div>
            </form>

            <x-theme-toggle />
        </div>
    </header>

    <main>
        <x-alertas />
        {{ $slot }}
    </main>

    <footer class="site-footer">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-sm-4">
                    <h6 class="site-footer-title">{{ $tienda->store_name }}</h6>
                    <p class="text-muted small mb-0">Catálogo online con pedidos directos por WhatsApp.</p>
                </div>

                <div class="col-sm-4">
                    <h6 class="site-footer-label">Categorías</h6>
                    <ul class="list-unstyled small mb-0">
                        @foreach (($footerCategorias ?? \App\Models\Category::orderBy('name')->limit(6)->get()) as $categoria)
                            <li class="mb-1">
                                <a href="{{ route('catalogo.categoria', $categoria) }}" class="text-decoration-none site-footer-link">
                                    {{ $categoria->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-sm-4">
                    <h6 class="site-footer-label">Contacto</h6>
                    @if ($tienda->whatsapp_number)
                        <a
                            href="https://wa.me/{{ preg_replace('/\D/', '', $tienda->whatsapp_number) }}"
                            target="_blank" rel="noopener"
                            class="d-flex align-items-center gap-2 text-decoration-none site-footer-link small"
                        >
                            <x-icon name="whatsapp" class="icon-sm" />
                            {{ $tienda->whatsapp_number }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="site-footer-bottom py-3 text-center text-muted small">
            &copy; {{ now()->year }} {{ $tienda->store_name }}
        </div>
    </footer>
</body>
</html>

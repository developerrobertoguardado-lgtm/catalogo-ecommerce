<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php $tienda = \App\Models\StoreSetting::current(); @endphp

    <div class="bg-dark text-center text-white-50 small py-1">
        Pedidos directos por WhatsApp · Sin registro, sin pasarela de pago
    </div>

    <nav class="navbar navbar-expand navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('catalogo.index') }}">{{ $tienda->store_name }}</a>
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a class="nav-link" href="{{ route('catalogo.index') }}">Catálogo</a>
                @if ($tienda->whatsapp_number)
                    <a
                        href="https://wa.me/{{ preg_replace('/\D/', '', $tienda->whatsapp_number) }}"
                        target="_blank" rel="noopener"
                        class="btn btn-success btn-sm d-flex align-items-center gap-2"
                    >
                        <x-icon name="whatsapp" class="icon-sm" />
                        WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-light border-top mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-sm-4">
                    <h6 class="fw-bold">{{ $tienda->store_name }}</h6>
                    <p class="text-muted small mb-0">Catálogo online con pedidos directos por WhatsApp.</p>
                </div>

                <div class="col-sm-4">
                    <h6 class="text-uppercase text-muted small fw-bold">Categorías</h6>
                    <ul class="list-unstyled small">
                        @foreach (\App\Models\Category::orderBy('name')->limit(6)->get() as $categoria)
                            <li class="mb-1">
                                <a href="{{ route('catalogo.categoria', $categoria) }}" class="text-decoration-none text-muted">
                                    {{ $categoria->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-sm-4">
                    <h6 class="text-uppercase text-muted small fw-bold">Contacto</h6>
                    @if ($tienda->whatsapp_number)
                        <a
                            href="https://wa.me/{{ preg_replace('/\D/', '', $tienda->whatsapp_number) }}"
                            target="_blank" rel="noopener"
                            class="d-flex align-items-center gap-2 text-decoration-none text-muted small"
                        >
                            <x-icon name="whatsapp" class="icon-sm text-success" />
                            {{ $tienda->whatsapp_number }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-top py-3 text-center text-muted small">
            &copy; {{ now()->year }} {{ $tienda->store_name }}
        </div>
    </footer>
</body>
</html>

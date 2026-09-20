@props(['producto', 'currency' => null])

@php
    $agotado = $producto->stock === 0;
    $pocasUnidades = !$agotado && $producto->stock < 5;
    $currency ??= \App\Models\StoreSetting::current()->currency;
@endphp

<a href="{{ route('catalogo.show', $producto) }}" class="card h-100 text-decoration-none text-reset border-0 product-card d-flex flex-column">
    <div class="position-relative overflow-hidden product-card-media">
        @if ($producto->primaryImage)
            <img
                src="{{ \Illuminate\Support\Facades\Storage::url($producto->primaryImage->path) }}"
                alt="{{ $producto->name }}"
                class="card-img-top product-card-img"
                loading="lazy"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
            >
            <div class="d-none align-items-center justify-content-center text-muted product-card-img product-card-fallback">
                <x-icon name="image" class="icon-md" />
            </div>
        @else
            <div class="d-flex align-items-center justify-content-center text-muted product-card-img product-card-fallback">
                <x-icon name="image" class="icon-md" />
            </div>
        @endif

        @if ($agotado)
            <span class="product-badge product-badge-dark position-absolute top-0 start-0 m-2">Agotado</span>
        @elseif ($pocasUnidades)
            <span class="product-badge product-badge-warning position-absolute top-0 start-0 m-2">Últimas {{ $producto->stock }} unidades</span>
        @endif
    </div>

    <div class="card-body d-flex flex-column flex-grow-1">
        <p class="product-card-category text-uppercase small text-muted fw-semibold mb-1">{{ $producto->category->name }}</p>
        <h3 class="product-card-name fw-bold mb-1">{{ $producto->name }}</h3>
        <p class="product-card-desc small text-muted mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($producto->description), 44) }}</p>

        <p class="product-card-price fw-bold mb-3 mt-auto">
            {{ $producto->price }} <small class="product-card-currency text-muted fw-normal">{{ $currency }}</small>
        </p>

        <span class="product-cta btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100 {{ $agotado ? 'is-soldout disabled' : '' }}">
            <x-icon name="cart" class="icon-sm" />
            {{ $agotado ? 'Agotado' : 'Agregar' }}
        </span>
    </div>
</a>

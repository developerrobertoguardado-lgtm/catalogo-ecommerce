@props(['producto'])

<a href="{{ route('catalogo.show', $producto) }}" class="card h-100 text-decoration-none text-reset hover-shadow">
    <div class="position-relative overflow-hidden bg-light">
        @if ($producto->primaryImage)
            <img
                src="{{ \Illuminate\Support\Facades\Storage::url($producto->primaryImage->path) }}"
                alt="{{ $producto->name }}"
                class="card-img-top product-card-img"
                loading="lazy"
            >
        @else
            <div class="d-flex align-items-center justify-content-center text-muted product-card-img">Sin foto</div>
        @endif

        @if ($producto->stock === 0)
            <span class="badge text-bg-dark position-absolute top-0 start-0 m-2">Agotado</span>
        @elseif ($producto->stock < 5)
            <span class="badge text-bg-warning position-absolute top-0 start-0 m-2">Últimas unidades</span>
        @endif
    </div>
    <div class="card-body">
        <p class="text-uppercase small text-primary fw-semibold mb-1">{{ $producto->category->name }}</p>
        <h3 class="h6 text-truncate mb-1">{{ $producto->name }}</h3>
        <p class="fw-semibold mb-0">
            {{ $producto->price }} <small class="text-muted fw-normal">{{ \App\Models\StoreSetting::current()->currency }}</small>
        </p>
    </div>
</a>

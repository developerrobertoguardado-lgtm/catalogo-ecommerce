@props(['imagenes'])

@php $carouselId = 'carousel-producto-'.uniqid(); @endphp

<div>
    <div id="{{ $carouselId }}" class="carousel slide">
        <div class="carousel-inner rounded border bg-light">
            @forelse ($imagenes as $index => $imagen)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($imagen->path) }}"
                        alt="Foto {{ $index + 1 }}"
                        class="d-block w-100 product-card-img"
                    >
                </div>
            @empty
                <div class="carousel-item active">
                    <div class="d-flex align-items-center justify-content-center text-muted product-card-img">Sin fotos</div>
                </div>
            @endforelse
        </div>

        @if ($imagenes->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        @endif
    </div>

    @if ($imagenes->count() > 1)
        <div class="d-flex gap-2 mt-3">
            @foreach ($imagenes as $index => $imagen)
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($imagen->path) }}"
                    alt="Miniatura {{ $index + 1 }}"
                    data-bs-target="#{{ $carouselId }}"
                    data-bs-slide-to="{{ $index }}"
                    class="thumbnail-indicator {{ $index === 0 ? 'active' : '' }}"
                >
            @endforeach
        </div>

        <script>
            (function () {
                const carouselEl = document.getElementById('{{ $carouselId }}');
                const thumbs = carouselEl.parentElement.querySelectorAll('.thumbnail-indicator');
                carouselEl.addEventListener('slide.bs.carousel', function (event) {
                    thumbs.forEach((thumb, i) => thumb.classList.toggle('active', i === event.to));
                });
            })();
        </script>
    @endif
</div>

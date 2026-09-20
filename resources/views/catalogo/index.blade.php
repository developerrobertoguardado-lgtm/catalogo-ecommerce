<x-layouts.app :title="$categoriaActual->name ?? 'Catálogo'">
    <div class="container py-4" id="productos">
        <x-breadcrumb :items="[
            ['label' => 'Inicio', 'url' => route('catalogo.index')],
            ['label' => $categoriaActual->name ?? 'Catálogo', 'url' => request()->url()],
        ]" />

<section class="catalog-hero" aria-label="Bienvenida">
            @php $categoriaHero = $categoriaActual ?? null; @endphp
            <div>
                <h2 class="catalog-hero-title">Los mejores productos<br>para tu día a día</h2>
                @if ($categoriaHero)
                    <p class="catalog-hero-sub">Calidad y confianza en la categoría {{ $categoriaHero->name }}.</p>
                @else
                    <p class="catalog-hero-sub">Calidad y confianza en cada compra.</p>
                @endif
            </div>
        </section>

        <section class="d-md-none mt-3" aria-label="Categorías">
            <div class="d-flex justify-content-between align-items-center px-1 mb-2">
                <h2 class="h6 fw-bold mb-0">Categorías</h2>
                <button type="button" class="catalog-filter-icon-btn" data-bs-toggle="offcanvas" data-bs-target="#catalogFiltersPanel" aria-controls="catalogFiltersPanel" aria-label="Abrir filtros">
                    <x-icon name="sliders" class="icon-sm" />
                </button>
            </div>
            <div class="d-flex gap-3 overflow-auto pb-2 category-chip-scroll">
                <a href="{{ route('catalogo.index') }}" class="category-chip {{ !request('categoria_id') ? 'is-active' : '' }}">
                    <span class="category-chip-icon"><x-icon name="box" class="icon-sm" /></span>
                    <span>Todas</span>
                </a>
                @foreach ($categorias as $categoria)
                    <a href="{{ request('categoria_id') == $categoria->id ? route('catalogo.index') : route('catalogo.categoria', $categoria) }}"
                       class="category-chip {{ request('categoria_id') == $categoria->id ? 'is-active' : '' }}">
                        <span class="category-chip-icon">
                            @if ($categoria->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($categoria->image_path) }}" alt="">
                            @else
                                <x-icon name="tag" class="icon-sm" />
                            @endif
                        </span>
                        <span>{{ $categoria->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        @php
            $labelsFiltros = [
                'nombre' => 'Búsqueda',
                'categoria_id' => 'Categoría',
                'precio_min' => 'Precio mín.',
                'precio_max' => 'Precio máx.',
            ];
            $queryBase = collect(request()->query())->except('page');
        @endphp

        <div class="d-md-none d-flex gap-2 align-items-center overflow-auto filter-chips-scroll py-2">
            @foreach ($labelsFiltros as $param => $label)
                @if ($queryBase->has($param) && $queryBase[$param] !== '' && $queryBase[$param] !== null)
                    <a class="filter-chip" href="{{ route('catalogo.index', $queryBase->except($param)->all()) }}" aria-label="Quitar filtro {{ $label }}">
                        <span>{{ $label }}: {{ $queryBase[$param] }}</span>
                        <x-icon name="x" class="icon-sm chip-x" />
                    </a>
                @endif
            @endforeach
            @if ($queryBase->isNotEmpty())
                <a class="filter-chip filter-chip-clear" href="{{ route('catalogo.index') }}">
                    <x-icon name="trash" class="icon-sm chip-x" /> Limpiar
                </a>
            @endif
        </div>

        <div class="row g-4 mt-md-0">
            <aside class="col-12 col-md-3">
                <div class="offcanvas-md offcanvas-start catalog-filters-panel" tabindex="-1" id="catalogFiltersPanel" aria-labelledby="catalogFiltersLabel">
                    <div class="offcanvas-header d-md-none">
                        <h2 class="offcanvas-title h5" id="catalogFiltersLabel"><x-icon name="sliders" class="icon-sm" /> Filtros de productos</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar filtros"></button>
                    </div>

                    <div class="offcanvas-body d-flex flex-column">
                        <form method="GET" action="{{ route('catalogo.index') }}" class="card catalog-filter flex-grow-1 d-flex flex-column">
                            <div class="card-body flex-grow-1">
                                <label for="nombre" class="visually-hidden">Buscar por nombre</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-transparent"><x-icon name="search" class="icon-sm" /></span>
                                    <input type="text" id="nombre" name="nombre" value="{{ request('nombre') }}"
                                        placeholder="Buscar producto..." class="form-control" aria-label="Buscar producto">
                                </div>

                                <div class="accordion" id="filtrosAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#filtroCategoria" aria-expanded="true">
                                                Categoría
                                            </button>
                                        </h2>
                                        <div id="filtroCategoria" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="category-filter-list">
                                                    <div
                                                        class="form-check category-filter-item {{ !request('categoria_id') ? 'is-selected' : '' }}">
                                                        <input type="radio" name="categoria_id" value="" id="cat-todas"
                                                            class="form-check-input visually-hidden"
                                                            @checked(!request('categoria_id')) onchange="this.form.submit()">
                                                        <label for="cat-todas" class="form-check-label">Todas</label>
                                                    </div>
                                                    @foreach ($categorias as $categoria)
                                                        <div
                                                            class="form-check category-filter-item {{ request('categoria_id') == $categoria->id ? 'is-selected' : '' }}">
                                                            <input type="radio" name="categoria_id"
                                                                value="{{ $categoria->id }}" id="cat-{{ $categoria->id }}"
                                                                class="form-check-input visually-hidden"
                                                                @checked(request('categoria_id') == $categoria->id) onchange="this.form.submit()">
                                                            <label for="cat-{{ $categoria->id }}" class="form-check-label">
                                                                @if ($categoria->image_path)
                                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($categoria->image_path) }}"
                                                                        alt="" class="category-avatar">
                                                                @else
                                                                    <span
                                                                        class="category-avatar category-avatar-fallback"><x-icon
                                                                            name="tag" class="icon-sm" /></span>
                                                                @endif
                                                                <span>{{ $categoria->name }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#filtroPrecio" aria-expanded="true">
                                                Precio
                                            </button>
                                        </h2>
                                        <div id="filtroPrecio" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="price-progress-visual" aria-hidden="true">
                                                    <span class="price-progress-bars"></span>
                                                    <span class="price-progress-line"></span>
                                                    <span class="price-progress-handle price-progress-handle-start"></span>
                                                    <span class="price-progress-handle price-progress-handle-end"></span>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" name="precio_min"
                                                            value="{{ request('precio_min') }}" placeholder="S/ Mín."
                                                            class="form-control form-control-sm" aria-label="Precio mínimo">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" name="precio_max"
                                                            value="{{ request('precio_max') }}" placeholder="S/ Máx."
                                                            class="form-control form-control-sm" aria-label="Precio máximo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer border-0 pt-0 pb-3 px-3 sticky-filter-cta">
                                <button type="submit" class="btn btn-primary w-100">
                                    <x-icon name="cart" class="icon-sm" /> Aplicar filtros
                                </button>
                                @if (request()->hasAny(['nombre', 'categoria_id', 'precio_min', 'precio_max']))
                                    <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                        <x-icon name="trash" class="icon-sm" /> Eliminar filtros
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="col-md-9">
                <div class="d-none d-md-flex align-items-center justify-content-between mb-3">
                    <h1 class="h5 fw-bold mb-0">Productos</h1>
                    <span class="text-muted small">{{ $productos->total() }} {{ $productos->total() == 1 ? 'resultado' : 'resultados' }}</span>
                </div>

                @if ($productos->isEmpty())
                    <div class="catalog-empty p-5 text-center">
                        <x-icon name="search" class="icon-lg mb-2" />
                        <p class="fw-semibold mb-1">No se encontraron productos con esos filtros.</p>
                        <a href="{{ route('catalogo.index') }}" class="btn btn-outline-primary btn-sm">Eliminar filtros</a>
                    </div>
                @else
                    <div class="d-md-none d-flex gap-2 justify-content-between align-items-center mb-1">
                        <h1 class="h6 fw-bold mb-0">Productos</h1>
                        <span class="text-muted small">{{ $productos->total() }} {{ $productos->total() == 1 ? 'resultado' : 'resultados' }}</span>
                    </div>

                    <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-3">
                        @foreach ($productos as $producto)
                            <div class="col">
                                <x-producto-card :producto="$producto" :currency="$currency ?? null" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 catalog-pagination">
                        {{ $productos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

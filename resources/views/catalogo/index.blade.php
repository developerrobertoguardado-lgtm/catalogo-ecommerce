<x-layouts.app :title="$categoriaActual->name ?? 'Catálogo'">
    @unless (isset($categoriaActual) || request()->hasAny(['nombre', 'categoria_id', 'precio_min', 'precio_max']))
        <section class="bg-primary text-white text-center py-5">
            <div class="container">
                <h1 class="fw-bold">{{ \App\Models\StoreSetting::current()->store_name }}</h1>
                <p class="mb-4">Elige tu producto y pide directo por WhatsApp — sin registros, sin vueltas.</p>
                <a href="#productos" class="btn btn-light">Ver catálogo</a>
            </div>
        </section>
    @endunless

    <div class="container py-4" id="productos">
        <x-breadcrumb :items="[
            ['label' => 'Inicio', 'url' => route('catalogo.index')],
            ['label' => $categoriaActual->name ?? 'Catálogo', 'url' => request()->url()],
        ]" />

        <div class="row g-4">
            <aside class="col-md-3">
                <form method="GET" action="{{ route('catalogo.index') }}" class="card">
                    <div class="card-body">
                        <label for="nombre" class="visually-hidden">Buscar por nombre</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><x-icon name="search" class="icon-sm" /></span>
                            <input type="text" id="nombre" name="nombre" value="{{ request('nombre') }}" placeholder="Buscar producto..." class="form-control">
                        </div>

                        <div class="accordion" id="filtrosAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filtroCategoria">
                                        Categoría
                                    </button>
                                </h2>
                                <div id="filtroCategoria" class="accordion-collapse collapse show" data-bs-parent="#filtrosAccordion">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input type="radio" name="categoria_id" value="" id="cat-todas" class="form-check-input" @checked(! request('categoria_id')) onchange="this.form.submit()">
                                            <label for="cat-todas" class="form-check-label">Todas</label>
                                        </div>
                                        @foreach ($categorias as $categoria)
                                            <div class="form-check">
                                                <input type="radio" name="categoria_id" value="{{ $categoria->id }}" id="cat-{{ $categoria->id }}" class="form-check-input" @checked(request('categoria_id') == $categoria->id) onchange="this.form.submit()">
                                                <label for="cat-{{ $categoria->id }}" class="form-check-label">{{ $categoria->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filtroPrecio">
                                        Precio
                                    </button>
                                </h2>
                                <div id="filtroPrecio" class="accordion-collapse collapse show" data-bs-parent="#filtrosAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="number" step="0.01" name="precio_min" value="{{ request('precio_min') }}" placeholder="Mín." class="form-control form-control-sm">
                                            </div>
                                            <div class="col-6">
                                                <input type="number" step="0.01" name="precio_max" value="{{ request('precio_max') }}" placeholder="Máx." class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">Filtrar</button>
                        @if (request()->hasAny(['nombre', 'categoria_id', 'precio_min', 'precio_max']))
                            <a href="{{ route('catalogo.index') }}" class="btn btn-link btn-sm w-100">Limpiar filtros</a>
                        @endif
                    </div>
                </form>
            </aside>

            <div class="col-md-9">
                @if ($productos->isEmpty())
                    <p class="text-muted">No se encontraron productos con esos filtros.</p>
                @else
                    <div class="row row-cols-2 row-cols-sm-3 g-3">
                        @foreach ($productos as $producto)
                            <div class="col">
                                <x-producto-card :producto="$producto" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $productos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

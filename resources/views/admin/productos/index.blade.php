@php
    $totalProductos = $productos->total();
    $sinStock = \App\Models\Product::where('stock', 0)->count();
    $bajoStock = \App\Models\Product::whereBetween('stock', [1, 4])->count();
@endphp

<x-layouts.admin title="Productos">
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total de productos</p>
                    <p class="h3 fw-bold mb-0">{{ $totalProductos }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Sin stock</p>
                    <p class="h3 fw-bold text-danger mb-0">{{ $sinStock }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Stock bajo (&lt; 5)</p>
                    <p class="h3 fw-bold text-warning mb-0">{{ $bajoStock }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
            + Nuevo producto
        </button>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.productos.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="buscar-productos" class="form-label">Buscar productos</label>
                    <input type="search" id="buscar-productos" name="buscar" value="{{ $buscar }}"
                           placeholder="Nombre o descripción" class="form-control">
                </div>
                <div class="col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if ($buscar !== '')
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card sash-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle sash-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td>
                                @if ($producto->primaryImage)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($producto->primaryImage->path) }}"
                                         alt="{{ $producto->name }}" class="rounded" style="width:2.5rem;height:2.5rem;object-fit:cover;">
                                @else
                                    <div class="rounded bg-light" style="width:2.5rem;height:2.5rem;"></div>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $producto->name }}</td>
                            <td class="text-muted">{{ $producto->category->name }}</td>
                            <td class="text-muted">{{ $producto->price }}</td>
                            <td>
                                @if ($producto->stock === 0)
                                    <span class="badge text-bg-danger-subtle text-danger-emphasis">Agotado</span>
                                @elseif ($producto->stock < 5)
                                    <span class="badge text-bg-warning-subtle text-warning-emphasis">{{ $producto->stock }} unidades</span>
                                @else
                                    <span class="badge text-bg-success-subtle text-success-emphasis">{{ $producto->stock }} unidades</span>
                                @endif
                            </td>
                                <td class="text-end sash-actions">
                                <button type="button" class="btn btn-link btn-sm p-0 me-3" data-bs-toggle="modal" data-bs-target="#modalEditarProducto{{ $producto->id }}">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" class="d-inline" data-confirm="¿Eliminar este producto?" data-confirm-title="Eliminar producto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Sin resultados disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $productos->links() }}
    </div>

    {{-- Modal: nuevo producto --}}
    <div class="modal fade" id="modalNuevoProducto" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" data-lock-on-submit>
                    @csrf
                    <input type="hidden" name="_modal" value="modalNuevoProducto">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.productos._campos', ['producto' => null, 'categorias' => $categorias])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modales: editar producto (uno por fila) --}}
    @foreach ($productos as $producto)
        <div class="modal fade" id="modalEditarProducto{{ $producto->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.productos.update', $producto) }}" enctype="multipart/form-data" data-lock-on-submit>
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_modal" value="modalEditarProducto{{ $producto->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @include('admin.productos._campos', ['producto' => $producto, 'categorias' => $categorias])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if ($errors->any() && old('_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalEl = document.getElementById(@json(old('_modal')));
                if (modalEl) new bootstrap.Modal(modalEl).show();
            });
        </script>
    @endif
</x-layouts.admin>

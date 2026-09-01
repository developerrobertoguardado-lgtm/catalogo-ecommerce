<x-layouts.admin title="Categorías">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
            + Nueva categoría
        </button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Productos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr>
                            <td class="fw-medium">{{ $categoria->name }}</td>
                            <td><span class="badge text-bg-primary-subtle text-primary-emphasis">{{ $categoria->products_count }}</span></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-link btn-sm p-0 me-3" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria{{ $categoria->id }}">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No hay categorías todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $categorias->links() }}
    </div>

    {{-- Modal: nueva categoría --}}
    <div class="modal fade" id="modalNuevaCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.categorias.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="modalNuevaCategoria">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="name-nueva" class="form-label">Nombre</label>
                        <input type="text" id="name-nueva" name="name" value="{{ old('name') }}" required class="form-control">
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modales: editar categoría (uno por fila) --}}
    @foreach ($categorias as $categoria)
        <div class="modal fade" id="modalEditarCategoria{{ $categoria->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_modal" value="modalEditarCategoria{{ $categoria->id }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar categoría</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="name-{{ $categoria->id }}" class="form-label">Nombre</label>
                            <input type="text" id="name-{{ $categoria->id }}" name="name" value="{{ old('name', $categoria->name) }}" required class="form-control">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

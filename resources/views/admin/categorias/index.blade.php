<x-layouts.admin title="Categorías">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
            + Nueva categoría
        </button>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categorias.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="buscar-categorias" class="form-label">Buscar categorías</label>
                    <input type="search" id="buscar-categorias" name="buscar" value="{{ $buscar }}"
                           placeholder="Nombre de categoría" class="form-control">
                </div>
                <div class="col-md-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    @if ($buscar !== '')
                        <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">Limpiar</a>
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
                            <th>Imagen</th>
                            <th>Nombre</th>
                        <th>Productos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr>
                            <td>
                                @if ($categoria->image_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($categoria->image_path) }}" alt="{{ $categoria->name }}" class="category-avatar">
                                @else
                                    <span class="category-avatar category-avatar-fallback"><x-icon name="tag" class="icon-sm" /></span>
                                @endif
                            </td>
                            <td class="fw-medium">{{ $categoria->name }}</td>
                            <td><span class="badge text-bg-primary-subtle text-primary-emphasis">{{ $categoria->products_count }}</span></td>
                            <td class="text-end sash-actions">
                                <button type="button" class="btn btn-link btn-sm p-0 me-3" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria{{ $categoria->id }}">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" class="d-inline" data-confirm="¿Eliminar esta categoría?" data-confirm-title="Eliminar categoría">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Sin resultados disponibles</td>
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
                <form method="POST" action="{{ route('admin.categorias.store') }}" enctype="multipart/form-data" data-lock-on-submit>
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
                        <label for="imagen-nueva" class="form-label mt-3">Imagen (opcional)</label>
                        <input type="file" id="imagen-nueva" name="imagen" accept=".png,.jpg,.jpeg,.webp" class="form-control">
                        @error('imagen') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" enctype="multipart/form-data" data-lock-on-submit>
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
                            <div class="mt-3" x-data="categoriaDropzone(@js($categoria->image_path ? \Illuminate\Support\Facades\Storage::url($categoria->image_path) : null))">
                                <label class="form-label">Imagen (opcional)</label>
                                <div class="logo-dropzone category-dropzone" :class="dragOver ? 'is-dragover' : ''" @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop($event)" @click="$refs.input.click()">
                                    <template x-if="preview"><div class="logo-dropzone-preview"><img :src="preview" alt="Vista previa" class="store-logo-preview"><button type="button" class="logo-remove" title="Eliminar imagen" aria-label="Eliminar imagen" @click.stop="removeImage">&times;</button></div></template>
                                    <template x-if="!preview"><div><p class="mb-1 fw-semibold">Arrastra una imagen aquí</p><p class="mb-0 small text-muted">o haz clic para seleccionar</p></div></template>
                                    <input type="file" name="imagen" accept=".png,.jpg,.jpeg,.webp" class="d-none" x-ref="input" @change="handleSelect($event)">
                                </div>
                                <input type="hidden" name="eliminar_imagen" value="0" x-ref="remove">
                                @error('imagen') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
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

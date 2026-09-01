@php $prefix = $producto ? "producto-{$producto->id}" : 'producto-nuevo'; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="name-{{ $prefix }}" class="form-label">Nombre</label>
        <input type="text" id="name-{{ $prefix }}" name="name" value="{{ old('name', $producto?->name) }}" required class="form-control">
        @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="category_id-{{ $prefix }}" class="form-label">Categoría</label>
        <select id="category_id-{{ $prefix }}" name="category_id" required class="form-select">
            <option value="">Selecciona una categoría</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('category_id', $producto?->category_id) == $categoria->id)>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="price-{{ $prefix }}" class="form-label">Precio</label>
        <input type="number" step="0.01" min="0" id="price-{{ $prefix }}" name="price" value="{{ old('price', $producto?->price) }}" required class="form-control">
        @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="stock-{{ $prefix }}" class="form-label">Stock</label>
        <input type="number" min="0" id="stock-{{ $prefix }}" name="stock" value="{{ old('stock', $producto?->stock) }}" required class="form-control">
        @error('stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="description-{{ $prefix }}" class="form-label">Descripción</label>
        <textarea id="description-{{ $prefix }}" name="description" rows="3" required class="form-control">{{ old('description', $producto?->description) }}</textarea>
        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Fotos (máximo 4, arrastra para reordenar)</label>
        <x-fotos-dropzone :producto="$producto" />
        @error('fotos') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>

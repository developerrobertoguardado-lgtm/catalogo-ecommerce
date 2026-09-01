<x-layouts.admin title="Configuración">
    <div class="card" style="max-width: 28rem;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.configuracion.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="store_name" class="form-label">Nombre de la tienda</label>
                    <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $configuracion->store_name) }}" required class="form-control">
                    @error('store_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="whatsapp_number" class="form-label">Número de WhatsApp (solo dígitos, con código de país)</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $configuracion->whatsapp_number) }}" required class="form-control">
                    @error('whatsapp_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="currency" class="form-label">Moneda</label>
                    <input type="text" id="currency" name="currency" value="{{ old('currency', $configuracion->currency) }}" required class="form-control">
                    @error('currency') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

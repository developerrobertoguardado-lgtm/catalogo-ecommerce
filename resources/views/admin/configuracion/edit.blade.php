<x-layouts.admin title="Configuración">
    <div class="card" style="max-width: 30rem;">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-tienda" data-bs-toggle="tab" data-bs-target="#panel-tienda" type="button" role="tab" aria-controls="panel-tienda" aria-selected="true">Tienda</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-visual" data-bs-toggle="tab" data-bs-target="#panel-visual" type="button" role="tab" aria-controls="panel-visual" aria-selected="false">Aspecto visual</button>
                </li>
            </ul>

            <form method="POST" action="{{ route('admin.configuracion.update') }}" enctype="multipart/form-data" data-lock-on-submit>
                @csrf
                @method('PUT')

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="panel-tienda" role="tabpanel" aria-labelledby="tab-tienda">
                        <div class="mb-3">
                            <label for="store_name" class="form-label">Nombre de la tienda</label>
                            <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $configuracion->store_name) }}" required class="form-control">
                            @error('store_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4" x-data="logoDropzone(@js($configuracion->logo_path ? \Illuminate\Support\Facades\Storage::url($configuracion->logo_path) : null))">
                            <label for="logo" class="form-label">Logo de la tienda</label>
                            <div class="logo-dropzone" :class="dragOver ? 'is-dragover' : ''" @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop($event)" @click="$refs.input.click()">
                                <template x-if="preview">
                                    <div class="logo-dropzone-preview">
                                        <img :src="preview" alt="Vista previa del logo" class="store-logo-preview">
                                        <button type="button" class="logo-remove" title="Eliminar logo" aria-label="Eliminar logo" @click.stop="removeLogo">&times;</button>
                                    </div>
                                </template>
                                <template x-if="!preview">
                                    <div>
                                        <p class="mb-1 fw-semibold">Arrastra tu logo aquí</p>
                                        <p class="mb-0 small text-muted">o haz clic para seleccionar un archivo</p>
                                    </div>
                                </template>
                                <input type="file" id="logo" name="logo" accept=".png,.jpg,.jpeg,.webp" class="d-none" x-ref="input" @change="handleSelect($event)">
                            </div>
                            <input type="hidden" name="eliminar_logo" value="0" x-ref="remove">
                            <div class="form-text">PNG, JPG, JPEG o WEBP. Máximo 2 MB.</div>
                            @error('logo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                    </div>

                    <div class="tab-pane fade" id="panel-visual" role="tabpanel" aria-labelledby="tab-visual" x-data="paletaColores(@js([
                        'primary_color' => $configuracion->primary_color,
                        'secondary_color' => $configuracion->secondary_color,
                        'button_color' => $configuracion->button_color,
                        'menu_color' => $configuracion->menu_color,
                        'background_color' => $configuracion->background_color,
                        'text_color' => $configuracion->text_color,
                    ]))">
                        <p class="text-muted small mb-3">Define los colores de tu empresa. Se aplican al catálogo público y al panel de administración. Si dejas un campo vacío se usa el color por defecto.</p>

                        <div class="mb-3">
                            <label for="paletaPreview" class="form-label">Vista previa</label>
                            <div id="paletaPreview" class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary" :style="previewBtn">Botón</button>
                                <span class="px-2 py-1 rounded" :style="previewPill">Menú</span>
                            </div>
                        </div>

                        @foreach ([
                            'primary_color' => 'Color primario',
                            'secondary_color' => 'Color secundario',
                            'button_color' => 'Color de botones',
                            'menu_color' => 'Color del menú',
                            'background_color' => 'Color de fondo',
                            'text_color' => 'Color de texto',
                        ] as $field => $label)
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <div class="input-group">
                                    <input type="color" class="input-group-text border-end-0" :value="colors.{{ $field }}" @input="setColor('{{ $field }}', $event.target.value)" style="padding:.25rem .35rem; width:3rem; height:2.4rem; cursor:pointer;">
                                    <input type="text" id="{{ $field }}" name="{{ $field }}" x-model="colors.{{ $field }}" class="form-control" placeholder="#RRGGBB" maxlength="7">
                                    <button type="button" class="btn btn-outline-secondary" @click="clearColor('{{ $field }}')" title="Usar color por defecto">Por defecto</button>
                                </div>
                                @error($field) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

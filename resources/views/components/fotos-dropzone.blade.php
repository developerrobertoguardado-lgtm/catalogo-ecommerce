@props(['producto' => null, 'max' => 4])

@php
    $existentes = $producto?->images->map(fn ($imagen) => [
        'id' => $imagen->id,
        'url' => \Illuminate\Support\Facades\Storage::url($imagen->path),
    ])->values() ?? [];
@endphp

<div
    x-data="fotosDropzone(@json($existentes), {{ $max }})"
    x-init="init()"
>
    <div
        class="border border-2 border-dashed rounded p-4 text-center"
        :class="dragOver ? 'border-primary bg-primary-subtle' : 'border-secondary-subtle bg-light'"
        style="cursor: pointer;"
        @dragover.prevent="dragOver = true"
        @dragleave.prevent="dragOver = false"
        @drop.prevent="handleDrop($event)"
        @click="$refs.input.click()"
    >
        <p class="mb-1 text-muted">Arrastra tus fotos aquí o haz clic para seleccionar</p>
        <p class="mb-0 small text-muted" x-text="items.length + ' / ' + max + ' fotos'"></p>
        <input
            type="file" name="fotos[]" multiple accept="image/*" class="d-none"
            x-ref="input"
            @change="handleSelect($event)"
        >
    </div>

    <div class="d-flex flex-wrap gap-2 mt-3" x-show="items.length > 0">
        <template x-for="(item, index) in items" :key="item.key">
            <div
                class="position-relative"
                draggable="true"
                @dragstart="dragStart(index)"
                @dragover.prevent
                @drop.prevent="dropAt(index)"
                style="cursor: grab;"
            >
                <img :src="item.previewUrl" class="thumbnail-indicator active">
                <span class="badge bg-dark position-absolute top-0 start-0 m-1" x-text="index + 1"></span>
                <button
                    type="button"
                    x-show="item.type === 'new'"
                    class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center"
                    style="width: 1.25rem; height: 1.25rem; line-height: 1;"
                    title="Quitar (aún no guardada)"
                    @click="quitarNueva(index)"
                >&times;</button>
            </div>
        </template>
    </div>

    <input type="hidden" name="orden_fotos" :value="JSON.stringify(ordenTokens())">
</div>

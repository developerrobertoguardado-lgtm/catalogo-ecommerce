<x-layouts.app :title="$producto->name">
    <div class="container py-4">
        <x-breadcrumb :items="[
            ['label' => 'Inicio', 'url' => route('catalogo.index')],
            ['label' => $producto->category->name, 'url' => route('catalogo.categoria', $producto->category)],
            ['label' => $producto->name, 'url' => route('catalogo.show', $producto)],
        ]" />

        <div class="row g-5">
            <div class="col-md-6">
                <x-slider-fotos :imagenes="$producto->images" />
            </div>

            <div
                class="col-md-6"
                x-data="{
                    cantidad: 1,
                    stock: {{ $producto->stock }},
                    precio: {{ $producto->price }},
                    enviando: false,
                    incrementar() { if (this.cantidad < this.stock) this.cantidad++; },
                    decrementar() { if (this.cantidad > 1) this.cantidad--; },
                    comprar() {
                        this.enviando = true;
                        fetch('{{ route('pedidos.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '{{ csrf_token() }}',
                                Accept: 'application/json',
                            },
                            body: JSON.stringify({ product_id: {{ $producto->id }}, quantity: this.cantidad }),
                        })
                            .then((res) => res.json().then((data) => ({ ok: res.ok, data })))
                            .then(({ ok, data }) => {
                                this.enviando = false;
                                if (ok) {
                                    window.location.href = data.whatsapp_url;
                                } else {
                                    alert(data.message ?? 'No se pudo generar el pedido.');
                                }
                            })
                            .catch(() => {
                                this.enviando = false;
                                alert('Ocurrió un error al generar el pedido.');
                            });
                    },
                }"
            >
                <p class="text-uppercase small text-primary fw-semibold mb-1">{{ $producto->category->name }}</p>
                <h1 class="h3 fw-bold">{{ $producto->name }}</h1>

                <div class="d-flex align-items-center gap-3 my-3">
                    <p class="h4 fw-bold mb-0">
                        {{ $producto->price }} <small class="text-muted fw-normal">{{ \App\Models\StoreSetting::current()->currency }}</small>
                    </p>
                    @if ($producto->stock === 0)
                        <span class="badge text-bg-secondary">Agotado</span>
                    @elseif ($producto->stock < 5)
                        <span class="badge text-bg-warning">Últimas {{ $producto->stock }} unidades</span>
                    @else
                        <span class="badge text-bg-success">En stock</span>
                    @endif
                </div>

                <p class="text-muted">{{ $producto->description }}</p>

                @if ($producto->stock > 0)
                    <div class="card bg-light border-0 mt-4">
                        <div class="card-body">
                            <label class="form-label">Cantidad</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group" style="width: 140px;">
                                    <button type="button" @click="decrementar" class="btn btn-outline-secondary">
                                        <x-icon name="minus" class="icon-sm" />
                                    </button>
                                    <input type="number" min="1" :max="stock" x-model.number="cantidad" class="form-control text-center">
                                    <button type="button" @click="incrementar" class="btn btn-outline-secondary">
                                        <x-icon name="plus" class="icon-sm" />
                                    </button>
                                </div>
                                <p class="text-muted small mb-0">
                                    Total: <span class="fw-semibold text-dark" x-text="(cantidad * precio).toFixed(2)"></span>
                                    {{ \App\Models\StoreSetting::current()->currency }}
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="comprar"
                                :disabled="enviando || cantidad < 1 || cantidad > stock"
                                class="btn btn-success w-100 mt-3 d-flex align-items-center justify-content-center gap-2"
                            >
                                <x-icon name="whatsapp" class="icon-md" />
                                <span x-show="!enviando">Comprar por WhatsApp</span>
                                <span x-show="enviando">Enviando...</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary mt-4">Sin stock disponible por el momento.</div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

<x-layouts.app :title="$producto->name">
    <div class="container py-4" x-data="{
        cantidad: 1,
        stock: {{ $producto->stock }},
        precio: {{ $producto->price }},
        enviando: false,
        datos: { customer_name: '', customer_phone: '', delivery_zone: '', delivery_address: '', delivery_city: '', delivery_notes: '' },
        errores: {},
        incrementar() { this.cantidad++; },
        decrementar() { if (this.cantidad > 1) this.cantidad--; },
        validar() {
            this.errores = {};
            const obligatorios = {
                customer_name: 'El nombre completo es obligatorio.',
                customer_phone: 'El número telefónico es obligatorio.',
                delivery_zone: 'Selecciona Lima o provincias.',
                delivery_address: 'La dirección completa es obligatoria.',
                delivery_city: 'La ciudad es obligatoria.',
            };
            Object.entries(obligatorios).forEach(([campo, mensaje]) => {
                if (!this.datos[campo].trim()) this.errores[campo] = mensaje;
            });
            if (this.datos.customer_phone && !/^[0-9+()\s-]+$/.test(this.datos.customer_phone)) {
                this.errores.customer_phone = 'Ingresa un número telefónico válido.';
            }
            return Object.keys(this.errores).length === 0;
        },
        comprar() {
            if (!this.validar()) return;
            this.enviando = true;
            fetch('{{ route('pedidos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '{{ csrf_token() }}',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ product_id: {{ $producto->id }}, quantity: this.cantidad, ...this.datos }),
            })
                .then((res) => res.json().then((data) => ({ ok: res.ok, data })))
                .then(({ ok, data }) => {
                    if (ok) {
                        window.location.href = data.redirect_url;
                    } else {
                        this.enviando = false;
                        this.errores = Object.fromEntries(Object.entries(data.errors ?? {}).map(([key, messages]) => [key, messages[0]]));
                        window.alertas.error('Revisa los datos', data.message ?? 'Completa los campos requeridos.');
                    }
                })
                .catch(() => {
                    this.enviando = false;
                    window.alertas.error('Error de conexión', 'Ocurrió un error al generar el pedido.');
                });
        },
    }">
        <x-breadcrumb :items="[
            ['label' => 'Inicio', 'url' => route('catalogo.index')],
            ['label' => $producto->category->name, 'url' => route('catalogo.categoria', $producto->category)],
            ['label' => $producto->name, 'url' => route('catalogo.show', $producto)],
        ]" />

        <div class="row g-4 g-lg-5 product-detail-layout">
            <div class="col-md-6">
                <x-slider-fotos :imagenes="$producto->images" />
            </div>

            <div class="col-md-6">
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

                 <div class="card bg-light border-0 mt-4 product-purchase-card">
                        <div class="card-body">
                            <label class="form-label">Cantidad</label>
                            <div class="d-flex align-items-center gap-3">
                                 <div class="input-group quantity-stepper">
                                    <button type="button" @click="decrementar" class="btn btn-outline-secondary">
                                        <x-icon name="minus" class="icon-sm" />
                                    </button>
                                    <input type="number" min="1" x-model.number="cantidad" class="form-control text-center">
                                    <button type="button" @click="incrementar" class="btn btn-outline-secondary">
                                        <x-icon name="plus" class="icon-sm" />
                                    </button>
                                </div>
                                <p class="text-muted small mb-0">
                                    Total: <span class="fw-semibold" x-text="(cantidad * precio).toFixed(2)"></span>
                                    {{ \App\Models\StoreSetting::current()->currency }}
                                </p>
                            </div>

                            <button
                                type="button"
                                :disabled="enviando || cantidad < 1"
                                class="btn btn-primary w-100 mt-3 d-flex align-items-center justify-content-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#modalDatosPedido"
                            >
                                <x-icon name="whatsapp" class="icon-md" />
                                <span x-show="!enviando">Comprar por WhatsApp</span>
                                <span x-show="enviando">Enviando...</span>
                            </button>
                        </div>
                    </div>
            </div>
        </div>

        @if ($producto->long_description)
            <div class="mt-5 pt-4 border-top">
                <div class="product-long-description mx-auto" style="max-width: 900px;">
                    {!! $producto->long_description !!}
                </div>
            </div>
        @endif

    <div class="modal fade purchase-modal" id="modalDatosPedido" tabindex="-1" aria-labelledby="modalDatosPedidoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5 fw-bold" id="modalDatosPedidoLabel">Completar Pedido</h2>
                        <p class="small text-muted mb-0 text-uppercase">Hacer pedido y pagar en casa</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="purchase-summary mb-4">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                @if ($producto->primaryImage)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($producto->primaryImage->path) }}" alt="{{ $producto->name }}" class="purchase-summary-image">
                                @endif
                                <span><strong>{{ $producto->name }}</strong> &middot; <span x-text="cantidad"></span> unidad(es)</span>
                            </div>
                            <span class="fw-semibold">Total: <span x-text="(cantidad * precio).toFixed(2)"></span> {{ \App\Models\StoreSetting::current()->currency }}</span>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 purchase-field">
                            <label for="customer_name" class="form-label">Nombre completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><x-icon name="user" class="icon-sm" /></span>
                                <input id="customer_name" x-model="datos.customer_name" type="text" placeholder="Tu nombre" class="form-control" :class="errores.customer_name ? 'is-invalid' : ''">
                            </div>
                            <div class="invalid-feedback d-block" x-show="errores.customer_name" x-text="errores.customer_name"></div>
                        </div>
                        <div class="col-12 purchase-field">
                            <label for="customer_phone" class="form-label">Número telefónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><x-icon name="phone" class="icon-sm" /></span>
                                <input id="customer_phone" x-model="datos.customer_phone" type="tel" placeholder="900 123 456" class="form-control" :class="errores.customer_phone ? 'is-invalid' : ''">
                            </div>
                            <div class="invalid-feedback d-block" x-show="errores.customer_phone" x-text="errores.customer_phone"></div>
                        </div>
                        <div class="col-12 purchase-field">
                            <label for="delivery_zone" class="form-label">Zona de entrega</label>
                            <div class="input-group">
                                <span class="input-group-text"><x-icon name="map-pin" class="icon-sm" /></span>
                                <select id="delivery_zone" x-model="datos.delivery_zone" class="form-select" :class="errores.delivery_zone ? 'is-invalid' : ''">
                                    <option value="">Selecciona una zona</option>
                                    <option value="lima">Lima</option>
                                    <option value="provincias">Provincias</option>
                                </select>
                            </div>
                            <div class="invalid-feedback d-block" x-show="errores.delivery_zone" x-text="errores.delivery_zone"></div>
                        </div>
                        <div class="col-12 purchase-field">
                            <label for="delivery_city" class="form-label">Ciudad</label>
                            <div class="input-group">
                                <span class="input-group-text"><x-icon name="building" class="icon-sm" /></span>
                                <input id="delivery_city" x-model="datos.delivery_city" type="text" placeholder="Tu ciudad" class="form-control" :class="errores.delivery_city ? 'is-invalid' : ''">
                            </div>
                            <div class="invalid-feedback d-block" x-show="errores.delivery_city" x-text="errores.delivery_city"></div>
                        </div>
                        <div class="col-12 purchase-field">
                            <label for="delivery_address" class="form-label">Dirección completa</label>
                            <div class="input-group">
                                <span class="input-group-text"><x-icon name="map-pin" class="icon-sm" /></span>
                                <input id="delivery_address" x-model="datos.delivery_address" type="text" placeholder="Calle, número, barrio" class="form-control" :class="errores.delivery_address ? 'is-invalid' : ''">
                            </div>
                            <div class="invalid-feedback d-block" x-show="errores.delivery_address" x-text="errores.delivery_address"></div>
                        </div>
                        <div class="col-12 purchase-field">
                            <label for="delivery_notes" class="form-label">Referencias o notas <span class="text-muted fw-normal">(opcional)</span></label>
                            <textarea id="delivery_notes" x-model="datos.delivery_notes" rows="3" class="form-control" placeholder="Referencia para entrega o nota de pasos para llamar o entregar"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary purchase-submit" @click="comprar" :disabled="enviando">
                        <span x-text="enviando ? 'Creando pedido...' : 'Crear pedido y continuar'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>

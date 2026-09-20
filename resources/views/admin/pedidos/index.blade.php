@php
    $totalPedidos = $pedidos->total();
    $totalFacturado = \App\Models\Order::sum('total');
@endphp

<x-layouts.admin title="Pedidos">
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total de pedidos</p>
                    <p class="h3 fw-bold mb-0">{{ $totalPedidos }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total enviado por WhatsApp</p>
                    <p class="h3 fw-bold text-primary mb-0">{{ number_format((float) $totalFacturado, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card sash-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle sash-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>WhatsApp</th>
                        <th>Estado</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                        <tr>
                            <td class="fw-medium">#{{ $pedido->id }}</td>
                            <td class="text-muted">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-muted">{{ $pedido->whatsapp_number }}</td>
                            <td>
                                <span class="badge {{ $pedido->status === 'ENTREGADO' ? 'text-bg-success' : ($pedido->status === 'ENVIADO' ? 'text-bg-info' : ($pedido->status === 'EN_PROCESO' ? 'text-bg-warning' : 'text-bg-secondary')) }}">
                                    {{ $pedido->status }}
                                </span>
                            </td>
                            <td><span class="badge text-bg-light border">{{ $pedido->items->count() }}</span></td>
                            <td class="fw-medium">{{ $pedido->total }}</td>
                            <td class="text-end sash-actions">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="link-primary">Ver</a>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2 btn-edit-order" data-order-id="{{ $pedido->id }}">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Todavía no se registraron pedidos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>

    <!-- Modal placeholder - contenido se carga via AJAX -->
    <div id="orderModalPlaceholder"></div>

    <script>
        function pedidoModal() {
            return {
                items: [],
                searchQuery: '',
                searchResults: [],
                searchDebounce: null,
                total: 0,
                isDelivered: false,

                cargar(pedido) {
                    this.isDelivered = pedido.status === 'ENTREGADO';
                    this.items = (pedido.items || []).map(item => {
                        const url = item.product?.primary_image_url
                            ?? (item.product?.primary_image ? '/storage/' + item.product.primary_image.path : null);
                        return {
                            id: item.id,
                            product_id: item.product_id,
                            product_name: item.product_name,
                            unit_price: parseFloat(item.unit_price),
                            quantity: item.quantity,
                            subtotal: parseFloat(item.subtotal),
                            image: url || ''
                        };
                    });
                    this.recalculateAll();
                },

                recalculate(index) {
                    const item = this.items[index];
                    if (item.quantity < 1) item.quantity = 1;
                    item.subtotal = item.quantity * item.unit_price;
                    this.recalculateAll();
                },

                recalculateAll() {
                    this.total = this.items.reduce((sum, item) => sum + item.subtotal, 0);
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(value);
                },

                async removeItem(index) {
                    const confirmed = await window.alertas.confirm(
                        'Eliminar item',
                        '¿Quitar este producto del pedido?',
                    );

                    if (confirmed) {
                        this.items.splice(index, 1);
                        this.recalculateAll();
                    }
                },

                searchProducts() {
                    if (this.searchDebounce) clearTimeout(this.searchDebounce);
                    this.searchDebounce = setTimeout(async () => {
                        if (this.searchQuery.length < 2) {
                            this.searchResults = [];
                            return;
                        }
                        try {
                            const res = await fetch('{{ route("admin.productos.search") }}?q=' + encodeURIComponent(this.searchQuery));
                            this.searchResults = await res.json();
                        } catch (e) {
                            console.error(e);
                        }
                    }, 300);
                },

                addItem(product) {
                    const exists = this.items.find(i => i.product_id === product.id);
                    if (exists) {
                        exists.quantity += 1;
                        this.recalculate(this.items.indexOf(exists));
                    } else {
                        this.items.push({
                            id: null,
                            product_id: product.id,
                            product_name: product.name,
                            unit_price: parseFloat(product.price),
                            quantity: 1,
                            subtotal: parseFloat(product.price),
                            image: product.primary_image_url || ''
                        });
                        this.recalculateAll();
                    }
                    bootstrap.Modal.getInstance(document.getElementById('addItemModal'))?.hide();
                    this.searchQuery = '';
                    this.searchResults = [];
                },
            };
        }

        // Inicializar modal de pedido después de cargar via AJAX
        function initPedidoModal(modalEl) {
            if (window.Alpine) {
                window.Alpine.initTree(modalEl);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit-order').forEach(btn => {
                btn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const placeholder = document.getElementById('orderModalPlaceholder');

                    // Mostrar loader
                    placeholder.innerHTML = '<div class="modal fade show d-block" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content"><div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando pedido...</p></div></div></div></div>';

                    // Cargar modal via AJAX
                    fetch('/admin/pedidos/' + orderId + '/edit', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        placeholder.innerHTML = html;
                        const modalEl = placeholder.querySelector('.modal');
                        if (modalEl) {
                            initPedidoModal(modalEl);
                            new bootstrap.Modal(modalEl).show();
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        placeholder.innerHTML = '';
                        window.alertas.error('Ocurrió un error', 'No se pudo cargar el pedido.');
                    });
                });
            });
        });
    </script>
</x-layouts.admin>
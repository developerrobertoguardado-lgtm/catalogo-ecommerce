<x-layouts.admin :title="'Pedido #'.$pedido->id">
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-4">
                    <p class="text-muted small mb-1">Fecha</p>
                    <p class="fw-medium">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-sm-4">
                    <p class="text-muted small mb-1">WhatsApp destino</p>
                    <p class="fw-medium">{{ $pedido->whatsapp_number }}</p>
                </div>
                <div class="col-sm-4">
                    <p class="text-muted small mb-1">Total</p>
                    <p class="fw-medium">{{ $pedido->total }}</p>
                </div>
            </div>

            <h2 class="h6 fw-semibold border-top pt-3 mt-2">Productos</h2>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td class="fw-medium">{{ $item->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.pedidos.index') }}" class="d-inline-flex align-items-center gap-1 mt-3 link-primary">
        <x-icon name="chevron-left" class="icon-sm" /> Volver a pedidos
    </a>
</x-layouts.admin>

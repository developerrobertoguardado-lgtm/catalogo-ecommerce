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

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>WhatsApp</th>
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
                            <td><span class="badge text-bg-light border">{{ $pedido->items->count() }}</span></td>
                            <td class="fw-medium">{{ $pedido->total }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="link-primary">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Todavía no se registraron pedidos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>
</x-layouts.admin>

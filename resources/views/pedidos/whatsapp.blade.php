@php
    $whatsappUrl = app(\App\Services\PedidoWhatsAppService::class)->generarUrlWhatsApp($order);
@endphp

<x-layouts.app :title="'Pedido #'.$order->id">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="avatar-circle bg-success-subtle text-success mx-auto mb-3" style="width:3.5rem;height:3.5rem;">
                            <x-icon name="check" class="icon-lg" />
                        </div>
                        <h1 class="h3 fw-bold">Pedido creado</h1>
                        <p class="text-muted">Revisa el detalle y continúa la conversación por WhatsApp.</p>

                        <div class="text-start border rounded p-3 my-4">
                            @foreach ($order->items as $item)
                                <div class="d-flex justify-content-between gap-3">
                                    <span>{{ $item->quantity }}x {{ $item->product_name }}</span>
                                    <span class="fw-semibold">{{ number_format((float) $item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>{{ number_format((float) $order->total, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-start small text-muted mb-4">
                            <div><strong>Nombre:</strong> {{ $order->customer_name }}</div>
                            <div><strong>Teléfono:</strong> {{ $order->customer_phone }}</div>
                            <div><strong>Zona:</strong> {{ ucfirst($order->delivery_zone) }}</div>
                            <div><strong>Dirección:</strong> {{ $order->delivery_address }}, {{ $order->delivery_city }}</div>
                            @if ($order->delivery_notes)
                                <div><strong>Notas:</strong> {{ $order->delivery_notes }}</div>
                            @endif
                        </div>

                        <p class="small text-muted mb-2">Serás dirigido a WhatsApp en unos segundos.</p>
                        <a href="{{ $whatsappUrl }}" class="btn btn-success btn-lg w-100" target="_blank" rel="noopener">
                            Continuar a WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.setTimeout(function () {
            window.location.href = @json($whatsappUrl);
        }, 4000);
    </script>
</x-layouts.app>

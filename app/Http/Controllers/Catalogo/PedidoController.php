<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePedidoRequest;
use App\Models\Product;
use App\Services\PedidoWhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\Order;

class PedidoController extends Controller
{
    public function __construct(private readonly PedidoWhatsAppService $pedidos) {}

    public function store(CreatePedidoRequest $request): JsonResponse
    {
        $producto = Product::findOrFail($request->integer('product_id'));

        $order = $this->pedidos->crearPedido(
            $producto,
            $request->integer('quantity'),
            $request->safe()->only([
                'customer_name', 'customer_phone', 'delivery_zone',
                'delivery_address', 'delivery_city', 'delivery_notes',
            ]),
        );

        return response()->json([
            'redirect_url' => route('pedidos.whatsapp', $order),
            'whatsapp_url' => $this->pedidos->generarUrlWhatsApp($order),
        ]);
    }

    public function whatsapp(int $pedido): View
    {
        $order = Order::with('items')->findOrFail($pedido);

        return view('pedidos.whatsapp', compact('order'));
    }
}

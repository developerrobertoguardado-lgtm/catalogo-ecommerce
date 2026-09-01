<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePedidoRequest;
use App\Models\Product;
use App\Services\PedidoWhatsAppService;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller
{
    public function __construct(private readonly PedidoWhatsAppService $pedidos) {}

    public function store(CreatePedidoRequest $request): JsonResponse
    {
        $producto = Product::findOrFail($request->integer('product_id'));

        $order = $this->pedidos->crearPedido($producto, $request->integer('quantity'));

        return response()->json([
            'whatsapp_url' => $this->pedidos->generarUrlWhatsApp($order),
        ]);
    }
}

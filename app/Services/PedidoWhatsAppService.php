<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\StoreSetting;

class PedidoWhatsAppService
{
    public function crearPedido(Product $product, int $quantity): Order
    {
        abort_if($quantity > $product->stock, 409, 'Stock insuficiente');

        $order = Order::create([
            'whatsapp_number' => StoreSetting::current()->whatsapp_number,
            'total' => $product->price * $quantity,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal' => $product->price * $quantity,
        ]);

        return $order;
    }

    public function generarUrlWhatsApp(Order $order): string
    {
        $order->loadMissing('items');

        $lineas = $order->items->map(
            fn ($item) => "{$item->quantity}x {$item->product_name} - $".number_format((float) $item->subtotal, 2)
        );

        $mensaje = $lineas->implode("\n")."\nTotal: $".number_format((float) $order->total, 2);

        $numero = preg_replace('/\D/', '', $order->whatsapp_number);

        return "https://wa.me/{$numero}?text=".urlencode($mensaje);
    }
}

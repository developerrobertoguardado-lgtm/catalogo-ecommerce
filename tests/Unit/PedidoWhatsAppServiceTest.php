<?php

use App\Models\Product;
use App\Models\StoreSetting;
use App\Services\PedidoWhatsAppService;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    StoreSetting::factory()->create(['whatsapp_number' => '51987654321']);
    $this->service = new PedidoWhatsAppService();
});

it('crea el pedido con el total correcto', function () {
    $producto = Product::factory()->create(['price' => 20, 'stock' => 5]);

    $order = $this->service->crearPedido($producto, 3);

    expect((float) $order->total)->toBe(60.0)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->product_name)->toBe($producto->name);
});

it('rechaza crear un pedido cuando la cantidad excede el stock', function () {
    $producto = Product::factory()->create(['stock' => 2]);

    try {
        $this->service->crearPedido($producto, 3);
        $this->fail('Se esperaba una excepción por stock insuficiente.');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(Response::HTTP_CONFLICT);
    }
});

it('genera la url de WhatsApp con el mensaje y número correctos', function () {
    $producto = Product::factory()->create(['name' => 'Lámpara', 'price' => 15]);

    $order = $this->service->crearPedido($producto, 2);
    $url = $this->service->generarUrlWhatsApp($order);

    expect($url)->toStartWith('https://wa.me/51987654321?text=')
        ->and(urldecode($url))->toContain('2x Lámpara')
        ->and(urldecode($url))->toContain('Total: $30.00');
});

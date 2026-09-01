<?php

use App\Models\Product;
use App\Models\StoreSetting;

beforeEach(function () {
    StoreSetting::factory()->create(['whatsapp_number' => '51999999999']);
});

it('registra un pedido y arma el link de WhatsApp correctamente', function () {
    $producto = Product::factory()->create(['price' => 50, 'stock' => 10]);

    $response = $this->postJson(route('pedidos.store'), [
        'product_id' => $producto->id,
        'quantity' => 2,
    ]);

    $response->assertOk()->assertJsonStructure(['whatsapp_url']);

    expect($response->json('whatsapp_url'))->toContain('wa.me/51999999999');

    $this->assertDatabaseHas('order_items', [
        'product_id' => $producto->id,
        'quantity' => 2,
        'unit_price' => 50,
        'subtotal' => 100,
    ]);
});

it('rechaza el pedido si la cantidad supera el stock disponible', function () {
    $producto = Product::factory()->create(['stock' => 1]);

    $this->postJson(route('pedidos.store'), [
        'product_id' => $producto->id,
        'quantity' => 5,
    ])->assertStatus(409);

    $this->assertDatabaseCount('orders', 0);
});

it('valida que el producto exista y la cantidad sea válida', function () {
    $this->postJson(route('pedidos.store'), [
        'product_id' => 999,
        'quantity' => 0,
    ])->assertJsonValidationErrors(['product_id', 'quantity']);
});

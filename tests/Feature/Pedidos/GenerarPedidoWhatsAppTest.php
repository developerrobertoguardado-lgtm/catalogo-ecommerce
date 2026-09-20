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
        'customer_name' => 'Ana Pérez',
        'customer_phone' => '999888777',
        'delivery_zone' => 'lima',
        'delivery_address' => 'Av. Principal 123',
        'delivery_city' => 'Lima',
        'delivery_notes' => 'Tocar el timbre',
    ]);

    $response->assertOk()->assertJsonStructure(['whatsapp_url', 'redirect_url']);

    expect($response->json('whatsapp_url'))->toContain('wa.me/51999999999');

    $this->assertDatabaseHas('order_items', [
        'product_id' => $producto->id,
        'quantity' => 2,
        'unit_price' => 50,
        'subtotal' => 100,
    ]);
    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Ana Pérez',
        'customer_phone' => '999888777',
        'delivery_zone' => 'lima',
        'delivery_address' => 'Av. Principal 123',
        'delivery_city' => 'Lima',
        'delivery_notes' => 'Tocar el timbre',
    ]);

    $this->get($response->json('redirect_url'))
        ->assertOk()
        ->assertSee('Ana Pérez')
        ->assertSee('Pedido creado')
        ->assertSee('4000', false);
});

it('crea el pedido si la cantidad supera el stock disponible', function () {
    $producto = Product::factory()->create(['stock' => 1]);

    $this->postJson(route('pedidos.store'), [
        'product_id' => $producto->id,
        'quantity' => 5,
        'customer_name' => 'Ana Pérez',
        'customer_phone' => '999888777',
        'delivery_zone' => 'lima',
        'delivery_address' => 'Av. Principal 123',
        'delivery_city' => 'Lima',
    ])->assertOk();

    $this->assertDatabaseHas('order_items', ['product_id' => $producto->id, 'quantity' => 5]);
});

it('valida que el producto exista y la cantidad sea válida', function () {
    $this->postJson(route('pedidos.store'), [
        'product_id' => 999,
        'quantity' => 0,
    ])->assertJsonValidationErrors(['product_id', 'quantity']);
});

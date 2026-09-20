<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create();
});

function crearPedido(): Order
{
    $pedido = Order::create([
        'whatsapp_number' => '51999999999',
        'total' => 0,
        'customer_name' => 'Cliente Prueba',
        'customer_phone' => '999888777',
        'delivery_zone' => 'lima',
        'delivery_address' => 'Calle Falsa 123',
        'delivery_city' => 'Lima',
        'status' => 'PENDIENTE',
    ]);

    $producto = Product::factory()->create(['name' => 'Producto Test', 'price' => 20, 'stock' => 5]);

    OrderItem::create([
        'order_id' => $pedido->id,
        'product_id' => $producto->id,
        'product_name' => $producto->name,
        'quantity' => 2,
        'unit_price' => 20,
        'subtotal' => 40,
    ]);

    $pedido->recalculateTotal();

    return $pedido->refresh();
}

it('edita direccion, nota y estado del pedido y persiste los cambios', function () {
    $pedido = crearPedido();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'EN_PROCESO',
            'notes' => 'Empacar con cuidado',
            'customer_name' => 'Cliente Prueba',
            'customer_phone' => '999888777',
            'delivery_zone' => 'lima',
            'delivery_address' => 'Av. Nueva 456',
            'delivery_city' => 'Lima',
            'delivery_notes' => 'Torre B piso 3',
            'items' => [
                ['id' => $pedido->items->first()->id, 'product_id' => $pedido->items->first()->product_id, 'quantity' => 2, 'unit_price' => 20],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    $pedido->refresh();
    expect($pedido->status)->toBe('EN_PROCESO')
        ->and($pedido->notes)->toBe('Empacar con cuidado')
        ->and($pedido->delivery_address)->toBe('Av. Nueva 456')
        ->and($pedido->delivery_notes)->toBe('Torre B piso 3');
});

it('cambia la cantidad de un item y recalcula el total', function () {
    $pedido = crearPedido();
    $item = $pedido->items->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'PENDIENTE',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 5, 'unit_price' => 20],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    $item->refresh();
    $pedido->refresh();
    expect($item->quantity)->toBe(5)
        ->and((float) $item->subtotal)->toBe(100.0)
        ->and((float) $pedido->total)->toBe(100.0);
});

it('agrega un item nuevo con el precio enviado y recalcula el total', function () {
    $pedido = crearPedido();
    $nuevoProducto = Product::factory()->create(['name' => 'Producto Nuevo', 'price' => 15]);

    $item = $pedido->items->first();
    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'PENDIENTE',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 2, 'unit_price' => 20],
                ['product_id' => $nuevoProducto->id, 'quantity' => 1, 'unit_price' => 15],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    $pedido->refresh();
    expect($pedido->items()->count())->toBe(2)
        ->and((float) $pedido->total)->toBe(55.0);

    $nuevo = $pedido->items()->where('product_id', $nuevoProducto->id)->first();
    expect($nuevo->product_name)->toBe('Producto Nuevo')
        ->and((float) $nuevo->subtotal)->toBe(15.0);
});

it('elimina items que no vienen en el request y recalcula el total', function () {
    $pedido = crearPedido();
    $nuevoProducto = Product::factory()->create(['name' => 'Producto B', 'price' => 10]);
    OrderItem::create([
        'order_id' => $pedido->id,
        'product_id' => $nuevoProducto->id,
        'product_name' => 'Producto B',
        'quantity' => 1,
        'unit_price' => 10,
        'subtotal' => 10,
    ]);
    $pedido->recalculateTotal();

    $item = $pedido->items()->where('product_id', $nuevoProducto->id)->first();
    $restante = $pedido->items()->where('id', '!=', $item->id)->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'PENDIENTE',
            'items' => [
                ['id' => $restante->id, 'product_id' => $restante->product_id, 'quantity' => $restante->quantity, 'unit_price' => 20],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    $pedido->refresh();
    expect($pedido->items()->count())->toBe(1)
        ->and((float) $pedido->total)->toBe(40.0);
});

it('registra el cambio de estado en el log de auditoria', function () {
    $pedido = crearPedido();
    $item = $pedido->items->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'ENVIADO',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 2, 'unit_price' => 20],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    $log = OrderStatusLog::where('order_id', $pedido->id)->first();
    expect($log)->not->toBeNull()
        ->and($log->from_status)->toBe('PENDIENTE')
        ->and($log->to_status)->toBe('ENVIADO')
        ->and($log->user_id)->toBe($this->admin->id);
});

it('no registra log cuando el estado no cambia', function () {
    $pedido = crearPedido();
    $item = $pedido->items->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'PENDIENTE',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 2, 'unit_price' => 20],
            ],
        ])
        ->assertRedirect(route('admin.pedidos.index'));

    expect(OrderStatusLog::where('order_id', $pedido->id)->count())->toBe(0);
});

it('rechaza cantidades menores a uno', function () {
    $pedido = crearPedido();
    $item = $pedido->items->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'PENDIENTE',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 0, 'unit_price' => 20],
            ],
        ])
        ->assertSessionHasErrors('items.0.quantity');
});

it('rechaza estados fuera del catalogo', function () {
    $pedido = crearPedido();
    $item = $pedido->items->first();

    $this->actingAs($this->admin)
        ->put(route('admin.pedidos.update', $pedido), [
            'status' => 'CANCELADO',
            'items' => [
                ['id' => $item->id, 'product_id' => $item->product_id, 'quantity' => 1, 'unit_price' => 20],
            ],
        ])
        ->assertSessionHasErrors('status');
});

it('muestra el modal de edicion con los datos del pedido', function () {
    $pedido = crearPedido();
    $pedido->load('items.product.primaryImage');

    $this->actingAs($this->admin)
        ->get(route('admin.pedidos.edit', $pedido))
        ->assertOk()
        ->assertSee('modal-order-'.$pedido->id, false)
        ->assertSee('name="status"', false)
        ->assertSee('name="notes"', false)
        ->assertSee('name="delivery_address"', false)
        ->assertSee("items[' + index + '][quantity]", false)
        ->assertSee('order-item-thumb', false);
});

it('bloquea la edicion cuando el pedido esta entregado', function () {
    $pedido = crearPedido();
    $pedido->update(['status' => 'ENTREGADO']);
    $pedido->load('items.product.primaryImage');

    $this->actingAs($this->admin)
        ->get(route('admin.pedidos.edit', $pedido))
        ->assertOk()
        ->assertSee('disabled', false)
        ->assertDontSee('data-bs-target="#addItemModal"', false);
});

it('el listado muestra la columna de estado y el boton de edicion', function () {
    crearPedido();

    $this->actingAs($this->admin)
        ->get(route('admin.pedidos.index'))
        ->assertOk()
        ->assertSee('Estado', false)
        ->assertSee('PENDIENTE', false)
        ->assertSee('btn-edit-order', false);
});

it('el modal muestra el icono de eliminar, la fila alineada y ancho reducido', function () {
    $pedido = crearPedido();
    $pedido->load('items.product.primaryImage');

    $html = $this->actingAs($this->admin)
        ->get(route('admin.pedidos.edit', $pedido))
        ->assertOk()
        ->getContent();

    // El path del icono "trash" viene con trazos, no vacío (d="")
    expect($html)->toContain('M3 6h18')
        ->and($html)->not->toContain('<path d=""');

    // Nombre, Teléfono y Estado comparten la misma fila con col-md-4
    expect(substr_count($html, 'col-md-4'))->toBeGreaterThanOrEqual(3)
        ->and(strpos($html, 'name="customer_name"'))->toBeLessThan(strpos($html, 'name="status"'));

    // El CSS define el ancho reducido del modal (63% escritorio)
    $css = file_get_contents(resource_path('css/app.css'));
    expect($css)->toContain('max-width: 63%');
});

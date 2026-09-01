---
name: testing-specialist
description: Provee convenciones y buenas prácticas de testing con Pest para
  EL PROYECTO Ecommerce Catálogo + Pedidos por WhatsApp. Úsalo siempre que se
  escriba, revise o corra tests en este repositorio — por ejemplo al agregar
  un test para un nuevo CRUD del admin, cubrir el flujo de "Comprar" por
  WhatsApp, o depurar un test que falla.
---

# testing-specialist

## Contexto de este proyecto
Framework: Pest (sobre PHPUnit/Laravel Testing), corriendo contra PostgreSQL
real vía Laravel Sail — no SQLite en memoria, para mantener paridad con
producción. Ver `/spec/05-testing-strategy.md` para la cobertura mínima
requerida.

## Convenciones específicas de este repo
- Ubicación: `tests/Feature/<Area>/...` para tests HTTP/integración
  (ej. `tests/Feature/Admin/ProductoCrudTest.php`,
  `tests/Feature/Catalogo/FiltrosTest.php`,
  `tests/Feature/Pedidos/GenerarPedidoWhatsAppTest.php`) y
  `tests/Unit/...` para lógica aislada
  (ej. `tests/Unit/PedidoWhatsAppServiceTest.php`).
- Nombres de test en español, describiendo el comportamiento de negocio en
  los mismos términos que `/spec/01-requirements.md` (ej.
  `it('no permite subir una quinta foto a un producto')`).
- `uses(RefreshDatabase::class)` en `tests/Pest.php` (o por archivo) para
  que cada test parta de una base de datos limpia.
- Factories (`ProductFactory`, `CategoryFactory`, `OrderFactory`, etc.) para
  el arrange de cada test — no crear registros a mano con `Model::create`
  salvo que el test necesite valores muy específicos.

## Estructura de carpetas relevante
```
tests/Feature/Admin/
tests/Feature/Catalogo/
tests/Feature/Pedidos/
tests/Unit/
database/factories/
tests/Pest.php
```

## Patrones a seguir
- Un test de feature por criterio de aceptación relevante de
  `/spec/01-requirements.md` (RF-01 a RF-15), no un único test gigante por
  controller.
- Cubrir siempre caso feliz + al menos un caso borde/error por
  funcionalidad (ej. `POST /pedidos` con `quantity` mayor al stock debe
  responder 409 y no crear el `Order`).
- Tests de `PedidoWhatsAppService` como unitarios puros (sin HTTP), verificando
  el formato exacto del mensaje y la URL `wa.me` generada.

## Errores comunes a evitar en este proyecto
- No testear contra SQLite en memoria "por velocidad" — este proyecto
  decidió explícitamente usar PostgreSQL real en tests (ADR en
  `/spec/02-architecture.md` implícito en el stack elegido) para detectar
  diferencias de tipos/comportamiento específicas de Postgres.
- No dejar sin test la regla de "máximo 4 fotos por producto" ni la de
  "cantidad no puede superar el stock" — son las reglas de negocio más
  fáciles de romper accidentalmente en un refactor futuro.

## Snippet de referencia
```php
// tests/Feature/Pedidos/GenerarPedidoWhatsAppTest.php
it('registra un pedido y arma el link de WhatsApp correctamente', function () {
    $product = Product::factory()->create(['price' => 50, 'stock' => 10]);
    StoreSetting::factory()->create(['whatsapp_number' => '51999999999']);

    $response = $this->postJson('/pedidos', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertOk()
        ->assertJsonStructure(['whatsapp_url']);

    expect($response->json('whatsapp_url'))->toContain('wa.me/51999999999');

    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 50,
        'subtotal' => 100,
    ]);
});

it('rechaza el pedido si la cantidad supera el stock disponible', function () {
    $product = Product::factory()->create(['stock' => 1]);

    $this->postJson('/pedidos', [
        'product_id' => $product->id,
        'quantity' => 5,
    ])->assertStatus(409);

    $this->assertDatabaseCount('orders', 0);
});
```

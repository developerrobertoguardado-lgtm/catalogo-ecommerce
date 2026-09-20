---
name: testing-specialist
description: Provee convenciones y buenas prácticas de testing con Pest para EL PROYECTO Ecommerce Catálogo + Pedidos por WhatsApp. Úsalo siempre que se escriba, revise o corra tests en este repositorio — por ejemplo al agregar un test para un nuevo CRUD del admin, cubrir el flujo de "Comprar" por WhatsApp, o depurar un test que falla.
---

# testing-specialist

## Contexto de este proyecto

Pest como framework de testing (sintaxis funcional, compatible con PHPUnit por debajo). PostgreSQL real en tests (no SQLite). Tests en `tests/Feature/` y `tests/Unit/`.

## Convenciones específicas de este repo

- **Sintaxis:** Funcional de Pest (`it('hace algo', function () {...})` o `test('descripción', ...)`).
- **Idioma:** Español para nombres de tests (describen comportamiento de negocio).
- **Ubicación:** `tests/Feature/<Area>/...` (ej. `tests/Feature/Admin/ProductoCrudTest.php`, `tests/Feature/Pedidos/GenerarPedidoWhatsAppTest.php`) y `tests/Unit/...` para lógica aislada.
- **Base de datos:** PostgreSQL real, usando `RefreshDatabase` trait.
- **Factories:** Siempre usar factories de Eloquent para datos de test, no crear manualmente.

## Cobertura mínima requerida

1. **CRUD de productos y categorías desde el admin:** Crear, editar, eliminar, validaciones de Form Request.
2. **Filtros de búsqueda del catálogo público:** Por nombre, categoría, rango de precio, combinados.
3. **Generación del mensaje y link de WhatsApp:** `POST /pedidos`, formato del mensaje, número de destino, codificación de la URL.
4. **Creación de Order/OrderItem:** Al hacer clic en "Comprar", incluyendo caso de `quantity` inválida o mayor al `stock`.
5. **Regla de máximo 4 ProductImage por producto** y unicidad de `is_primary`.

## Estructura de carpetas relevante

```
tests/
├── Feature/
│   ├── Admin/
│   │   └── ProductoCrudTest.php
│   ├── Catalogo/
│   │   └── FiltrosTest.php
│   └── Pedidos/
│       └── GenerarPedidoWhatsAppTest.php
└── Unit/
    └── PedidoWhatsAppServiceTest.php
```

## Patrones a seguir

- **`RefreshDatabase`:** Siempre en tests de Feature que tocan la BD.
- **Factories:** `Product::factory()->create()`, `Category::factory()->create()`, etc.
- **Testing de validación:** Usar `assertValidationErrors` o `post(...)->assertInvalid()`.
- **Testing de redirect:** Usar `assertRedirect` o `assertSessionHas`.
- **Testing de WhatsApp:** Mockear el servicio o verificar que el mensaje contiene los datos correctos.

## Errores comunes a evitar en este proyecto

- **No usar SQLite:** La spec define PostgreSQL real.
- **No olvidar `RefreshDatabase`:** Causa tests que pasan pero dejan datos residuales.
- **No testear implementación interna:** Testear comportamiento (qué hace), no cómo lo hace internamente.
- **No crear datos manualmente:** Usar factories siempre.

## Snippet de referencia

```php
// Ejemplo de test de Feature con Pest
it('crea un producto correctamente', function () {
    $category = Category::factory()->create();
    
    $response = $this->post(route('admin.productos.store'), [
        'category_id' => $category->id,
        'name' => 'Producto Test',
        'slug' => 'producto-test',
        'price' => 99.99,
        'stock' => 10,
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('products', [
        'name' => 'Producto Test',
        'price' => 99.99,
    ]);
});

// Ejemplo de test de validación
it('requiere nombre para crear producto', function () {
    $category = Category::factory()->create();
    
    $response = $this->post(route('admin.productos.store'), [
        'category_id' => $category->id,
        'name' => '',
        'price' => 99.99,
    ]);
    
    $response->assertInvalid(['name']);
});

// Ejemplo de test de Unit
it('arma el mensaje de WhatsApp correctamente', function () {
    $service = new PedidoWhatsAppService();
    $product = Product::factory()->create(['price' => 50]);
    $message = $service->buildMessage($product, 2);
    
    expect($message)->toContain('2 unidades');
    expect($message)->toContain('$100');
});
```
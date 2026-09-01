---
name: postgres-specialist
description: Provee convenciones, snippets y buenas prácticas específicas de
  PostgreSQL + Eloquent para EL PROYECTO Ecommerce Catálogo + Pedidos por
  WhatsApp. Úsalo siempre que se escriba, revise o depure código relacionado
  con migraciones, modelos Eloquent, seeders/factories o consultas a la base
  de datos en este repositorio — por ejemplo al crear una nueva entidad,
  modificar el esquema de `products`/`orders`, o resolver errores de
  consultas/relaciones.
---

# postgres-specialist

## Contexto de este proyecto
Base de datos PostgreSQL, corriendo en Docker vía Laravel Sail. Entidades
principales: `categories`, `products`, `product_images`, `orders`,
`order_items`, `store_settings` — ver `/spec/03-data-model.md` para el
esquema completo y las relaciones.

## Convenciones específicas de este repo
- Nombres de tabla en snake_case plural (`product_images`, `order_items`),
  igual que la convención por defecto de Eloquent — no renombrar tablas
  manualmente.
- Claves foráneas siempre con `constrained()->cascadeOnDelete()` salvo
  `order_items.product_id`, que debe ser `nullOnDelete()` porque
  `OrderItem` guarda un snapshot (`product_name`, `unit_price`) y debe
  sobrevivir aunque el producto se borre después.
- Montos monetarios (`price`, `total`, `unit_price`, `subtotal`) siempre
  `decimal(10, 2)`, nunca `float`/`double`, para evitar errores de
  redondeo.
- `StoreSetting` se modela como singleton: una sola fila en la tabla,
  accedida con `StoreSetting::first()` (crear un seeder que garantice que
  esa fila exista desde el primer `migrate --seed`).

## Estructura de carpetas relevante
```
database/migrations/
database/factories/
database/seeders/
app/Models/
```

## Patrones a seguir
- Un modelo Eloquent por entidad de `/spec/03-data-model.md`, con sus
  relaciones declaradas explícitamente (`Product::images()`,
  `Product::category()`, `Order::items()`).
- Regla "máximo 4 fotos por producto" y "una sola `is_primary = true`" se
  valida en el Service/Form Request de la capa de aplicación, no como
  constraint de base de datos (Postgres no expresa bien "máximo N filas
  relacionadas" de forma nativa; sí puede usarse un índice único parcial
  para `is_primary` si se prioriza integridad a nivel de BD).
- Factories de Eloquent para cada entidad, usadas tanto en tests
  (`tests/Feature`, `tests/Unit`) como en `DatabaseSeeder` para poblar datos
  de desarrollo.

## Errores comunes a evitar en este proyecto
- No usar `float` para columnas de dinero.
- No olvidar el índice en `products.slug` y `categories.slug` (son las
  columnas de lookup del catálogo público, se consultan en cada request).
- No hacer N+1 en el listado del catálogo: usar `Product::with(['category',
  'images'])` al listar, ya que cada card necesita la foto principal y el
  nombre de categoría.

## Snippet de referencia
```php
// database/migrations/xxxx_create_product_images_table.php
Schema::create('product_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('path');
    $table->unsignedTinyInteger('position')->default(0);
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
});

// app/Models/Product.php
public function images(): HasMany
{
    return $this->hasMany(ProductImage::class)->orderBy('position');
}

public function primaryImage(): HasOne
{
    return $this->hasOne(ProductImage::class)->where('is_primary', true);
}
```

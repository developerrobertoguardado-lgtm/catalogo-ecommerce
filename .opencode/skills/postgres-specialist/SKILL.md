---
name: postgres-specialist
description: Provee convenciones, snippets y buenas prácticas específicas de PostgreSQL + Eloquent para EL PROYECTO Ecommerce Catálogo + Pedidos por WhatsApp. Úsalo siempre que se escriba, revise o depure código relacionado con migraciones, modelos Eloquent, seeders/factories o consultas a la base de datos en este repositorio — por ejemplo al crear una nueva entidad, modificar el esquema de `products`/`orders`, o resolver errores de consultas/relaciones.
---

# postgres-specialist

## Contexto de este proyecto

PostgreSQL corriendo en Docker vía Laravel Sail (puerto 5434 para evitar conflictos). Eloquent como ORM. Base de datos de test es PostgreSQL real (no SQLite en memoria) para mantener paridad con producción.

## Convenciones específicas de este repo

- **Tablas:** snake_case en plural (`products`, `product_images`, `order_items`, `store_settings`).
- **Columnas:** snake_case (`is_primary`, `whatsapp_number`, `created_at`).
- **Migraciones:** Fechas en formato `YYYY_MM_DD_HHMMSS_`, nombres descriptivos (`create_products_table`, `add_stock_to_products_table`).
- **Factories:** Por cada entidad (`ProductFactory`, `CategoryFactory`, `OrderFactory`, `OrderItemFactory`). Usadas tanto en tests como en seeders de desarrollo.
- **Relaciones:** Definidas en Eloquent con `hasMany`, `belongsTo`, etc. No raw queries salvo necesidad extrema.

## Estructura de carpetas relevante

```
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_09_01_043106_create_categories_table.php
│   ├── 2026_09_01_043107_create_products_table.php
│   ├── 2026_09_01_043109_create_product_images_table.php
│   ├── 2026_09_01_043110_create_orders_table.php
│   ├── 2026_09_01_043111_create_order_items_table.php
│   └── 2026_09_01_043112_create_store_settings_table.php
├── factories/
└── seeders/
```

## Modelo de datos del proyecto

- **categories:** `id`, `name`, `slug`, `description`, `timestamps`
- **products:** `id`, `category_id` (FK), `name`, `slug`, `description`, `price`, `stock`, `is_active`, `timestamps`
- **product_images:** `id`, `product_id` (FK), `path`, `is_primary`, `orden`, `timestamps`
- **orders:** `id`, `customer_name`, `customer_phone`, `total`, `status`, `timestamps`
- **order_items:** `id`, `order_id` (FK), `product_id` (FK), `quantity`, `price`, `timestamps`
- **store_settings:** `id`, `key`, `value`, `timestamps`

## Patrones a seguir

- **Máximo 4 fotos por producto:** Validar en Form Request o en el modelo.
- **Foto principal:** `is_primary` en `product_images`. La foto en posición 0 se marca automáticamente.
- **Relaciones eager loading:** Usar `with()` en queries del catálogo para evitar N+1.
- **Transacciones:** Usar `DB::transaction()` para operaciones que crean `Order` + `OrderItem` juntas.

## Errores comunes a evitar en este proyecto

- **No usar SQLite en tests:** La spec define PostgreSQL real para tests.
- **No olvidar `RefreshDatabase`:** En tests de Feature que tocan la BD.
- **No crear columnas sin migración:** Siempre crear migración primero, ejecutar `php artisan migrate`.

## Snippet de referencia

```php
// Ejemplo de migración
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// Ejemplo de relación en modelo
class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('orden');
    }
    
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
}
```
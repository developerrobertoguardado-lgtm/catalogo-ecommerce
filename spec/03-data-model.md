# Modelo de datos

## Entidades

### Category
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | |
| name | string | |
| slug | string, único | usado en URLs de filtro |
| image_path | string, nullable | ruta de imagen opcional de la categoría |
| created_at / updated_at | timestamp | |

### Product
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | |
| category_id | bigint (FK → categories) | |
| name | string | |
| slug | string, único | usado en URL de detalle |
| description | text | |
| price | decimal(10,2) | |
| stock | integer | |
| created_at / updated_at | timestamp | |

### ProductImage
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | |
| product_id | bigint (FK → products) | |
| path | string | ruta/URL del archivo almacenado |
| position | integer | orden de despliegue en el slider |
| is_primary | boolean | exactamente una `true` por producto |
| created_at / updated_at | timestamp | |

Regla de negocio (aplicada en el Service/capa de validación, no en el
esquema): un producto admite **máximo 4** `ProductImage`, y como máximo una
marcada `is_primary = true`.

### Order
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | |
| whatsapp_number | string | número de destino usado al momento del pedido (snapshot de configuración) |
| total | decimal(10,2) | suma de los `OrderItem` asociados |
| created_at / updated_at | timestamp | |

### OrderItem
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | |
| order_id | bigint (FK → orders) | |
| product_id | bigint (FK → products, nullable) | nullable por si el producto se elimina luego |
| product_name | string | snapshot del nombre al momento del pedido |
| quantity | integer | |
| unit_price | decimal(10,2) | snapshot del precio al momento del pedido |
| subtotal | decimal(10,2) | `quantity * unit_price` |
| created_at / updated_at | timestamp | |

### StoreSetting (configuración de tienda)
| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint (PK) | fila única (patrón singleton) |
| store_name | string | |
| whatsapp_number | string | número destino por defecto |
| currency | string | ej. `USD`, `PEN` |
| logo_path | string, nullable | ruta del logo público de la tienda |
| created_at / updated_at | timestamp | |

### users (autenticación admin — tabla estándar de Laravel)
Se usa la tabla `users` generada por defecto en Laravel para el login del
administrador; no se define un modelo `Admin` separado en esta primera
versión.

## Relaciones
- `Category` 1—N `Product`
- `Product` 1—N `ProductImage` (máx. 4)
- `Product` 1—N `OrderItem`
- `Order` 1—N `OrderItem`

## Migraciones
Proyecto nuevo: las migraciones se crean desde cero al iniciar la
implementación (`categories`, `products`, `product_images`, `orders`,
`order_items`, `store_settings`), siguiendo exactamente las tablas
definidas arriba.

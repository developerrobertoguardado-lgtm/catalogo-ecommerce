# Contratos de rutas (web, server-rendered con Blade)

Este proyecto no expone una API REST/JSON como interfaz principal: es una
aplicación Laravel server-rendered (Blade + Alpine.js). Este documento lista
las rutas HTTP relevantes en lugar de contratos de API JSON. Si en el futuro
se necesita una API JSON (ej. para un app móvil), debe documentarse aquí
siguiendo el mismo formato `{{metodo}} {{ruta}}` por endpoint.

## Rutas públicas

### GET /
- **Descripción:** Home del catálogo — listado de productos en cards con
  filtros (nombre, categoría, rango de precio).
- **Response:** vista Blade `catalogo.index` con productos paginados.

### GET /productos/{slug}
- **Descripción:** Detalle de un producto — slider de fotos, descripción,
  precio, stock, botón "Comprar".
- **Response:** vista Blade `catalogo.show`.
- **Códigos de error:** 404 si el slug no existe.

### GET /categorias/{slug}
- **Descripción:** Catálogo filtrado por una categoría.
- **Response:** vista Blade `catalogo.index` con filtro aplicado.
- **Códigos de error:** 404 si la categoría no existe.

### POST /pedidos
- **Descripción:** Registra el `Order`/`OrderItem` correspondiente cuando el
  cliente hace clic en "Comprar", antes de redirigir a `wa.me`. Llamada vía
  Alpine.js (fetch) desde el detalle/card del producto.
- **Request:** `product_id` (int, requerido), `quantity` (int, requerido, ≥1)
- **Response:** JSON `{ "whatsapp_url": "https://wa.me/<numero>?text=<mensaje_codificado>" }`
- **Códigos de error:** 422 si `product_id` no existe o `quantity` inválida;
  409 si `quantity` excede el `stock` disponible.

## Rutas de administración (`/admin`, middleware `auth`)

### GET /admin/login, POST /admin/login, POST /admin/logout
- **Descripción:** Autenticación estándar de Laravel para el administrador.

### Recursos CRUD estándar (Route::resource)
- `GET|POST /admin/productos`, `GET|PUT|DELETE /admin/productos/{producto}`
  — CRUD de productos, incluye subida/gestión de hasta 4 `ProductImage`
  (reordenar, marcar principal) desde el mismo formulario.
- `GET|POST /admin/categorias`, `GET|PUT|DELETE /admin/categorias/{categoria}`
  — CRUD de categorías.
- `GET /admin/pedidos`, `GET /admin/pedidos/{pedido}` — listado y detalle de
  `Order`/`OrderItem` (solo lectura, no editable desde el admin).
- `GET /admin/configuracion`, `PUT /admin/configuracion` — edición de
  `StoreSetting` (número de WhatsApp, nombre de tienda, moneda).

Todas las rutas de administración devuelven vistas Blade y validan el
request con Form Requests dedicados (ver `AGENTS.md` → convenciones Laravel).

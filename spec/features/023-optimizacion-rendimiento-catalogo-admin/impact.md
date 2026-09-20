# Impact: Optimizacion de rendimiento y carga de vistas del catalogo y admin

## Modulos/entidades existentes que toca
- `vite.config.js` — code splitting con manualChunks
- `resources/js/app.js` — imports dinamicos
- `resources/css/app.css` — Google Fonts a link, Quill CSS condicional
- `app/Models/StoreSetting.php` — cache en current()
- `resources/views/components/producto-card.blade.php` — recibir $currency como prop
- `resources/views/catalogo/index.blade.php` — pasar $currency
- `resources/views/components/layouts/app.blade.php` — Google Fonts link, footer con variable
- `resources/views/components/layouts/admin.blade.php` — Google Fonts link, footer con variable
- `app/Http/Controllers/Catalogo/CatalogoController.php` — $currency + $footerCategorias
- `app/Http/Controllers/Admin/ProductController.php` — bulk update en recompactarPosiciones
- `app/Http/Controllers/Admin/OrderController.php` — whereIn antes del loop
- `database/migrations/*_add_performance_indexes.php` — indices de BD

## Endpoints nuevos o modificados
Ninguno. Todos los cambios son internos (cache, BD, frontend splitting).

## Cambios de datos
- Nueva migracion: indices en products.price, products.created_at, product_images(product_id, is_primary), orders.status, orders.created_at.
- Sin nuevos campos ni entidades.

## Riesgos / dependencias con otras specs
- Code splitting puede romper imports si algun modulo asume que Quill/SweetAlert2 estan en window global. Se verifica con tests y MCP.
- Cache de StoreSetting: si se cambia la config desde otro proceso, el cache queda stale por 1h. Aceptable para este contexto.
- Indices: operacion online-safe en PostgreSQL con tablas pequenas (<10k filas).
- No rompe RF-01 a RF-15. Sin cambios de funcionalidad visible.
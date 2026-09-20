# Impact: Mejora UI/UX del catálogo público

## Módulos/entidades existentes que toca
- `resources/views/components/layouts/app.blade.php` — layout base del catálogo
  público (header, remoción de barra superior y botón WhatsApp, integración de
  barra de búsqueda).
- `resources/views/catalogo/index.blade.php` — página índice del catálogo
  (grilla de productos, mantenimiento de los filtros).
- `resources/views/catalogo/show.blade.php` — detalle de producto (solo ajuste
  estético, sin romper el flujo de compra).
- `resources/views/components/producto-card.blade.php` — card de producto
  (estética inspirada en la web de referencia).
- `resources/css/app.css` — estilos globales (nuevos estilos del catálogo).
- `resources/views/auth/login.blade.php` y vistas admin — NO se tocan en esta
  feature (fuera de alcance: la petición es solo el catálogo público).

## Endpoints nuevos o modificados
No se crean ni modifican rutas/controllers. Cambios puramente de vistas y CSS.
La barra de búsqueda del header reutiliza el filtro `nombre` existente del
catálogo (GET `catalogo.index` con `?nombre=...`), no un endpoint nuevo.

## Cambios de datos
Ninguno. No hay migraciones, columnas, entidades ni seeders nuevos.

## Riesgos / dependencias con otras specs
- Depende de la estructura definida en `001-proyecto-base` (catálogo, filtros,
  layout `app`) y de la migración a Bootstrap `003-bootstrap-crud-modales`
  (ADR-004: solo Bootstrap 5, no mezclar con Tailwind).
- Riesgo bajo de romper el flujo de "Comprar" (`show.blade.php`) si se
  re-maqueta sin cuidado; se debe preservar el Alpine.js del stepper y el botón
  de compra.
- No afecta al admin ni al login.

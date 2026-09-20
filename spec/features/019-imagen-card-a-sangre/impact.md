# Impact: Imagen de card a sangre sin padding en costados ni superior

## Módulos/entidades existentes que toca
- `resources/css/app.css` — regla `.product-card-media .product-card-img`
  (quitado `padding` y `object-fit: contain` → `cover`).
- `resources/views/components/producto-card.blade.php` — sin cambios (el CSS
  cubre la presentación).
- `tests/Feature/Catalogo/FiltrosTest.php` — test de regresión del CSS.

## Endpoints nuevos o modificados
Ninguno.

## Cambios de datos
Ninguno. No se crean entidades ni campos (presentación pura).

## Riesgos / dependencias con otras specs
- Inversión de la decisión visual del fix 011 (`contain` con padding) para las
  cards; el `contain` queda reservado a usos que lo necesiten (por ejemplo el
  slider del detalle no se toca).
- No rompe `RF-01` (la card sigue mostrando foto, nombre, precio y categoría).
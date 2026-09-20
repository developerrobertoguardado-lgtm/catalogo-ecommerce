# Impact: Card de productos con layout estable, fallback de imagen y texto truncado

## Módulos/entidades existentes que toca
- `resources/views/components/producto-card.blade.php` — estructura flex de la
  card, placeholder de imagen, truncado de descripción.
- `resources/css/app.css` — reglas de line-clamp, fallback image, mt-auto.

## Endpoints nuevos o modificados
Ninguno.

## Cambios de datos
Ninguno. Solo presentación.

## Riesgos / dependencias con otras specs
- No rompe RF-01 (cards con foto/nombre/precio/categoría): todos los elementos
  se mantienen, solo cambia su distribución.
- No afecta feature 019 (imagen a sangre): la regla `.product-card-media .product-card-img`
  se mantiene; el fallback se muestra solo cuando la imagen falla.
- No afecta feature 020 (botón filtro icono): es un componente diferente.
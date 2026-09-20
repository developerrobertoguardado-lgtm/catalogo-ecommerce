# Impact: Botón de filtros como icono pequeño en la versión móvil

## Módulos/entidades existentes que toca
- `resources/views/catalogo/index.blade.php` — botón `catalog-filter-trigger`
  (se duplica: uno para móvil con icono, otro para desktop con texto).
- `resources/css/app.css` — nueva clase `.catalog-filter-icon-btn` para el
  estilo del icono móvil.

## Endpoints nuevos o modificados
Ninguno.

## Cambios de datos
Ninguno. Solo presentación.

## Riesgos / dependencias con otras specs
- No rompe RF-04 (filtros combinables): el offcanvas que abre es el mismo.
- No afecta feature 017 (catálogo responsive): el offcanvas se mantiene
  con `offcanvas-md offcanvas-start`.
- No afecta feature 018 (rediseño marketplace): chips de categorías intactos.
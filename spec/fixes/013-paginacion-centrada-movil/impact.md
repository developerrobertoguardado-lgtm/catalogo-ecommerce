# Impact: Paginación del catálogo centrada en la versión móvil

## Causa raíz
Laravel 12 (bootstrap-5) genera la paginación con dos contenedores: el bloque
móvil `d-sm-none` (`d-flex justify-content-between flex-fill`) con solamente la
`ul.pagination` como hijo. `justify-content-between` con un solo hijo lo alinea
al inicio; no hay regla que lo centre. El wrapper `.catalog-pagination` (fix 011)
solo estiliza pills, no la alineación del bloque móvil.

## Módulos/archivos afectados
- `resources/css/app.css` — regla para centrar la `ul.pagination` dentro del
  bloque móvil del paginador.

## Riesgo de la corrección
Bajo. Solo presentación; afecta únicamente el contenedor `.catalog-pagination`
del listado en viewports <576px. Los tests existentes verifican presencia de
`catalog-pagination` y estilos de pills; no dependen de la alineación.
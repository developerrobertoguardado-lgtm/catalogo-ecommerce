# Tasks: Botones de filtros no deben flotar ni sobreponerse al contenido

- [x] Quitar `position: sticky; bottom: 0; margin-top: auto` de `.sticky-filter-cta`
- [x] Dar fondo sólido al footer para que no opaque/deje entrever el contenido
       (evitar `bg-transparent` sobre el sticky o eliminar el transparent del card-footer)
- [x] Agregar test de regresión del CSS (`.sticky-filter-cta` sin `position: sticky`)
- [x] Build, suite y validación MCP móvil + escritorio con scroll
- [x] Actualizar bitácora en `/spec/progress-log.md`
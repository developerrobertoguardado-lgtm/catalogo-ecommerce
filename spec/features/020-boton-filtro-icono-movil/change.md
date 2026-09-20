# Change: Botón de filtros como icono pequeño en la versión móvil

## Problema / Usuario
El botón "Filtros" actual en móvil ocupa todo el ancho (`w-100`) y se muestra
como un botón de texto grande, lo que ocupa espacio vertical innecesario y no
se ve como un control de filtrado típico de aplicaciones móviles. El cliente
quiere un icono pequeño y compacto (tipo sliders/embudo) alineado a la derecha
que libere espacio y haga más evidente que es un filtro.

## Descripción funcional
- En móvil (≤767px): el botón de filtros se muestra como un **icono pequeño**
  (`sliders`) con fondo sutil tipo pill/rounded, alineado a la derecha de la
  fila de chips de categorías.
- El icono mantiene `data-bs-toggle="offcanvas"` y `aria-controls` para
  abrir el mismo offcanvas de filtros.
- En desktop (≥768px): el sidebar de filtros se mantiene como está (visible
  inline, no se toca el botón de desktop).
- El botón "Filtros" de texto completo se oculta en móvil y se muestra solo
  en desktop.

## Criterios de aceptación
- [ ] El botón de filtros móvil muestra solo el icono `sliders` (sin texto).
- [ ] El icono tiene fondo sutil (pill/rounded) y tamaño compacto.
- [ ] El icono está alineado a la derecha de la fila de chips.
- [ ] El icono abre el mismo offcanvas `#catalogFiltersPanel`.
- [ ] En desktop el sidebar de filtros no se ve afectado.
- [ ] Accesibilidad: `aria-label` en el botón icono.
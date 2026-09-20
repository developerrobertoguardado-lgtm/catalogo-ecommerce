# Change: Botones de filtros no deben flotar ni sobreponerse al contenido

## Reproducción
1. Abrir el catálogo público (`/`).
2. En escritorio: con el panel de filtros visible (sidebar), el footer con
   "Aplicar filtros" queda pegado al borde inferior del viewport mientras la
   card de filtros es más alta, flotando sobre la card (botón en mitad del
   contenido).
3. En móvil: abrir el offcanvas de filtros (botón "Filtros") y desplazar el
   contenido; la barra de botones se pega abajo y se superpone al acordeón de
   categorías/precio. Con fondo transparente, los controles subyacentes se ven
   a través de los botones.

## Comportamiento esperado
- Los botones "Aplicar filtros" y "Eliminar filtros" forman parte del flujo de
  la card de filtros: quedan al final de la card/sidebar de forma natural.
- No se superponen con el acordeón ni flotan sobre otros contenidos al hacer
  scroll, en móvil ni en escritorio.
- No hay contenido visible "atravesando" los botones.

## Comportamiento actual
- `.sticky-filter-cta` usa `position: sticky; bottom: 0` con `margin-top: auto`
  y fondo transparente (`jg-transparent` de Bootstrap sobre `.card-footer`).
- En escritorio el footer se adhiere al viewport y flota a media card.
- En móvil el footer se superpone con el acordeón al hacer scroll en el
  offcanvas; los controles de debajo son visibles a través de los botones.
# Impact: Botones de filtros no deben flotar ni sobreponerse al contenido

## Causa raíz
El footer `.sticky-filter-cta` se definió en el fix 008 con
`position: sticky; bottom: 0; margin-top: auto` pensando en anclarlo al final
del panel. Ese anclaje es contraproducente en ambos modos:
- Móvil: el contenedor de scroll es el `offcanvas-body`; el sticky mantiene la
  barra pegada al fondo del body y encima del acordeón al hacer scroll.
- Escritorio: la card de filtros es más alta que la barra de botones y, al ser
  el sticky respecto al viewport, el footer termina flotando sobre la card.
Además `bg-transparent` (Bootstrap) deja los controles subyacentes visibles.

## Módulos/archivos afectados
- `resources/css/app.css` — regla `.sticky-filter-cta` (posición sticky → flujo
  normal, fondo sólido).
- `resources/views/catalogo/index.blade.php` — sin cambios (el CSS cubre la
  corrección; `card-footer` permanece al final del form).

## Riesgo de la corrección
Bajo. Quitar `position: sticky` hace que los botones queden al final del
contenido del panel en vez de fijos: el usuario debe hacer scroll al fondo del
panel para aplicar filtros en móvil, comportamiento estándar de formularios y
esperado por el pedido (no flotar). Ningún test actual depende del sticky.
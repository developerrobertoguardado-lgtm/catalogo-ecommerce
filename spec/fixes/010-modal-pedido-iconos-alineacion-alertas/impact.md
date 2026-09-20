# Impact: Corregir iconos, alineación y ancho del modal de pedido + SweetAlert2

## Causa raíz
1. El componente `resources/views/components/icon.blade.php` define un mapa `$paths` que no incluye `trash`, `image` ni `save`; con `name` desconocido renderiza `<path d="">` → SVG sin trazos (invisible).
2. En `_modal.blade.php`, la fila superior usa `col-md-6` (cliente) con estructura label fw-semibold + labels small internos, y otro `col-md-6` (estado) con un solo label → los campos no comparten línea base.
3. `app.css` declara `.modal-xl { max-width: 90% }`.
4. `removeItem()` (componente Alpine `pedidoModal` en `index.blade.php`) usa `confirm()` nativo y el `catch` del fetch usa `alert()`, en lugar del wrapper SweetAlert2 global (`resources/js/alertas.js` → `window.alertas`).

## Módulos/archivos afectados
- `resources/views/components/icon.blade.php` (agregar trash, image, save)
- `resources/views/admin/pedidos/_modal.blade.php` (alineación de fila superior, icons ok)
- `resources/views/admin/pedidos/index.blade.php` (removeItem con alertas.confirm, catch con alertas.error)
- `resources/css/app.css` (max-width del modal: 63% escritorio / 95% móvil)

## Riesgo de la corrección
Bajo. Cambios de presentación y del wrapper de confirmación. Se conserva la lógica de eliminación (splice + recálculo) y la semántica de todos los campos. Los tests existentes no dependen del `confirm()` nativo ni del ancho.

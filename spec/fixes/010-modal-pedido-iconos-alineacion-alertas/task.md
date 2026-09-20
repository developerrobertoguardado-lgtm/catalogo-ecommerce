# Tasks: Corregir iconos, alineación y ancho del modal de pedido + SweetAlert2

- [x] Agregar paths `trash`, `image` y `save` al componente icon
- [x] Alinear Nombre/Teléfono/Estado en la misma fila (tres `col-md-4` con labels small idénticos)
- [x] Reducir ancho del modal a 63% en escritorio (95% en móvil, breakpoint 991.98px)
- [x] Reemplazar `confirm()` nativo por `window.alertas.confirm` en removeItem (async/await)
- [x] Reemplazar `alert()` por `window.alertas.error` en el catch del fetch
- [x] Test de regresión: path del icono trash con trazos (no `d=""`), fila col-md-4 compartida por customer_name/status, y `max-width: 63%` en el CSS
- [x] Validar con MCP (escritorio y móvil) y actualizar bitácora

# Validation: Corregir iconos, alineación y ancho del modal de pedido + SweetAlert2

## Test de regresión
Test feature del modal de edición que verifique:
- El SVG de eliminar item contiene trazos: el HTML incluye los nuevos names en el icon component (`trash` con `d="M3 6h18"` visible) — se comprueba que el markup renderizado del botón contiene un `<path d="M3 6h18...">` no vacío.
- La fila superior contiene Nombre, Teléfono y `name="status"` como columnas hermanas del mismo `row` (estructura col-md-4).
- El CSS define `max-width: 63%` para `.modal-xl`.

Sin el fix, el path queda vacío (`d=""`) y el test falla.

## Estado
- [x] Corregido
- [x] Testeado
- [x] Verificado en el comportamiento reportado

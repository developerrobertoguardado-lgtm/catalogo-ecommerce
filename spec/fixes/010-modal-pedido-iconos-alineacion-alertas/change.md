# Change: Corregir iconos, alineación y ancho del modal de pedido + SweetAlert2

## Reproducción
1. Abrir Admin → Pedidos → Editar (modal AJAX).
2. Los botones de eliminar item no muestran el icono (SVG vacío).
3. El combo de Estado queda más arriba que Nombre/Teléfono (descuadrado).
4. El modal ocupa ~90% del ancho de pantalla (demasiado grande).
5. Al eliminar un item aparece un `confirm()` nativo del navegador (debe ser SweetAlert2); igual con `alert()` al fallar la carga del modal.

## Comportamiento esperado
- Icono de basura visible en cada fila de item (y fallback de imagen con icono visible).
- Nombre, Teléfono y Estado alineados en la misma fila (misma altura de labels/inputs).
- Modal ~63% de ancho en escritorio (30% menos que 90%), ~95% en móvil.
- Confirmaciones y errores con SweetAlert2 (`window.alertas`).

## Comportamiento actual
- `<x-icon name="trash|image|save">` renderiza path vacío (no existen en el componente).
- El bloque Cliente tiene doble nivel de label; Estado solo uno → desalineación vertical.
- `.modal-xl { max-width: 90% }` en app.css.
- `removeItem()` usa `confirm()` y el catch del AJAX usa `alert()`.

# Change: Paginación del catálogo centrada en la versión móvil

## Reproducción
1. Abrir el catálogo público (`/`) en un viewport móvil (≤575px) o con el
   simulador de dispositivo de las DevTools.
2. Desplazarse al final del listado de productos.
3. Observar los botones "« Previous / Next »": quedan pegados a la izquierda,
   no centrados horizontalmente.

## Comportamiento esperado
- La navegación de paginación ("« Previous / Next »") está centrada
  horizontalmente dentro del contenedor del catálogo en móvil.

## Comportamiento actual
- En móvil Laravel renderiza `{{ $productos->links() }}` (bootstrap-5) con dos
  bloques; el visible (<576px) es `<div class="d-flex justify-content-between flex-fill d-sm-none">`
  que contiene solo la `ul.pagination`. Al tener un único hijo, el
  `justify-content-between` no lo centra y la `ul` queda a la izquierda.
# Change: Catálogo responsive con mayor visibilidad e iconos de carrito

## Problema / Usuario
Los clientes que navegan desde móviles encuentran que el catálogo, sus filtros,
las cards y el detalle de producto no están optimizados para pantallas pequeñas.
Además, los botones `Agregar` muestran un icono de WhatsApp que no representa
la acción visual del botón.

## Descripción funcional
Adaptar el catálogo público y el detalle de producto a móvil con buenas
prácticas UX/UI, mejorando la visibilidad de imágenes, nombres, precios,
filtros, cantidades y botones sin cambiar el comportamiento actual ni el
flujo de pedidos por WhatsApp. En móvil, los filtros se abrirán mediante un
control compacto tipo offcanvas/accordion y podrán cerrarse después de aplicar
los criterios. Los botones `Agregar` usarán un icono de carrito de compras.

## Criterios de aceptación
- El catálogo no presenta overflow horizontal en anchos móviles.
- En móvil existe un control claro para abrir los filtros y el panel no ocupa
  permanentemente el espacio principal de los productos.
- Los filtros conservan nombre, categoría, precio, parámetros GET y enlace
  `Eliminar filtros`.
- Las cards mejoran la visibilidad de imagen, nombre, precio, stock y botón
  `Agregar` en móvil.
- Los botones `Agregar` no muestran icono de WhatsApp y muestran un icono de
  carrito de compras.
- El detalle de producto se reorganiza correctamente en una columna en móvil,
  incluyendo slider, precio, stock, cantidad, botón de compra y modal de datos.
- Se conserva el comportamiento actual: rutas, filtros, cantidades, cálculos,
  registro del pedido y apertura de WhatsApp.
- En escritorio se conserva el diseño actual salvo los ajustes necesarios para
  compartir el comportamiento responsive.
- El cambio no agrega entidades, columnas ni endpoints.

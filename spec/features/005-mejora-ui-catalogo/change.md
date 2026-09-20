# Change: Mejora UI/UX del catálogo público

## Problema / Usuario
El catálogo público actual tiene un header con elementos que el vendedor no
quiere mostrar: una barra superior que dice "Pedidos directos por WhatsApp ·
Sin registro, sin pasarela de pago" y un botón "WhatsApp" en el header. Además
la estética no está alineada con el estilo visual limpio y moderno de la web de
referencia (`app.orderypro.com/tienda/mayoristas`). El usuario quiere una
experiencia de catálogo más pulida y profesional, enfocada en el logo de la
tienda, la búsqueda de producto y una grilla de cards atractiva y limpia.

## Descripción funcional
Rediseñar la maquetación y estilos del catálogo público (y su layout base)
tomando como referencia visual la web `app.orderypro.com/tienda/mayoristas`,
PERO con estas restricciones explícitas:

1. **No cambiar los filtros.** El formulario de filtros por nombre, categoría y
   rango de precio del catálogo se mantiene exactamente como está
   funcionalmente y en su ubicación (aside `col-md-3`). Solo se permite ajustar
   su estilo visual/estética sin alterar su comportamiento ni sus controles.
2. **Quitar la barra superior** que dice "Pedidos directos por WhatsApp · Sin
   registro, sin pasarela de pago" (el div `.bg-dark` del layout `app`).
3. **Quitar el botón "WhatsApp"** del header (el `a.btn.btn-success` con el icono
   de WhatsApp en la navbar).
4. **Header reducido:** el header queda solo con el **logo/nombre de la tienda** y
   la **barra de búsqueda de producto** del catálogo. Se quita el enlace
   "Catálogo" de la navbar (o se mantiene solo si no interfiere con el logo y la
   búsqueda; la prioridad es logo + búsqueda como lo pide el usuario).
5. Aplicar la estética de la web de referencia a las cards de producto y al
   layout general: grilla limpia de cards (foto arriba, nombre, precio debajo,
   botón de acción tipo "Agregar 🛒" / compra), tipografía y espaciado más
   livianos, imágenes en relación cuadrada, hover sutil.

## Criterios de aceptación
- CA-01: La barra superior oscura (`bg-dark`) con el texto "Pedidos directos por
  WhatsApp · Sin registro, sin pasarela de pago" desapareció por completo del
  catálogo público.
- CA-02: El botón "WhatsApp" verde ya no aparece en el header.
- CA-03: El header muestra solo el logo/nombre de la tienda y la barra de
  búsqueda de producto. No hay enlaces de navegación adicionales destacados en
  el header del catálogo.
- CA-04: Los filtros (nombre, categoría, rango de precio) siguen funcionando
  exactamente igual, en la misma posición y con los mismos controles que antes.
- CA-05: La grilla de productos conserva la apariencia de cards: foto arriba
  (relación cuadrada, object-fit cover), nombre, categoría y precio debajo, con
  hover sutil y accesible vía mouse y teclado.
- CA-06: El detalle de producto (`show`) mantiene su funcionalidad (slider,
  cantidad, botón "Comprar por WhatsApp") y solo ajusta su estética para
  consistencia con el nuevo estilo, sin romper el flujo de compra.
- CA-07: Se mantiene Bootstrap 5 como único framework CSS (no se reintroduce
  Tailwind ni otro framework).
- CA-08: El footer y el resto del layout se mantienen funcionales y estéticamente
  consistentes con el nuevo estilo.

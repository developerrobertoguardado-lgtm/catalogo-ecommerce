# Change: Mejora de UI — catálogo estilo ecommerce + admin estilo dashboard

## Problema / Usuario
La UI inicial (generada en el scaffold base) era funcional pero muy básica.
Se pidió mejorarla: el catálogo público debía verse como una tienda online
real (inspirado en el estilo de una demo de PrestaShop:
`https://prestashop.coderplace.in/PRS05/PRS05103/demo/en/3-clothes` — banner,
sidebar de filtros colapsable, cards con badges), y el panel admin debía
verse como un dashboard profesional (inspirado en el estilo del template
Vuexy: sidebar vertical, navbar superior, cards con métricas).

Nota: no se instaló ninguna skill "ui-ux-pro-max-skill" — no existe en este
proyecto ni es una skill estándar; tras aclarar con el usuario, se descartó
esa vía y se aplicaron las mejoras de UI directamente.

## Descripción funcional
Rediseñar el catálogo público y el panel admin con un estilo visual más
pulido, sin agregar funcionalidad falsa (ratings, wishlist, swatches) que no
existiera en el modelo de datos.

## Criterios de aceptación
- [x] Catálogo público (`/`, `/productos/{slug}`, `/categorias/{slug}`):
      banner superior, breadcrumbs, sidebar de filtros con secciones
      colapsables (Alpine `x-collapse`), cards de producto con badge de
      stock ("Agotado" / "Últimas unidades"), footer de 3 columnas
      (tienda, categorías reales, contacto WhatsApp real).
- [x] Detalle de producto: galería con miniaturas (no solo puntos), selector
      de cantidad con botones +/-, badge de stock, botón de compra con ícono
      de WhatsApp.
- [x] Panel admin: layout con sidebar vertical colapsable (persistida en
      `localStorage` vía Alpine `$persist`), navbar superior con dropdown de
      usuario, paleta violeta consistente, cards de estadísticas reales
      (total productos, sin stock, stock bajo; total pedidos, total
      facturado) en los listados de Productos y Pedidos.
- [x] Ningún elemento decorativo fue inventado sin datos reales: no se
      agregaron ratings, wishlist, comparador ni swatches de color porque no
      existen en el modelo de datos (`/spec/03-data-model.md`) — se
      mantiene la regla de "no half-finished features".
- [x] Todos los tests existentes (19) siguen pasando sin modificarlos.

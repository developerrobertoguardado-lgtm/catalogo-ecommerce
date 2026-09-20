# Impact: Catálogo responsive con mayor visibilidad e iconos de carrito

## Módulos/entidades existentes que toca
- `resources/views/catalogo/index.blade.php`: filtros responsive y layout del catálogo.
- `resources/views/catalogo/show.blade.php`: layout responsive del detalle y botones.
- `resources/views/components/producto-card.blade.php`: visibilidad de card e icono de carrito.
- `resources/views/components/icon.blade.php`: nuevo icono de carrito.
- `resources/css/app.css`: breakpoints, offcanvas/filtros, cards y detalle.
- Tests de catálogo existentes y nuevas regresiones responsive.

## Endpoints nuevos o modificados
No se agregan ni modifican endpoints. Se conservan las rutas actuales del
catálogo, filtros y pedidos por WhatsApp.

## Cambios de datos
No hay cambios de base de datos, entidades ni campos.

## Riesgos / dependencias con otras specs
- Depende del lenguaje visual actual del catálogo (`005`, `011`, `012`, `014`,
  `015`) y de Bootstrap 5.
- Debe conservar el flujo de pedido por WhatsApp definido en RF-06/RF-08.
- El panel de filtros móvil debe convivir con los acordeones independientes del
  filtro implementados previamente.
- Riesgo funcional bajo; riesgo visual medio por los cambios de breakpoints y
  visibilidad en cards/detalle.

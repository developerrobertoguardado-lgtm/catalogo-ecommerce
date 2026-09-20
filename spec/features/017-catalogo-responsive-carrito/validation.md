# Validation: Catálogo responsive con mayor visibilidad e iconos de carrito

## Criterios de aceptación a validar
- Catálogo sin overflow horizontal en 390px.
- Filtros accesibles mediante control móvil, aplicables y conservando sus
  parámetros GET.
- Cards con imagen, nombre, precio y `Agregar` legibles.
- Botones `Agregar` con icono de carrito y sin icono de WhatsApp.
- Detalle correctamente apilado en móvil, con compra y modal funcionales.
- Escritorio sin regresiones visuales o funcionales.

## Plan de testing
- Tests Feature del catálogo para clases responsive, control de filtros e
  icono de carrito.
- Tests de regresión para conservar parámetros de filtros y campos del modal.
- `npm run build` sin errores.
- Suite completa Pest.
- MCP en 1440px y 390px: overflow, apertura de filtros, cards, detalle,
  cantidad y modal de compra.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

# Validation: Rediseño marketplace responsive del catálogo

## Criterios de aceptación a validar
- Catálogo desktop y mobile con estructura marketplace y sin overflow.
- Drawer mobile usable, con scroll, cierre, filtros seleccionados y aplicar.
- Cards legibles con badges, precio, estado agotado e icono carrito.
- Detalle y modal de compra usables en ambos tamaños.
- Light/dark conserva estructura y usa colores configurados desde Admin.
- Navegación pública sin autenticación y flujo WhatsApp intacto.
- No aparecen funcionalidades fuera del catálogo.

## Plan de testing
- Tests Feature para markup responsive, filtros, cards, iconografía y detalle.
- Regresiones para parámetros GET, paginación, cantidades y modal de compra.
- Test de consumo de variables CSS dinámicas de `StoreSetting`.
- `npm run build` sin errores.
- Suite completa Pest.
- MCP en 1440px/390px para light/dark, drawer, cards, detalle, focus/overflow.

## Estado
- [ ] Spec aprobada
- [ ] Implementada
- [ ] Testeada
- [ ] Desplegada

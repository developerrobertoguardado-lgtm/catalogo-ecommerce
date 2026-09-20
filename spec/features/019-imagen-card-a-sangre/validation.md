# Validation: Imagen de card a sangre sin padding en costados ni superior

## Criterios de aceptación a validar
- Imagen `cover` sin padding/margin en costados ni superior, respetando el radio
  del card; badge visible; dark mode y detalle intactos.

## Plan de testing
- Test de regresión: el CSS de `.product-card-media .product-card-img` define
  `object-fit: cover` y no incluye padding.
- Suite completa (Pest).
- Validación visual en MCP: 390px light/dark y escritorio, comparando con la
  referencia.

## Estado
- [ ] Spec aprobada
- [ ] Implementada
- [ ] Testeada
- [ ] Desplegada
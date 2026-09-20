# Validation: Card de productos con layout estable, fallback de imagen y texto truncado

## Criterios de aceptación a validar
- Botón CTA al final de la card (flex layout). Fallback image con icono
  `image` visible cuando la img falla. Descripción truncada a 3 líneas.

## Plan de testing
- MCP 390px: verificar que el botón queda al final, el fallback funciona y
  el texto se trunca.
- MCP 1280px: mismo comportamiento en desktop.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada
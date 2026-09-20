# Validation: Botones de filtros no deben flotar ni sobreponerse al contenido

## Test de regresión
Test del catálogo que verifica en `resources/css/app.css` que la regla
`.sticky-filter-cta` no declare `position: sticky` ni `bottom: 0`, y que el
footedor del panel no use `bg-transparent` (el botón Aplicar queda en flujo
normal). Falla sin el fix porque `.sticky-filter-cta` sí concatena
`position: sticky`.

## Estado
- [x] Corregido
- [x] Testeado
- [x] Verificado en el comportamiento reportado
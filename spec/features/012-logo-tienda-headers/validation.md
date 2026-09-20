# Validation: Logo de tienda en headers

## Criterios de aceptación a validar
Validar carga, reemplazo, eliminación y validación de logos; fallback al nombre; visualización en headers público y administrativo; eliminación de la franja negra; proporciones; responsive; modo claro/oscuro; y conservación de operaciones existentes.

## Plan de testing
- Tests de request para formatos permitidos, tamaño máximo y archivo opcional.
- Tests de configuración para persistencia, reemplazo y eliminación del archivo anterior.
- Tests de vistas para logo y fallback en ambos layouts.
- Validación MCP de configuración, header público/admin y errores de validación.
- Validación responsive en móvil y ambos temas.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

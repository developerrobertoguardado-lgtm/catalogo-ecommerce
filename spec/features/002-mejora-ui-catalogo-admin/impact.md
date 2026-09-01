# Impact: Mejora de UI — catálogo estilo ecommerce + admin estilo dashboard

## Módulos/entidades existentes que toca
Solo capa de presentación: `resources/views/catalogo/*`,
`resources/views/admin/*`, `resources/views/components/layouts/*`. Ningún
modelo, controller ni ruta.

## Endpoints nuevos o modificados
Ninguno. Mismas rutas de `/spec/04-api-contracts.md`.

## Cambios de datos
Ninguno. Solo se agregó lógica de presentación (badges de stock derivados
del campo `stock` ya existente).

## Riesgos / dependencias con otras specs
- Dependencias nuevas agregadas: `@alpinejs/persist` (persistir el estado
  colapsado del sidebar admin) y `@alpinejs/collapse` (animar los grupos de
  filtros colapsables del catálogo).
- **Ambas dependencias fueron removidas después**, en
  `003-bootstrap-crud-modales`, al reemplazar Tailwind por Bootstrap (que
  cubre collapse/offcanvas nativamente) — esta spec queda como registro
  histórico de una decisión que ya no está vigente en el código actual.

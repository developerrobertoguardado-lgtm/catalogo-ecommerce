# Validation: Búsqueda y paginación en tablas CRUD

## Criterios de aceptación a validar
Verificar búsqueda por nombre y descripción, coincidencias parciales e insensibles a mayúsculas/minúsculas, filtros mediante `GET`, 10 registros por página, conservación del término al cambiar de página, listado completo con filtro vacío y mensaje `Sin resultados disponibles` cuando no haya coincidencias.

## Plan de testing
- Tests de feature para productos y categorías con coincidencias por nombre.
- Tests de productos con coincidencias por descripción y combinación de campos.
- Tests con diferentes mayúsculas/minúsculas y coincidencias parciales.
- Tests de paginación en 10 registros y persistencia del parámetro `buscar`.
- Tests de respuesta vacía con el texto exacto requerido.
- Validación MCP de formularios, URL, tabla y navegación responsive.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

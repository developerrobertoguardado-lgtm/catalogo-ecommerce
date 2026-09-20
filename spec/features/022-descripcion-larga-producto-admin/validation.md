# Validation: Descripción larga con texto enriquecido en admin de productos

## Criterios de aceptación a validar
- Editor Quill funcional en modal admin. Campo long_description persiste.
- Detalle público muestra HTML renderizado. Test Pest pasa.

## Plan de testing
- Test Pest: crear producto con long_description, verificar persistencia.
- MCP: abrir modal de crear producto, verificar que Quill carga, escribir
  contenido, guardar, verificar que se persiste y se muestra en el detalle.

## Estado
- [ ] Spec aprobada
- [ ] Implementada
- [ ] Testeada
- [ ] Desplegada
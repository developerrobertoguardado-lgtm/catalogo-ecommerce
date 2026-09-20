# Validation: Bloqueo de formularios durante el guardado

## Criterios de aceptación a validar
Validar bloqueo inmediato, texto `Guardando...`, deshabilitación de campos y botones, bloqueo de cierre/cancelación, prevención de doble envío, reactivación ante errores y aplicación en todos los formularios administrativos.

## Plan de testing
- Tests de vistas para verificar atributos y clases de bloqueo en formularios administrativos.
- Tests frontend/MCP para envío, estado bloqueado y prevención de doble clic.
- Tests de error para comprobar que los formularios pueden corregirse después de una respuesta inválida.
- Validación MCP en productos, categorías, configuración y login, incluyendo modales.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

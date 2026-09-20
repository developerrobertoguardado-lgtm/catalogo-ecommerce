# Validation: Datos de entrega antes del pedido por WhatsApp

## Criterios de aceptación a validar
Validar modal, campos obligatorios y opcionales, errores debajo de inputs, resumen del pedido, cancelación sin persistencia, estado `Creando pedido...`, persistencia de datos, página intermedia, mensaje de WhatsApp, creación sin stock suficiente y comportamiento responsive.

## Plan de testing
- Tests de request para campos obligatorios, zona válida, teléfono y notas opcionales.
- Tests de servicio/controlador para persistir datos de entrega y permitir stock cero o insuficiente.
- Tests de regresión para asegurar que cancelar no crea pedidos y que no hay envíos duplicados.
- Tests para URL/mensaje de WhatsApp con información de entrega.
- Validación MCP del modal, errores inline, resumen, redirección y vista móvil.

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

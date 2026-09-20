# Impact: Mejora visual del modal de compra

## Módulos/entidades existentes que toca
- Vista de detalle de producto y modal de compra.
- Estilos compartidos Bootstrap 5 y temas claro/oscuro.
- Script Alpine.js del flujo de compra.
- Página intermedia existente de redirección a WhatsApp.
- No modifica entidades ni campos.

## Endpoints nuevos o modificados
Ninguno. Se conserva `POST /pedidos` y la respuesta actual.

## Cambios de datos
Ninguno. Se usan los datos de entrega y pedido ya existentes.

## Riesgos / dependencias con otras specs
- Debe conservar la funcionalidad de `010-datos-entrega-pedido-whatsapp`.
- Debe conservar SweetAlert2 de `006` y el tema claro/oscuro de `008`.
- El temporizador de 4 segundos debe evitar aperturas duplicadas de WhatsApp.
- Debe mantenerse un enlace manual visible por si el navegador bloquea la apertura automática.

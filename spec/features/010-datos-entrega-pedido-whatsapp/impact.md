# Impact: Datos de entrega antes del pedido por WhatsApp

## Módulos/entidades existentes que toca
- `Order` y migración de `orders` para datos de cliente y entrega.
- `OrderItem` y `PedidoWhatsAppService` para crear el pedido completo.
- `PedidoController` y validación del request.
- Vista de detalle de producto y componentes SweetAlert2/Alpine existentes.
- Vista nueva de redirección a WhatsApp.

## Endpoints nuevos o modificados
- Se modifica `POST /pedidos` para recibir los datos de cliente y entrega.
- La respuesta JSON conserva el flujo de WhatsApp y agrega la URL de la página intermedia de redirección.
- Se agrega una página GET interna para mostrar el resumen y dirigir al usuario a WhatsApp, sin cambiar las rutas públicas existentes de catálogo.

## Cambios de datos
Se agregan a `orders` los campos `customer_name`, `customer_phone`, `delivery_zone`, `delivery_address`, `delivery_city` y `delivery_notes` nullable para notas.

La regla de stock cambia: el pedido se registra aunque el stock sea cero o la cantidad supere el stock. No se descuenta inventario ni se modifica el producto al crear el pedido.

## Riesgos / dependencias con otras specs
- Modifica intencionalmente la regla vigente del flujo de pedidos y los tests que rechazaban stock insuficiente.
- Debe conservar SweetAlert2 de `006`, el tema de `008` y los estilos de tablas de `009`.
- Requiere validación server-side con Form Request además de validación visual debajo de cada input.
- El mensaje y la página intermedia no deben exponer datos distintos a los confirmados por el cliente.

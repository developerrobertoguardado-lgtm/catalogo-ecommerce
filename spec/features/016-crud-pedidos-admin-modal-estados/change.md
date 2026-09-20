# Change: CRUD completo de pedidos en admin con modal, estados y auditoría

## Problema / Usuario
El administrador necesita gestionar los pedidos de forma completa desde el panel:
- Hoy el listado de pedidos muestra info básica pero no permite editar.
- Falta trazabilidad: no hay historial de cambios de estado ni notas internas.
- Para despachar, el admin debe poder corregir dirección, referencia, cambiar cantidades de items y recalcular totales sin restricciones de stock.
- El bloqueo de edición cuando el estado es `ENTREGADO` evita modificaciones accidentales en pedidos cerrados.

## Descripción funcional
Se transforma el listado de pedidos (`/admin/pedidos`) para que cada fila tenga un botón "Ver/Editar" que abre un **modal** con toda la información del pedido:
- Datos del cliente: nombre, teléfono.
- Datos de entrega: zona, dirección, ciudad, referencias.
- Nota interna del admin.
- Estado del pedido (selector): `PENDIENTE` → `EN PROCESO` → `ENVIADO` → `ENTREGADO`.
- Items del pedido: cada fila muestra miniatura de la foto del producto, nombre, precio unitario, input de cantidad (editable), subtotal calculado.
- Total del pedido recalculado en vivo al cambiar cantidades.
- Botones: "Agregar item" (busca producto y lo añade), "Eliminar item" por fila.
- Guardar: persiste estado, nota, datos de entrega, items actualizados y recalcula `total` en `Order`.
- Historial de cambios de estado: tabla `order_status_logs` con `order_id`, `from_status`, `to_status`, `user_id`, `created_at`.
- **Regla de bloqueo**: si `status = ENTREGADO`, el modal se abre en modo solo-lectura. Para editar, el admin debe cambiar el estado a uno anterior (ej. `ENVIADO`) y luego editar.

## Criterios de aceptación
- Modal abre con todos los campos cargados (cliente, entrega, nota, estado, items con miniatura).
- Cambiar cantidad de un item recalcula su subtotal y el total del pedido en vivo (Alpine.js).
- Botón "Agregar item" abre selector de productos (modal anidado o dropdown searchable), al confirmar añade fila con cantidad 1 y precio actual del producto.
- Botón "Eliminar item" quita la fila y recalcula total.
- Selector de estado permite transiciones: PENDIENTE → EN PROCESO → ENVIADO → ENTREGADO (no saltos hacia atrás salvo que admin cambie manualmente).
- Al guardar: se actualiza `Order` (status, notas, entrega, total) y `OrderItem` (quantity, subtotal). Si se agregaron items nuevos, se crean con `product_id`, `product_name`, `unit_price` (precio actual), `quantity`, `subtotal`.
- Se inserta registro en `order_status_logs` solo cuando cambia el `status`.
- Si `status = ENTREGADO`: inputs deshabilitados, botones de agregar/eliminar item ocultos, selector de estado visible para permitir "reabrir" (cambiar a ENVIADO).
- Validación: cantidad >= 1, total > 0.
- Tests de regresión: flujo completo editar pedido, cambiar estado, agregar/eliminar items, bloqueo ENTREGADO, auditoría.
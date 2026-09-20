# Validation: CRUD completo de pedidos en admin con modal, estados y auditoría

## Criterios de aceptación a validar
- Modal abre con todos los datos del pedido (cliente, entrega, nota, estado, items con miniatura).
- Input cantidad por item: al cambiar, recalcula subtotal y total en vivo (Alpine.js).
- Botón "Agregar item": busca producto, lo añade con cantidad 1 y precio actual, recalcula total.
- Botón "Eliminar item": quita fila, recalcula total.
- Selector estado: transiciones PENDIENTE → EN_PROCESO → ENVIADO → ENTREGADO.
- Guardar: persiste order (status, notes, delivery_*, total) + items (create/update/delete) + log de status si cambió.
- Pedido con `status = ENTREGADO`: modal en modo solo-lectura (inputs disabled, botones agregar/eliminar ocultos). Cambiar estado a anterior habilita edición.
- Auditoría: cada cambio de status inserta en `order_status_logs` (from, to, user, timestamp).
- Validación server-side: cantidad >= 1, total > 0, status enum válido.

## Plan de testing
1. **Test unitario**: `Order::recalculateTotal()` suma subtotales de items.
2. **Test feature** `PedidosCrudTest`:
   - `it_edits_order_delivery_and_notes()`
   - `it_changes_item_quantity_and_recalculates_total()`
   - `it_adds_new_item_to_order()`
   - `it_removes_item_from_order()`
   - `it_changes_status_and_logs_to_order_status_logs()`
   - `it_blocks_editing_when_delivered_allows_reopen()`
   - `it_validates_min_quantity_and_positive_total()`
3. **Test de integración**: flujo completo desde listado → modal → guardar → verifica BD.
4. **MCP visual**: escritorio (modal centrado, tabla items responsive) y móvil (scroll horizontal en tabla items, botones apilados).

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada
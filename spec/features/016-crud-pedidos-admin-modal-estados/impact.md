# Impact: CRUD completo de pedidos en admin con modal, estados y auditoría

## Módulos/entidades existentes que toca
- `Order` — nuevos campos: `status` (enum), `notes` (text nullable).
- `OrderItem` — lógica de recálculo de `subtotal` y `total` del pedido.
- `OrderController` — nuevos métodos: `show` (para modal), `update` (guardar todo), `statusLog` (historial).
- `OrderStatusLog` (nuevo modelo) — tabla `order_status_logs`.
- Vista `resources/views/admin/pedidos/index.blade.php` — agregar botón "Ver/Editar" y modal completo.
- Vista `resources/views/admin/pedidos/_modal.blade.php` (nueva) — modal con Alpine.js para recálculo en vivo.
- Migraciones: `add_status_notes_to_orders_table`, `create_order_status_logs_table`.
- Form Request: `UpdateOrderRequest` (validación completa).
- Test: `tests/Feature/Admin/PedidosCrudTest.php`.

## Endpoints nuevos o modificados
- `GET /admin/pedidos` — listado (existente, se agrega botón modal).
- `GET /admin/pedidos/{order}/edit` — devuelve HTML del modal (para carga vía AJAX/Alpine o inline).
- `PUT /admin/pedidos/{order}` — actualiza pedido completo (estado, nota, entrega, items, total).
- `GET /admin/pedidos/{order}/status-log` — historial de cambios de estado (opcional, para pestaña en modal).

> Se mantiene convención: CRUD en modales, no páginas separadas. El modal se carga inline en el `index` (data-bs-target="#modal-order-{{$order->id}}").

## Cambios de datos
### Nueva migración: `add_status_notes_to_orders_table`
```php
$table->enum('status', ['PENDIENTE', 'EN_PROCESO', 'ENVIADO', 'ENTREGADO'])
      ->default('PENDIENTE')
      ->after('total');
$table->text('notes')->nullable()->after('status');
```

### Nueva migración: `create_order_status_logs_table`
```php
$table->id();
$table->foreignId('order_id')->constrained()->cascadeOnDelete();
$table->enum('from_status', ['PENDIENTE', 'EN_PROCESO', 'ENVIADO', 'ENTREGADO'])->nullable();
$table->enum('to_status', ['PENDIENTE', 'EN_PROCESO', 'ENVIADO', 'ENTREGADO']);
$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
$table->timestamps();
```

### Order model
- `$fillable` agrega `status`, `notes`.
- Relación `statusLogs()` → `OrderStatusLog`.
- Accessor/mutator para recalcular total desde items.

### OrderItem
- No cambios de esquema. Lógica de recálculo en controller/service.

## Riesgos / dependencias con otras specs
- **Dependencia**: Feature 010 (`datos-entrega-pedido-whatsapp`) ya agregó campos de entrega. Esta feature los hace editables.
- **Dependencia**: Feature 008/009 (tema admin, tablas sash) — el modal debe usar estilos `sash-table-card`, `sash-actions`, botones Bootstrap.
- **Riesgo bajo**: No rompe catálogo público ni flujo WhatsApp. Solo admin.
- **Riesgo medio**: Recalcular total en vivo (Alpine.js) debe coincidir con cálculo en backend (PHP) al guardar.
- **Migraciones**: Requieren `php artisan migrate` en deploy.
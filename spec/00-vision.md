# Visión del proyecto

## Nombre
Ecommerce Catálogo + Pedidos por WhatsApp

## Objetivo
Permitir a un vendedor publicar un catálogo de productos online (con fotos,
categorías y filtros de búsqueda) y recibir pedidos directamente por WhatsApp,
sin necesidad de checkout propio ni integración con una pasarela de pago. El
cierre real de la venta ocurre por fuera del sistema, en la conversación de
WhatsApp con el vendedor.

## Alcance
- Catálogo público de productos con fotos (hasta 4 por producto), categorías
  y filtros de búsqueda (nombre, categoría, rango de precio).
- Página de detalle de producto con slider de fotos, descripción, precio,
  stock y botón "Comprar".
- Flujo de compra directo por producto (sin carrito multi-producto) que arma
  un mensaje prellenado y abre WhatsApp (`wa.me`) hacia un número configurable.
- Registro en base de datos (`Order`/`OrderItem`) de cada pedido enviado por
  WhatsApp, visible desde el panel administrativo.
- Panel administrativo con login: CRUD de productos (incluye gestión de hasta
  4 fotos, reordenar, marcar principal), CRUD de categorías, listado de
  pedidos, y configuración (número de WhatsApp destino, nombre de tienda,
  moneda).

### Fuera de alcance
- Checkout con carrito multi-producto.
- Pasarela de pago o cobro en línea.
- Gestión avanzada de inventario (reservas, backorders, alertas de stock).
- Notificaciones automáticas (email/SMS) al cliente o al vendedor.
- Multi-tienda / multi-tenant.
- Roles de administrador granulares (por ahora un solo rol admin).

## Usuarios / actores principales
- **Visitante / cliente**: navega el catálogo, filtra productos, ve el
  detalle y genera un pedido vía WhatsApp. No requiere cuenta ni login.
- **Administrador**: gestiona productos, categorías, pedidos y configuración
  de la tienda desde el panel autenticado.

## Contexto
Proyecto nuevo (MODO GREENFIELD) — SDD adoptado desde el inicio, el 2026-08-31.

# Requisitos

## Requisitos funcionales

### Catálogo público
- RF-01: Listar productos en formato "card" mostrando: foto principal,
  nombre, precio y categoría.
- RF-02: Cada producto admite hasta 4 fotos; en el detalle se muestran en un
  slider/carrusel, y en el card se muestra la primera foto (opcionalmente
  con hover para recorrer las demás).
- RF-03: CRUD de categorías desde el admin, con navegación/filtro por
  categoría en el catálogo público.
- RF-04: Filtros de búsqueda combinables por nombre, categoría y rango de
  precio (mínimo/máximo).
- RF-05: Página de detalle de producto con slider de fotos, descripción,
  precio, stock disponible y botón "Comprar".

### Flujo de compra vía WhatsApp
- RF-06: Cada producto tiene su propio botón "Comprar" (no hay carrito
  multi-producto).
- RF-07: Al hacer clic en "Comprar", el usuario puede ajustar la cantidad
  (por defecto 1) antes de confirmar.
- RF-08: Al confirmar, se arma un mensaje con nombre del producto, cantidad,
  precio unitario y total, y se abre `wa.me` con ese mensaje prellenado hacia
  el número de WhatsApp configurado.
- RF-09: No existe checkout ni pasarela de pago; el cierre de la venta ocurre
  por WhatsApp con el vendedor, fuera del sistema.
- RF-10: Cada pedido enviado se persiste como `Order` + `OrderItem` en base
  de datos, aunque el cierre de la venta sea externo.

### Panel administrativo
- RF-11: Login de administrador usando el sistema de autenticación de
  Laravel.
- RF-12: CRUD de productos, incluyendo subir/gestionar hasta 4 fotos por
  producto, reordenarlas y marcar cuál es la principal.
- RF-13: CRUD de categorías.
- RF-14: Listado de pedidos (`Order`/`OrderItem`) generados desde el
  catálogo, con detalle de producto, cantidad y total enviado por WhatsApp.
- RF-15: Configuración de tienda: número de WhatsApp de destino, nombre de la
  tienda y moneda.

## Requisitos no funcionales
- Rendimiento: el catálogo público debe cargar de forma fluida con imágenes
  optimizadas (lazy loading, tamaños razonables); no se requiere manejo de
  alto tráfico concurrente en esta etapa.
- Seguridad: rutas del panel admin protegidas por autenticación y
  middleware; protección CSRF estándar de Laravel; validación de inputs vía
  Form Requests; el número de WhatsApp y demás configuración sensible se
  gestiona desde el admin o `.env`, no hardcodeado en vistas.
- Disponibilidad: instancia única, sin requerimiento de alta disponibilidad
  en esta etapa.
- Escalabilidad: carga esperada baja/media (catálogo de un solo vendedor);
  no se requiere diseño para escalado horizontal inicial.

## Restricciones técnicas
- Backend y frontend integrados en Laravel 12 (Blade), estilos y componentes
  con Bootstrap 5 (ver ADR-004 en `/spec/02-architecture.md`), Alpine.js
  solo para la lógica sin equivalente nativo en Bootstrap.
- Base de datos PostgreSQL, corriendo en local vía Docker.
- ORM: Eloquent.
- Sin pasarela de pago ni proveedor de checkout de ningún tipo.
- El número de WhatsApp destino debe ser configurable (admin o `.env`), no
  fijo en código.
- CI obligatorio en GitHub Actions ejecutando tests en cada push/PR.
- Sin CD por ahora.

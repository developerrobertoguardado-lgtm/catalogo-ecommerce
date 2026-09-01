# Arquitectura

## Patrón arquitectónico
Estado actual: MVC simple (convención estándar de Laravel: Route → Controller
→ Model/View), con capa de **Repository** para acceso a datos y capa de
**Service** para lógica de negocio no trivial, aplicadas solo donde aportan
valor (ej. armado del mensaje de WhatsApp, registro de `Order`/`OrderItem`,
manejo de las 4 fotos de un producto). Se evita sobre-ingeniería: CRUDs
simples (categorías, configuración) pueden resolverse directo en el
Controller sin Repository/Service dedicados.

## Stack tecnológico
- Backend: Laravel 12 (PHP), Blade como motor de plantillas
- Frontend: Bootstrap 5 (estilos y componentes: modales, carousel, accordion,
  offcanvas, dropdown) + Alpine.js solo para la lógica sin equivalente nativo
  en Bootstrap (selector de cantidad y flujo de compra por WhatsApp en el
  detalle de producto)
- Base de datos: PostgreSQL + Eloquent ORM
- Entorno local: Docker vía Laravel Sail
- Testing: Pest
- CI: GitHub Actions

## Decisiones arquitectónicas (ADR)

### ADR-001: Compra directa por WhatsApp, sin carrito ni pasarela de pago
- **Contexto:** El vendedor cierra ventas por WhatsApp de forma manual y no
  quiere fricción de checkout ni integrarse con un proveedor de pagos.
- **Decisión:** Cada producto tiene su propio flujo de "Comprar" que arma un
  link `wa.me` con mensaje prellenado (producto, cantidad, precio, total).
  No hay carrito multi-producto ni checkout.
- **Consecuencias:** Simplifica drásticamente el sistema (sin estados de pago,
  sin webhooks de pasarela). El pedido queda registrado en BD solo como
  referencia para el admin; el estado real de la venta se gestiona fuera del
  sistema.

### ADR-002: Laravel Sail para el entorno local con Docker
- **Contexto:** Se requiere PostgreSQL en Docker en local, con la opción más
  simple de mantener posible.
- **Decisión:** Usar Laravel Sail (docker-compose oficial de Laravel) en
  lugar de un `docker-compose.yml` propio desde cero.
- **Consecuencias:** Menor mantenimiento y mejor soporte/documentación
  oficial; ligera pérdida de flexibilidad frente a un compose 100% custom,
  aceptable para el alcance del proyecto.

### ADR-003: Pest como framework de testing
- **Contexto:** Laravel 12 usa Pest por defecto en proyectos nuevos
  (`laravel new`); el usuario pidió Pest o PHPUnit, lo que sea más estándar.
- **Decisión:** Usar Pest.
- **Consecuencias:** Sintaxis más concisa y legible para tests
  funcionales/feature; sigue siendo compatible con PHPUnit por debajo, así
  que no hay riesgo de bloqueo por herramientas.

### ADR-004: Bootstrap 5 reemplaza a Tailwind CSS
- **Contexto:** El proyecto arrancó con Tailwind CSS. El usuario pidió
  explícitamente usar Bootstrap para los inputs del admin. Se le advirtió
  que mezclar ambos frameworks causa conflictos reales (reset de estilos
  duplicado, clases con el mismo nombre y distinto significado como
  `.container`/`.row`/`.card`). El usuario confirmó reemplazar Tailwind por
  Bootstrap **en todo el proyecto** (catálogo público + admin), no solo en
  los inputs.
- **Decisión:** Se removió Tailwind por completo (`package.json`,
  `vite.config.js`, `app.css`) y se instaló Bootstrap 5 (CSS + JS bundle con
  Popper). Todas las vistas se reescribieron con clases/componentes nativos
  de Bootstrap. La paginación de Laravel usa
  `Paginator::useBootstrapFive()`. Alpine.js se mantiene únicamente para la
  lógica que Bootstrap no cubre de forma nativa (stepper de cantidad + fetch
  a `/pedidos` en el detalle de producto).
- **Consecuencias:** Un solo framework de CSS en todo el proyecto (sin
  riesgo de conflicto). El CRUD de productos/categorías en el admin pasó de
  páginas completas a modales de Bootstrap (`data-bs-toggle="modal"`), con
  un modal por fila para "editar" y reapertura automática del modal
  correcto cuando falla la validación del servidor (vía un campo oculto
  `_modal` + `bootstrap.Modal(...).show()`). Las rutas
  `admin.productos.create/edit` y `admin.categorias.create/edit` ya no
  existen — el listado es la única vista, con los formularios embebidos en
  modales.

## Diagrama de capas
```
┌─────────────────────────────────────────────┐
│                Rutas (web.php)                │
│   públicas: /, /productos, /categorias/*      │
│   admin: /admin/* (middleware auth)           │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────▼───────────────────────────┐
│                Controllers                     │
│  CatalogoController, ProductoController,       │
│  PedidoController, admin/*Controller           │
└───────┬───────────────────────────┬────────────┘
        │                           │
┌───────▼─────────┐       ┌─────────▼───────────┐
│  Services         │       │  Form Requests      │
│  PedidoWhatsApp   │       │  validación de       │
│  Service           │       │  entradas             │
└───────┬─────────┘       └─────────────────────┘
        │
┌───────▼─────────────────────────────────────┐
│  Repositories (donde aporta valor)            │
│  ProductoRepository, PedidoRepository         │
└───────┬───────────────────────────────────────┘
        │
┌───────▼─────────────────────────────────────┐
│  Eloquent Models                               │
│  Product, Category, ProductImage, Order,       │
│  OrderItem                                     │
└───────┬───────────────────────────────────────┘
        │
┌───────▼─────────────────────────────────────┐
│              PostgreSQL (Docker/Sail)          │
└─────────────────────────────────────────────┘

Vistas: Blade + Bootstrap 5 (renderizado server-side, componentes nativos:
modal, carousel, accordion, offcanvas, dropdown) + Alpine.js solo para la
lógica sin equivalente nativo en Bootstrap
```

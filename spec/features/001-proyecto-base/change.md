# Change: Proyecto base (scaffold inicial completo)

## Problema / Usuario
Antes de poder construir features incrementales con `/spec-new`, el proyecto
necesitaba el esqueleto funcional completo descrito en `/spec/00-vision.md` a
`/spec/05-testing-strategy.md`: Laravel 12 instalado, base de datos
PostgreSQL vía Sail, el modelo de datos completo, el catálogo público con
filtros, el flujo de compra por WhatsApp, el panel administrativo con login,
y CI en GitHub Actions. No se hizo elicitación interactiva porque todas las
respuestas ya estaban resueltas en `/spec/*` — este documento registra lo que
se implementó, no un diseño nuevo.

## Descripción funcional
Scaffoldear Laravel 12 con Sail/PostgreSQL, implementar el modelo de datos
completo (`Category`, `Product`, `ProductImage`, `Order`, `OrderItem`,
`StoreSetting`), el catálogo público con filtros y compra por WhatsApp, el
panel admin con login y CRUD, y el pipeline de CI — todo tal como quedó
definido en `/spec/00-vision.md` a `/spec/05-testing-strategy.md`.

## Criterios de aceptación
- [x] Laravel 12 (pinneado, no la última versión mayor disponible) instalado
      con Blade + Alpine.js + Tailwind CSS v4 (superado luego por
      `003-bootstrap-crud-modales`, que reemplazó Tailwind por Bootstrap).
- [x] Laravel Sail configurado con PostgreSQL (`compose.yaml`), migrable con
      `docker compose` directo en Windows sin WSL2 (el script `sail` no
      corre en ese entorno).
- [x] Migraciones para `categories`, `products`, `product_images`, `orders`,
      `order_items`, `store_settings`, corridas y verificadas contra
      PostgreSQL real.
- [x] Modelos Eloquent con relaciones, factories y `DatabaseSeeder` con datos
      de ejemplo + usuario admin (`admin@example.com` / `password`).
- [x] Catálogo público: listado con filtros (nombre, categoría, rango de
      precio), detalle de producto con slider de fotos (Alpine.js).
- [x] Flujo de compra por WhatsApp: botón "Comprar" → `POST /pedidos` → crea
      `Order`/`OrderItem` → devuelve URL `wa.me` con mensaje prellenado;
      valida stock (409 si la cantidad excede el stock disponible).
- [x] Panel admin protegido por login custom (sin Breeze, por simplicidad):
      CRUD de productos (con hasta 4 fotos, validado en `UpdateProductRequest`),
      CRUD de categorías, listado/detalle de pedidos (solo lectura),
      configuración de tienda.
- [x] Pipeline `.github/workflows/ci.yml`: instala dependencias, levanta
      PostgreSQL como servicio, corre migraciones y tests en cada push/PR.
- [ ] CD — explícitamente fuera de alcance por ahora (ver ADR en
      `/spec/02-architecture.md`).

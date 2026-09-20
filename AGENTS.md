# AGENTS.md — Ecommerce Catálogo + Pedidos por WhatsApp

Este archivo da contexto a cualquier agente de IA (Claude, Cursor, etc.) que
trabaje en este repositorio. Léelo por completo antes de escribir código.

## Resumen del proyecto
Catálogo público de productos (con fotos, categorías y filtros) donde cada
producto tiene un botón "Comprar" que arma un mensaje prellenado y abre
WhatsApp con el vendedor. No hay carrito ni pasarela de pago: el cierre de la
venta ocurre por WhatsApp, fuera del sistema. Cada pedido enviado se registra
en base de datos (`Order`/`OrderItem`) para que el administrador lo vea desde
un panel con login, donde también gestiona productos, categorías y
configuración de la tienda (número de WhatsApp, nombre, moneda). Ver
`/spec/00-vision.md` para el detalle completo.

## Regla principal de SDD
**Toda feature nueva debe tener una spec en
`/spec/features/NNN-slug/` (con `change.md`, `impact.md`, `task.md`,
`validation.md`) antes de escribir código.** Usa el comando `/spec-new` para
generarla con preguntas guiadas. No implementes funcionalidad que no tenga
spec aprobada.

## Regla de bitácora (obligatoria)
`/spec/progress-log.md` es la memoria persistente del proyecto — sobrevive a
la compactación de contexto de cualquier IA que trabaje acá después.
- **Al empezar** cualquier tarea (feature, fix, revisión): leer las últimas
  entradas de `/spec/progress-log.md` antes de hacer nada, para saber en qué
  quedó el proyecto.
- **Al terminar** cualquier tarea: agregar una entrada nueva siguiendo el
  formato ya usado en el archivo, ANTES de dar la tarea por cerrada. Es
  append-only — nunca se edita ni se borra una entrada existente.

## Convenciones de código
- **Laravel:** PascalCase en modelos y controladores (`ProductController`,
  `ProductImage`), snake_case en columnas de base de datos y nombres de
  tabla en plural (`products`, `product_images`).
- Form Requests dedicados para toda validación de entrada admin (ej.
  `StoreProductRequest`, `UpdateProductRequest`) — no validar inline en el
  controller salvo casos triviales de una sola regla.
- Blade Resources/vistas parciales para evitar duplicar markup entre
  catálogo público y admin cuando aplique (ej. slider de fotos).
- Repository/Service solo donde hay lógica no trivial (armado de mensaje de
  WhatsApp, registro de `Order`/`OrderItem`, reglas de las 4 fotos por
  producto). CRUDs simples (categorías, configuración) van directo en el
  controller — no crear Repository/Service por sistema.
- Rutas en español para URLs públicas (`/productos`, `/categorias`) ya que
  es contenido orientado al cliente final; nombres de clases/métodos en
  inglés siguiendo la convención estándar de Laravel.
- **Solo Bootstrap 5 para CSS/componentes — no Tailwind.** El proyecto migró
  de Tailwind a Bootstrap (ver ADR-004 en `/spec/02-architecture.md`); no
  reintroducir utilidades de Tailwind ni mezclar ambos frameworks. Alpine.js
  se mantiene solo para la lógica sin equivalente nativo en Bootstrap
  (stepper de cantidad, fetch del flujo de compra por WhatsApp).
- **CRUD del admin (productos, categorías) es en modales, no en páginas
  separadas.** No existen rutas/vistas `create`/`edit` de página completa —
  el formulario vive embebido en el `index` como modal de Bootstrap
  (`data-bs-toggle="modal"`), un modal por fila para "editar". Si la
  validación falla, se usa el campo oculto `_modal` + un pequeño script que
  llama a `bootstrap.Modal(...).show()` para reabrir el modal correcto con
  los errores visibles.

## Arquitectura
MVC simple + Repository/Service selectivo. Stack: Laravel 12 (Blade) +
Bootstrap 5 + Alpine.js (solo para lógica puntual), PostgreSQL + Eloquent,
entorno local con Laravel Sail. Ver `/spec/02-architecture.md` para el
diagrama de capas completo y los ADRs (sin carrito/pasarela de pago, Sail,
Pest, Bootstrap sobre Tailwind).

## Cómo correr tests
```bash
./vendor/bin/sail artisan test          # macOS/Linux/WSL2
docker compose exec laravel.test php artisan test   # Windows sin WSL2
```
Ver `/spec/05-testing-strategy.md` para cobertura mínima requerida y
convenciones de nombres/ubicación.

## Cómo desplegar
Sin CD configurado todavía. Cuando se defina un destino, usar `/cicd` para
generar el pipeline correspondiente y actualizar este archivo y
`/spec/02-architecture.md`.

## Skills especializados disponibles en este proyecto
- `laravel-specialist` — convenciones de Laravel/Blade/Bootstrap/Alpine.js
  específicas de este proyecto (rutas, controllers, Form Requests, vistas,
  modales del admin).
- `postgres-specialist` — convenciones de modelado, migraciones y Eloquent
  para PostgreSQL en este proyecto.
- `testing-specialist` — convenciones de tests con Pest en este proyecto.

## Comandos SDD disponibles
Guardados en `.opencode/commands/` (formato de comandos de opencode):
- `/spec-new` — crea la spec de una nueva feature con preguntas guiadas
- `/spec-review` — revisa una spec existente contra el código actual
- `/fix` — flujo guiado para corregir un bug (repro → mini-spec → parche → test)
- `/test` — genera/corre tests según `/spec/05-testing-strategy.md`
- `/cicd` — genera o actualiza el pipeline de CI/CD (GitHub Actions)

Los skills especializados viven en `.claude/skills/`:
- `laravel-specialist`, `postgres-specialist`, `testing-specialist`

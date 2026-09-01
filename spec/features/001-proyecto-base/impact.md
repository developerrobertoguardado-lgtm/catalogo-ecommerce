# Impact: Proyecto base (scaffold inicial completo)

## Módulos/entidades existentes que toca
Todo el proyecto — es el scaffold inicial. Modelos: `Category`, `Product`,
`ProductImage`, `Order`, `OrderItem`, `StoreSetting`, `User` (admin).
Controllers: `Catalogo/CatalogoController`, `Catalogo/PedidoController`,
`Admin/ProductController`, `Admin/CategoryController`,
`Admin/OrderController`, `Admin/StoreSettingController`,
`Auth/AuthenticatedSessionController`.

## Endpoints nuevos o modificados
Ver `/spec/04-api-contracts.md` — implementado tal cual: rutas públicas de
catálogo (`/`, `/productos/{slug}`, `/categorias/{slug}`), `POST /pedidos`,
autenticación de admin (`/admin/login`, `/admin/logout`), y recursos CRUD
bajo `/admin/*`.

## Cambios de datos
Ver `/spec/03-data-model.md` — implementado tal cual: `Category`, `Product`,
`ProductImage` (máx. 4, con `is_primary`), `Order`, `OrderItem`,
`StoreSetting` (patrón singleton vía `StoreSetting::current()`).

## Riesgos / dependencias con otras specs
- El stack de frontend elegido acá (Tailwind CSS) fue reemplazado
  completamente en `003-bootstrap-crud-modales` — cualquier referencia a
  Tailwind en el código ya no aplica.
- Notas de implementación no cubiertas explícitamente en la spec original:
  - Laravel 12 ya trae Tailwind CSS v4 y `laravel/sail` por defecto en el
    skeleton; no fue necesario instalarlos aparte.
  - Laravel 12+ ya no registra alias globales de facades (`Storage`, etc.) —
    las vistas Blade usan el namespace completo
    (`\Illuminate\Support\Facades\Storage::url(...)`).
  - Se usó un `AuthenticatedSessionController` + `LoginRequest` mínimos y
    propios en vez de instalar Laravel Breeze completo, para evitar traer
    registro/perfil/verificación de email que no están en el alcance (un
    solo admin, creado por seeder).

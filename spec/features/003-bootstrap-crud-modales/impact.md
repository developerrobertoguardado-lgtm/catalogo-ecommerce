# Impact: Migración a Bootstrap + CRUD del admin en modales

## Módulos/entidades existentes que toca
- Frontend completo: `package.json`, `vite.config.js`, `resources/css/app.css`,
  `resources/js/app.js`, todas las vistas Blade (catálogo + admin).
- `App\Http\Controllers\Admin\ProductController` y `CategoryController`
  (métodos `create`/`edit` eliminados).
- `routes/web.php` (rutas `create`/`edit` de productos y categorías
  eliminadas de los `Route::resource`).
- `App\Providers\AppServiceProvider` (agregado `Paginator::useBootstrapFive()`).

## Endpoints nuevos o modificados
- Eliminadas: `admin.productos.create`, `admin.productos.edit`,
  `admin.categorias.create`, `admin.categorias.edit`.
- Sin cambios: `store`, `update`, `destroy`, `index` de ambos recursos.

## Cambios de datos
Ninguno.

## Riesgos / dependencias con otras specs
- Esta spec **reemplaza por completo** la decisión de frontend tomada en
  `001-proyecto-base` (Tailwind CSS) y revierte las dependencias agregadas
  en `002-mejora-ui-catalogo-admin` (`@alpinejs/persist`,
  `@alpinejs/collapse`) — ya no se usan.
- Documentado como ADR-004 en `/spec/02-architecture.md`.
- Notas de implementación:
  - Bootstrap no tiene una utilidad de tamaño de ícono equivalente a los
    `h-* w-*` de Tailwind; se agregaron 3 clases CSS pequeñas y explícitas
    (`.icon-sm/.icon-md/.icon-lg`) en `app.css` como complemento — no es
    "mezclar frameworks", es CSS de soporte que cualquier proyecto Bootstrap
    necesita para iconografía SVG custom.
  - El componente `<x-icon>` (SVGs propios, sin dependencia externa) no
    necesitó cambios internos, solo las clases con las que se invoca.
  - Los mensajes de validación siguen en inglés (Laravel no publica archivos
    de idioma por defecto) — esto es preexistente al proyecto, no algo que
    esta feature haya introducido o roto.

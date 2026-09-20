# Impact: Descripción larga con texto enriquecido en admin de productos

## Módulos/entidades existentes que toca
- `database/migrations/xxxx_create_products_table.php` — migración para
  agregar campo `long_description`.
- `app/Models/Product.php` — agregar `long_description` al `$fillable`.
- `app/Http/Requests/Admin/StoreProductRequest.php` — agregar validación.
- `app/Http/Requests/Admin/UpdateProductRequest.php` — agregar validación.
- `resources/views/admin/products/index.blade.php` — editor Quill en modal.
- `resources/views/catalogo/show.blade.php` — mostrar long_description.
- `resources/js/app.js` — importar e inicializar Quill.
- `resources/css/app.css` — estilos del editor Quill.
- `package.json` — dependencia `quill`.

## Endpoints nuevos o modificados
Ninguno. Los endpoints de CRUD de productos ya existen.

## Cambios de datos
- Nueva migración: `long_description` (text, nullable) en `products`.

## Riesgos / dependencias con otras specs
- No rompe RF-01 (cards): la descripción larga es para el detalle, no el card.
- No rompe RF-05 (detalle): se agrega debajo de la descripción existente.
- Dependencia: Quill.js vía npm (nueva dependencia).
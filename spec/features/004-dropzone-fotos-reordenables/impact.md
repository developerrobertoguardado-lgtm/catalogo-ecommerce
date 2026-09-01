# Impact: Dropzone de fotos con reordenamiento por arrastre

## Módulos/entidades existentes que toca
- `resources/views/components/fotos-dropzone.blade.php` (nuevo componente)
- `resources/js/fotos-dropzone.js` (nueva lógica Alpine, registrada en `app.js`)
- `resources/views/admin/productos/_campos.blade.php` (usa el nuevo componente)
- `App\Http\Controllers\Admin\ProductController` (nuevo método
  `sincronizarFotos()` + fallback `agregarFotosSinOrden()`)

## Endpoints nuevos o modificados
Ninguno. Mismos endpoints `POST admin/productos` y `PUT admin/productos/{id}`
— se agrega un campo más al payload (`orden_fotos`, JSON con tokens
`existing:<id>` / `new:<índice>`) que el controller interpreta para
reordenar/crear las fotos en la posición correcta.

## Cambios de datos
Ninguno — se apoya en las columnas `position` e `is_primary` que ya existían
en `product_images` (`/spec/03-data-model.md`). Lo que cambia es que ahora
son mutables después de la subida inicial (reordenables), y `is_primary` se
deriva siempre de `position === 0` en vez de fijarse una sola vez.

## Riesgos / dependencias con otras specs
- Depende del CRUD en modales de `003-bootstrap-crud-modales` (el dropzone
  vive dentro de esos modales, reutiliza `_campos.blade.php`).
- Sin riesgo de regresión en el camino sin `orden_fotos`: se mantiene el
  comportamiento anterior a esta spec como fallback explícito
  (`agregarFotosSinOrden()`), cubierto por los tests preexistentes de
  `001-proyecto-base`.

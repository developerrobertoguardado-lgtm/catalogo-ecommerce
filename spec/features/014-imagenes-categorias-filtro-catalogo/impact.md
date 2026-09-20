# Impact: Imágenes de categorías y filtro visual en catálogo

## Módulos/entidades existentes que toca
- `Category` y formulario CRUD administrativo.
- Catálogo público y componente/listado de filtros.
- Almacenamiento público de imágenes.
- Estilos Bootstrap 5 y temas claro/oscuro.

## Endpoints nuevos o modificados
Se reutilizan los endpoints actuales `POST/PUT /admin/categorias` con `multipart/form-data`. No se crean rutas nuevas ni se modifica la consulta pública existente.

## Cambios de datos
Se agrega el campo nullable `image_path` en `categories` para almacenar la ruta de la imagen.

## Riesgos / dependencias con otras specs
- Debe conservar la búsqueda y paginación de `007`.
- Debe conservar el estilo de tablas de `009`, tema claro/oscuro de `008` y alertas de `006`.
- Debe mantener el bloqueo de formularios de `013` y el drag & drop del logo como patrón de UX.
- La imagen genérica debe estar disponible sin depender de un archivo subido por el usuario.

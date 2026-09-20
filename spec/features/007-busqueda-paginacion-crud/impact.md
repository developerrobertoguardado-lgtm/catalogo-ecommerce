# Impact: Búsqueda y paginación en tablas CRUD

## Módulos/entidades existentes que toca
- `ProductController` y consulta de productos del panel admin.
- `CategoryController` y consulta de categorías del panel admin.
- Vistas Blade de tablas CRUD de productos y categorías.
- Componentes o estilos Bootstrap de filtros y paginación.

## Endpoints nuevos o modificados
Se reutilizan los endpoints `GET` actuales de los índices administrativos. Se agregan parámetros de consulta, por ejemplo `?buscar=texto&page=2`, sin modificar métodos, rutas base ni respuestas de escritura.

## Cambios de datos
Ninguno. Se consultan los campos existentes; no se crean tablas, entidades ni columnas.

## Riesgos / dependencias con otras specs
- Debe conservar la paginación Bootstrap 5 existente.
- Las consultas deben agrupar correctamente las condiciones `name`/`description` sin alterar otros filtros.
- PostgreSQL puede requerir expresiones compatibles con búsqueda insensible a mayúsculas (`ILIKE` mediante Eloquent).
- Debe conservarse el comportamiento de modales y mensajes SweetAlert2 de la feature `006`.

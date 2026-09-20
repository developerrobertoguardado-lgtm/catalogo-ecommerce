# Change: Búsqueda y paginación en tablas CRUD

## Problema / Usuario
Los usuarios del administrador necesitan encontrar productos y categorías de forma más rápida y cómoda cuando las tablas contienen muchos registros.

## Descripción funcional
Agregar filtros de búsqueda mediante `GET` en las tablas CRUD existentes. La búsqueda debe consultar nombre y descripción, permitir encontrar coincidencias parciales sin distinguir mayúsculas/minúsculas y conservar el término al cambiar de página. Los resultados se mostrarán paginados con 10 registros por página.

Si el filtro está vacío se mostrarán todos los registros. Si no existen coincidencias, la tabla mostrará exactamente `Sin resultados disponibles`.

## Criterios de aceptación
- Las tablas CRUD de productos y categorías incluyen un campo de búsqueda visible.
- La búsqueda de productos consulta nombre y descripción.
- La búsqueda de categorías consulta su nombre y, si aplica, la información textual disponible del registro.
- La búsqueda funciona parcialmente y sin distinguir mayúsculas/minúsculas.
- El filtro se envía mediante `GET` y es visible en la URL.
- Cada tabla muestra 10 registros por página.
- El término de búsqueda se conserva al navegar entre páginas.
- Un filtro vacío muestra todos los registros paginados.
- Sin coincidencias se muestra `Sin resultados disponibles` dentro de la tabla.
- Las rutas y respuestas existentes se mantienen compatibles.

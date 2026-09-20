# Change: Imágenes de categorías y filtro visual en catálogo

## Problema / Usuario
Los usuarios necesitan identificar y seleccionar categorías con mayor facilidad al buscar productos en el catálogo.

## Descripción funcional
Permitir que el administrador cargue, reemplace y elimine opcionalmente una imagen de cada categoría. En el catálogo, cada categoría se mostrará con una imagen circular pequeña; si no tiene imagen, se usará una imagen genérica por defecto.

El filtro seleccionado se indicará sombreando tenuemente la categoría y usando texto semibold, reemplazando visualmente el radio button sin cambiar la lógica existente. Se conservará el botón para eliminar filtros, la búsqueda actual, las rutas, consultas y operaciones.

## Criterios de aceptación
- El CRUD de categorías permite cargar una imagen opcional.
- Se aceptan formatos de imagen definidos por la validación del formulario y se conserva el límite establecido.
- La imagen puede reemplazarse y eliminarse desde el CRUD.
- La eliminación borra la referencia de base de datos y el archivo almacenado.
- El catálogo muestra una imagen circular pequeña por categoría.
- Las categorías sin imagen muestran un fallback genérico.
- La categoría seleccionada tiene sombreado tenue y texto semibold.
- El radio button deja de ser el indicador visual principal.
- El botón `Eliminar filtros` permanece disponible y operativo.
- Sin categoría se muestran los productos normalmente.
- Sin productos se muestra `No hay productos encontrados`.
- Se mantienen intactas rutas, consultas y operaciones.
- El filtro se adapta correctamente a móvil sin desbordamiento.

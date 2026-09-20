# Impact: Inputs del modal de compra a ancho completo

## Causa raíz
Los campos de nombre, teléfono, zona y ciudad usan clases Bootstrap `col-md-6`, mientras dirección y notas usan `col-12`. Desde el breakpoint md los primeros campos ocupan media fila y no el ancho completo del modal.

## Módulos/archivos afectados
- `resources/views/catalogo/show.blade.php`: estructura de columnas de los campos del modal.
- MCP y test de regresión visual del modal.

## Riesgo de la corrección
Bajo. Solo se cambia la distribución visual de los campos; no se modifican nombres, validaciones, datos, endpoint ni lógica de pedido.

# Impact: Corregir contraste de tablas en modo oscuro

## Causa raíz
Bootstrap aplica variables de fondo propias de `.table` y `.table-hover` a filas y celdas. Las reglas existentes cambiaban el texto y algunas variables, pero no sobrescribían el fondo efectivo de las celdas del cuerpo; por eso permanecía blanco en modo oscuro.

## Módulos/archivos afectados
- `resources/css/app.css`: variables y fondos efectivos de tablas Sash en modo oscuro.
- Tablas administrativas de productos, categorías, pedidos y detalle de pedido, que comparten las clases `sash-table`.
- MCP y tests de regresión visual.

## Riesgo de la corrección
Bajo. Solo se modifican colores de tablas bajo `html[data-theme='dark']`; el tema claro, datos, consultas y operaciones no cambian.

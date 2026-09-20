# Impact: Sistema global de alertas SweetAlert2

## Módulos/entidades existentes que toca
- Layouts Blade globales y de administración.
- Operaciones CRUD de productos y categorías.
- Operaciones de pedidos y configuración.
- Scripts frontend compartidos.
- No modifica entidades ni tablas de base de datos.

## Endpoints nuevos o modificados
No se crean ni modifican endpoints. Se reutilizan los endpoints actuales y se adapta únicamente la presentación de sus respuestas en el frontend.

## Cambios de datos
Ninguno. No se agregan campos, tablas ni entidades.

## Riesgos / dependencias con otras specs
- Requiere integrar SweetAlert2 con el bundle frontend actual sin reintroducir Tailwind.
- Debe conservar el flujo de modales Bootstrap y Alpine.js donde ya existe lógica equivalente.
- Las confirmaciones deben convivir con los formularios HTML y no depender exclusivamente de fetch.
- Deben revisarse mensajes flash, errores de validación y confirmaciones inline para evitar alertas duplicadas.

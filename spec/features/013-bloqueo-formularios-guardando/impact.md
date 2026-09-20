# Impact: Bloqueo de formularios durante el guardado

## Módulos/entidades existentes que toca
- Scripts frontend compartidos.
- Formularios Blade administrativos de productos, categorías, configuración y login.
- Modales Bootstrap de productos y categorías.
- Alertas SweetAlert2 existentes para errores.

## Endpoints nuevos o modificados
Ninguno. Se mantienen los endpoints, métodos, rutas, validaciones y respuestas actuales.

## Cambios de datos
Ninguno.

## Riesgos / dependencias con otras specs
- Debe convivir con modales Bootstrap y reapertura tras errores.
- Debe conservar SweetAlert2 de `006`.
- Debe conservar los formularios multipart del logo y fotos.
- El fallback sin JavaScript debe mantener el envío HTML normal tanto como sea posible.

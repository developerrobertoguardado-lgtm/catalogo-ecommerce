# Impact: Rediseño visual del administrador y tema claro/oscuro

## Módulos/entidades existentes que toca
- Layout administrativo y layout de autenticación.
- Vistas admin de productos, categorías, pedidos y configuración.
- Componentes compartidos de alertas y navegación.
- `resources/css/app.css` y scripts frontend compartidos.
- No modifica entidades ni lógica de dominio.

## Endpoints nuevos o modificados
Ninguno. Se conservan las rutas y respuestas existentes.

## Cambios de datos
Ninguno en la base de datos. La preferencia visual se almacena localmente en el navegador mediante `localStorage`.

## Riesgos / dependencias con otras specs
- Debe conservar la estructura de modales Bootstrap de productos y categorías.
- Debe mantener las alertas globales SweetAlert2 de la feature `006`.
- Debe conservar filtros y paginación de la feature `007`.
- La referencia externa se usa como inspiración visual; no se incorporan sus funcionalidades, assets propietarios ni rutas.
- El cambio de tema debe evitar estilos hardcodeados que dificulten el mantenimiento.

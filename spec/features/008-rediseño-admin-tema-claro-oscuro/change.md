# Change: Rediseño visual del administrador y tema claro/oscuro

## Problema / Usuario
La aplicación y el panel administrativo necesitan una experiencia visual más consistente, moderna y usable. Los administradores deben poder operar cómodamente en escritorio y móvil, y elegir entre tema claro u oscuro.

## Descripción funcional
Analizar la referencia Sash Bootstrap 5 y aplicar su lenguaje visual al administrador sin copiar funcionalidades ni modificar textos, rutas o comportamiento de negocio. Se rediseñarán visualmente login, menú lateral, header, tarjetas, tablas, botones, formularios, modales, badges, alertas y paginación en productos, categorías, pedidos y configuración.

Se agregará un botón visible para alternar entre modo claro y oscuro. La preferencia se guardará en `localStorage` y se aplicará al volver a visitar cualquier página. El layout debe adaptarse correctamente a móvil mediante menú lateral colapsable, tablas responsivas y componentes sin desbordamiento horizontal no intencional.

## Criterios de aceptación
- Todas las páginas administrativas tienen un lenguaje visual unificado inspirado en Sash Bootstrap 5.
- Se rediseñan login, productos, categorías, pedidos, detalle de pedido y configuración.
- Se mantienen intactos textos, rutas, endpoints y operaciones actuales.
- El menú lateral funciona en escritorio y móvil sin salir de la pantalla.
- Header, tarjetas, tablas, botones, formularios, modales, badges, alertas y paginación reciben estilos coherentes.
- Existe un botón accesible para alternar tema claro/oscuro.
- La preferencia se persiste en `localStorage` y se restaura al recargar o navegar.
- El tema no produce parpadeo visual evitable al cargar.
- Las tablas y modales son utilizables en pantallas pequeñas sin desbordamiento visual.
- El catálogo público no pierde su funcionalidad actual por los cambios compartidos de estilos/scripts.
- La solución continúa usando Bootstrap 5 y no reintroduce Tailwind.

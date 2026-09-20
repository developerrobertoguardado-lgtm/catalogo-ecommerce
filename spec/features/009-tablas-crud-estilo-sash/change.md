# Change: Rediseño visual de tablas CRUD con estilo Sash

## Problema / Usuario
Los administradores necesitan una experiencia visual más clara y consistente al consultar y gestionar productos, categorías y pedidos.

## Descripción funcional
Aplicar a todas las tablas CRUD administrativas el lenguaje visual de la página `data-tables.html` de Sash Bootstrap 5: tablas dentro de cards, encabezados jerarquizados, filas con espaciado y estados visuales, badges, acciones, paginación y comportamiento responsive.

El cambio será exclusivamente visual. Se conservarán la búsqueda, filtros, paginación, modales, botones, confirmaciones, formularios, rutas, textos, datos y operaciones actuales. También se conservarán los temas claro/oscuro implementados.

## Criterios de aceptación
- Las tablas de productos, categorías y pedidos comparten un estilo visual inspirado en Sash.
- Se mantienen todos los filtros y búsquedas actuales sin cambiar sus parámetros ni resultados.
- Se mantiene la paginación actual y sus enlaces.
- Se mantienen botones, acciones, modales, confirmaciones y formularios.
- Los encabezados, filas, badges, estados vacíos y acciones tienen jerarquía visual consistente.
- Las tablas funcionan correctamente en modo claro y oscuro.
- En móvil las tablas usan contenedor responsive sin desbordar la página.
- No se modifican textos, rutas, endpoints, consultas ni datos.
- El cambio continúa usando Bootstrap 5 sin reintroducir Tailwind.

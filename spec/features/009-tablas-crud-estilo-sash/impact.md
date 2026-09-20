# Impact: Rediseño visual de tablas CRUD con estilo Sash

## Módulos/entidades existentes que toca
- Vistas Blade administrativas de productos, categorías, pedidos y detalle de pedido.
- Estilos compartidos de tablas, cards, badges, acciones y paginación.
- Temas claro/oscuro y layout administrativo existentes.
- No modifica entidades ni lógica de negocio.

## Endpoints nuevos o modificados
Ninguno. Las rutas y respuestas actuales se mantienen sin cambios.

## Cambios de datos
Ninguno. No se modifican consultas, tablas, entidades ni campos.

## Riesgos / dependencias con otras specs
- Debe conservar la búsqueda y paginación de `007-busqueda-paginacion-crud`.
- Debe conservar el tema claro/oscuro de `008-rediseño-admin-tema-claro-oscuro`.
- Debe conservar las alertas y confirmaciones de `006-alertas-sweetalert2-globales`.
- El responsive debe evitar overflow horizontal accidental sin impedir el scroll interno necesario de tablas anchas.
- La referencia Sash se usa como inspiración visual; no se copian funcionalidades, rutas ni assets propietarios.

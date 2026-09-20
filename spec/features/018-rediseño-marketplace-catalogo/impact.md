# Impact: Rediseño marketplace responsive del catálogo

## Módulos/entidades existentes que toca
- `resources/views/components/layouts/app.blade.php`: header/footer público.
- `resources/views/catalogo/index.blade.php`: estructura, filtros, categorías,
  grid, ordenamiento/paginación.
- `resources/views/catalogo/show.blade.php`: detalle y modal de compra.
- `resources/views/components/producto-card.blade.php`: card, badges, CTA y
  carrito.
- `resources/views/components/icon.blade.php`: iconografía reutilizable.
- `resources/css/app.css`: tokens dinámicos, light/dark, responsive, drawer,
  cards y detalle.
- Tests de catálogo y nuevos tests de regresión visual/responsive.

## Endpoints nuevos o modificados
No se agregan ni modifican endpoints. Se conservan catálogo, categorías,
filtros, paginación y `POST /pedidos`/flujo WhatsApp actuales.

## Cambios de datos
No hay cambios de base de datos, entidades ni campos. Se consumen los colores
de `StoreSetting` ya existentes mediante las variables CSS dinámicas actuales.

## Riesgos / dependencias con otras specs
- Depende de `017-catalogo-responsive-carrito`, que ya introdujo el drawer
  responsive y el icono de carrito.
- Depende de `015-paleta-colores-empresa` para la paleta administrable y los
  tokens CSS dinámicos.
- Debe respetar RF-01 a RF-10 y la restricción de no crear carrito
  multi-producto ni autenticación obligatoria.
- Riesgo funcional bajo; riesgo visual medio por la reestructuración de
  superficies, breakpoints y estados light/dark.

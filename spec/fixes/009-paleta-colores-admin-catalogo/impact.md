# Impact: Paleta de colores - aplicación completa en admin y catálogo

## Causa raíz
1. **Sidebar/menú admin**: En `resources/css/app.css`, el bloque `html[data-theme='dark']` define `--admin-sidebar: var(--brand-menu)` pero también define `--brand-menu: #0b1220` (valor por defecto oscuro). La regla del tema oscuro en el elemento `html` tiene mayor especificidad que el estilo inline en `body`, por lo que el valor por defecto gana y el color configurado en la paleta no se aplica.

2. **Botón "Comprar por WhatsApp" en detalle de producto**: Usa la clase `btn btn-success` (línea 229 `app.css` y vista de producto) en lugar de `btn-primary`. Mi CSS anterior solo sobrescribe `--bs-btn-*` para `.btn-primary`, dejando `btn-success` intacto con el verde por defecto de Bootstrap.

3. **Vista previa "Acento"**: El badge "Acento" en el tab Aspecto visual muestra `--brand-primary` pero no hay ningún elemento en la UI que use explícitamente "acento" como concepto diferenciado de "primario" y "botones".

## Módulos/archivos afectados
- `resources/css/app.css` — reglas de tema oscuro, botones, sidebar, nav-link.active
- `resources/views/components/layouts/admin.blade.php` — estructura del sidebar (sin cambios funcionales)
- `resources/views/admin/configuracion/edit.blade.php` — vista previa del tab Aspecto visual
- `resources/views/catalogo/show.blade.php` — botón "Comprar por WhatsApp" (cambiar a btn-primary)

## Riesgo de la corrección
Bajo. Cambios solo de presentación CSS y una clase en la vista de detalle. No se tocan rutas, controladores, modelos ni lógica de negocio.
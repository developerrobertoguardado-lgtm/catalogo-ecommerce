# Impact: Paleta de colores de la empresa

## Módulos/entidades existentes que toca
- `StoreSetting` (nuevos campos de color) y su migración `store_settings`.
- `StoreSettingController` (validación y persistencia de los colores).
- Vista `resources/views/admin/configuracion/edit.blade.php` (nuevo tab
  `Aspecto visual`).
- Vistas del catálogo público (`resources/views/catalogo/...`) para aplicar
  colores dinámicos.
- Layout administrativo (sidebar/menú, header, tarjetas, botones) para
  aplicar colores dinámicos.
- `resources/css/app.css` (variables CSS consumidas desde la paleta).
- Script de vista previa en vivo (Alpine.js o JS simple).

## Endpoints nuevos o modificados
- Modificado: `PUT /admin/configuracion` (admin) — ahora acepta y guarda los
  campos de color (primario, secundario, botones, menú, fondo, texto) junto
  con los datos actuales de la tienda. No se agregan rutas nuevas. Se
  conserva `GET /admin/configuracion` para cargar los valores actuales.

## Cambios de datos
- Se extiende la tabla `store_settings` con columnas nullable para los
  colores:
  - `primary_color`
  - `secondary_color`
  - `button_color`
  - `menu_color`
  - `background_color`
  - `text_color`
- Formato: cadena hexadecimal `#RRGGBB` (nullable; `null` = color por defecto).
- No se crean entidades ni tablas nuevas.

## Riesgos / dependencias con otras specs
- Depende del rediseño visual existente basado en variables CSS de tema claro/
  oscuro (`008-rediseno-admin-tema-claro-oscuro`). La paleta debe consumir
  esas mismas variables para no romper el contraste ni el modo oscuro.
- El catálogo público ya tiene estilos propios (`002`, `005`, `010`, `011`,
  `012`, `014`); los colores dinámicos deben aplicarse por variables CSS para
  conservar esos estilos.
- Riesgo bajo de regresión funcional: no se tocan rutas ni datos de negocio.

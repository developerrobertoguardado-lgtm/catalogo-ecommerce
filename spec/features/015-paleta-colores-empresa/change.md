# Change: Paleta de colores de la empresa

## Problema / Usuario
El administrador de la tienda necesita que los colores de toda la aplicación
reflejen los de su empresa. Hoy los colores (botones, menús, acentos, fondos)
están disparejos y mezclados entre el catálogo público y el panel
administrativo, y no hay forma de personalizarlos desde el admin.

## Descripción funcional
Se agrega un nuevo tab llamado **Aspecto visual** dentro de la página de
Configuración del admin. Desde ahí, el administrador define una paleta de
colores de empresa (color primario, secundario, botones, menú, fondo y texto)
que se aplica automáticamente a toda la aplicación: tanto al catálogo público
como al panel administrativo. Los valores se guardan como campos adicionales
en `store_settings` y se mantiene el modo claro/oscuro existente.

## Criterios de aceptación
- En Configuración aparece un tab nuevo llamado `Aspecto visual` con los
  colores editables:
  - Primario
  - Secundario
  - Botones
  - Menú
  - Fondo
  - Texto
- Cada color se elige con un selector de color (picker) y se puede teclear
  manualmente en formato hex (`#RRGGBB`).
- Los cambios se previsualizan antes de guardar (vista previa en vivo).
- Al guardar, los colores se persisten en `store_settings` conservando el
  resto de la configuración de la tienda (nombre, WhatsApp, moneda, logo).
- Los colores se aplican en el **catálogo público** y en el **panel
  administrativo** (botones, menú/sidebar, acentos y fondos).
- Se mantiene el modo claro/oscuro: la paleta aplica sobre cada tema sin
  romper el contraste.
- Si el administrador todavía no configura la paleta, se usan los colores por
  defecto actuales.
- Si un color tiene formato inválido, se muestra un error de validación y no
  se guarda.
- No se modifican rutas ni operaciones existentes: se reutiliza
  `PUT /admin/configuracion`.
- No se agregan entidades nuevas: se extiende la tabla `store_settings`.

## Casos borde
- Campos de color vacíos: se conserva el valor por defecto.
- Mismo color para varios campos: válido.
- Color inválido (fuera de hex): se rechaza con mensaje.
- Volver al por defecto: permitido limpiando el campo de un color.

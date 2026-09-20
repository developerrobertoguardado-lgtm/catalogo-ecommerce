# Impact: Drag & drop para el logo de configuración

## Causa raíz
El formulario de configuración renderizaba un input `file` estándar y un checkbox separado. No existía un componente frontend para controlar arrastre, previsualización, reemplazo ni eliminación visual del logo.

## Módulos/archivos afectados
- `resources/js/logo-dropzone.js` y `resources/js/app.js`.
- `resources/views/admin/configuracion/edit.blade.php`.
- `resources/css/app.css`.
- `tests/Feature/Admin/ConfiguracionTest.php`.

## Riesgo de la corrección
Bajo. Se agrega interacción frontend sobre el flujo de configuración existente; el backend continúa eliminando el archivo anterior y el registro al guardar.

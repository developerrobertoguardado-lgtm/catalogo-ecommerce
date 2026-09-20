# Change: Bloqueo de formularios durante el guardado

## Problema / Usuario
Los administradores pueden hacer doble clic o enviar varias veces un formulario mientras una operación de guardado o actualización está procesándose, provocando solicitudes repetidas o datos duplicados.

## Descripción funcional
Bloquear inmediatamente los formularios administrativos al enviarlos. El botón principal debe cambiar su texto a `Guardando...`, quedar deshabilitado y evitar envíos repetidos. Todos los campos del formulario y los botones de cerrar/cancelar también deben deshabilitarse durante el procesamiento. Si el servidor devuelve un error de validación o de operación, el formulario debe volver a habilitarse para permitir corregirlo.

La funcionalidad aplica a productos, categorías, configuración, login y cualquier formulario administrativo actual o futuro compatible con el comportamiento compartido.

## Criterios de aceptación
- El primer envío bloquea inmediatamente el formulario.
- El botón Guardar/Actualizar cambia a `Guardando...`.
- El botón principal queda deshabilitado durante la operación.
- Todos los campos quedan deshabilitados durante la operación.
- Los botones cerrar y cancelar quedan deshabilitados durante la operación.
- Cualquier segundo clic o pulsación repetida de Enter no genera otro envío.
- Ante error de validación o servidor, el formulario vuelve a habilitarse.
- El comportamiento aplica a productos, categorías, configuración, login y formularios administrativos futuros.
- No se modifican endpoints, métodos, rutas ni datos.

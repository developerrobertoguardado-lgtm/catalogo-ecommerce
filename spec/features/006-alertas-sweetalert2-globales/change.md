# Change: Sistema global de alertas SweetAlert2

## Problema / Usuario
Mejorar la UI/UX del administrador y ofrecer una experiencia consistente en toda la aplicación. Administradores y usuarios finales deben recibir mensajes claros para operaciones exitosas, errores, información y confirmaciones.

## Descripción funcional
Crear un componente reutilizable basado en SweetAlert2 para centralizar todas las alertas de la aplicación. Debe poder utilizarse en consultas, eliminaciones y demás operaciones actuales y futuras, incluyendo mensajes de éxito, error, información, advertencia y confirmaciones.

Las confirmaciones de eliminación deben permitir continuar o cancelar. Las alertas de éxito e información deben cerrarse automáticamente cuando corresponda. Los errores de validación y de servidor deben mostrarse de forma comprensible, conservando el comportamiento funcional actual.

## Criterios de aceptación
- Todas las alertas nuevas y existentes usan el componente reutilizable de SweetAlert2.
- Las operaciones exitosas muestran una alerta de éxito consistente.
- Las operaciones de eliminación solicitan confirmación antes de enviar la petición.
- Cancelar una confirmación no ejecuta la operación.
- Los errores de validación muestran los campos o mensajes disponibles sin perder datos del formulario.
- Los errores de servidor o de red muestran una alerta de error comprensible.
- Las alertas informativas y de advertencia están disponibles mediante la misma API reutilizable.
- El componente funciona en las vistas públicas y administrativas.
- La interfaz mantiene compatibilidad responsive y con Bootstrap 5.
- Si JavaScript no está disponible, los formularios conservan su comportamiento HTML/Laravel existente tanto como sea posible.

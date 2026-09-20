# Impact: Conservar valores al bloquear formularios

## Causa raíz
Los controles `disabled` no forman parte de la petición HTML. El bloqueo deshabilitaba los campos visibles antes del submit sin conservar sus valores, por lo que el servidor recibía formularios incompletos.

## Módulos/archivos afectados
- `resources/js/bloqueo-formularios.js`.
- Formularios administrativos, incluidos multipart.
- Test de regresión de bloqueo.

## Riesgo de la corrección
Bajo. Se agregan copias hidden de controles con nombre y se mantiene el archivo original sin disabled, bloqueado visualmente. No se modifican endpoints ni reglas de validación.

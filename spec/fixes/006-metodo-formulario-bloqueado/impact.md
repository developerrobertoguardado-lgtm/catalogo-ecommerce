# Impact: Preservar el método de formularios bloqueados

## Causa raíz
El script de bloqueo seleccionaba todos los `input` y los deshabilitaba. Los campos ocultos `_token` y `_method` no se envían cuando están disabled, por lo que Laravel perdía CSRF y method spoofing.

## Módulos/archivos afectados
- `resources/js/bloqueo-formularios.js`.
- Formularios administrativos con POST/PUT y CSRF.
- Test de regresión de formularios.

## Riesgo de la corrección
Bajo. Solo se excluyen inputs hidden del bloqueo visual; los campos visibles y botones continúan deshabilitándose.

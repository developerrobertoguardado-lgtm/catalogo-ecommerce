# Change: Preservar el método de formularios bloqueados

## Reproducción
1. Abrir `/admin/configuracion`.
2. Enviar el formulario de configuración.
3. Observar la respuesta del servidor mientras el formulario está bloqueado.

## Comportamiento esperado
El formulario debe bloquear sus controles visibles, cambiar el botón a `Guardando...` y conservar los campos ocultos necesarios para CSRF y el método HTTP.

## Comportamiento actual
Al bloquear todos los inputs se deshabilitaban también `_token` y `_method`, provocando que formularios PUT llegaran como POST y respondieran 405.

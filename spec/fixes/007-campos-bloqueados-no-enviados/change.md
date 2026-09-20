# Change: Conservar valores al bloquear formularios

## Reproducción
1. Abrir un formulario administrativo, por ejemplo Configuración.
2. Completar sus campos.
3. Pulsar Guardar con el bloqueo de formulario activo.

## Comportamiento esperado
El formulario debe bloquearse y mostrar `Guardando...`, pero enviar todos sus valores y completar la operación.

## Comportamiento actual
Los campos deshabilitados no se enviaban con el formulario, provocando errores de validación y perdiendo los datos ingresados.

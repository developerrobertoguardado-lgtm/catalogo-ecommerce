# Validation: Preservar el método de formularios bloqueados

## Test de regresión
Verificar que el selector de bloqueo excluya `input[type="hidden"]`, evitando que `_token` y `_method` se pierdan. El test pasa con la corrección y detecta la selección amplia anterior.

## Estado
- [x] Corregido
- [x] Testeado
- [x] Verificado en el comportamiento reportado

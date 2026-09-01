# Validation: Dropzone de fotos con reordenamiento por arrastre

## Criterios de aceptación a validar
Ver `change.md`.

## Plan de testing
Tests nuevos en `tests/Feature/Admin/ProductoCrudTest.php`:
- `reordena las fotos existentes según el orden enviado por el dropzone` —
  invierte el orden de 3 fotos existentes vía `orden_fotos` y verifica que
  `position`/`is_primary` queden correctos en BD.
- `intercala una foto nueva con las existentes según el orden del dropzone`
  — sube 1 foto nueva ubicada antes de una existente y verifica que la
  nueva quede en posición 0 (`is_primary = true`) y la existente se corra a
  la posición 1.

Verificado además: los 19 tests preexistentes (incluyendo el límite de 4
fotos) siguen en verde sin cambios, y el HTML del modal en
`/admin/productos` contiene el dropzone (`fotosDropzone`, "Arrastra tus
fotos").

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

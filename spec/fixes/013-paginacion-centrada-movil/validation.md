# Validation: Paginación del catálogo centrada en la versión móvil

## Test de regresión
Test del catálogo que verifica en `resources/css/app.css` que existe una regla
que centra la `ul.pagination` dentro de `.catalog-pagination` en móvil
(por ejemplo `.catalog-pagination .d-sm-none { justify-content: center }`).
Falla sin el fix porque no hay ninguna regla de centrado para el bloque móvil.

## Estado
- [x] Corregido
- [x] Testeado
- [x] Verificado en el comportamiento reportado
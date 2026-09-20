# Validation: Paridad visual del catálogo móvil con la referencia de diseño

## Test de regresión
Test del catálogo que verifique en el markup: presencia de `category-chip` con
avatar en móvil, `catalog-pagination` en el envoltorio de paginación, y que el
CSS defina `object-fit: contain` para el media de la card. Falla sin el fix
(chips de categorías y envoltorio no existen en el markup previo).

## Estado
- [x] Corregido
- [x] Testeado
- [x] Verificado en el comportamiento reportado

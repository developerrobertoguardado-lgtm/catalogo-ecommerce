# Validation: Proyecto base (scaffold inicial completo)

## Criterios de aceptación a validar
Ver `change.md` — todos cumplidos salvo CD (fuera de alcance).

## Plan de testing
Ejecutado — ver `/spec/05-testing-strategy.md`. Cobertura mínima requerida
cumplida: CRUD admin, filtros, generación de mensaje/link de WhatsApp,
creación de `Order`/`OrderItem`, regla de 4 fotos máximo. Suite de tests
Pest (19 tests, 53 assertions) corrida contra PostgreSQL real (no SQLite),
verificado en la sesión de implementación.

## Estado
- [x] Spec aprobada (implícita — deriva de `/spec/00-vision.md` a `05-testing-strategy.md`)
- [x] Implementada
- [x] Testeada
- [ ] Desplegada (sin CD todavía)

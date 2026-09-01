# Estrategia de testing

## Niveles cubiertos
- Unitario: Sí — Pest (`tests/Unit`), para lógica aislada (ej. armado del
  mensaje de WhatsApp, cálculo de totales).
- Integración/Feature: Sí — Pest sobre `Illuminate\Foundation\Testing` (HTTP
  feature tests contra rutas reales y base de datos PostgreSQL de test),
  en `tests/Feature`.
- E2E: No por ahora — se puede incorporar más adelante con Playwright si el
  proyecto lo justifica.

## Cobertura mínima requerida
- CRUD de productos y categorías desde el admin (crear, editar, eliminar,
  validaciones de Form Request).
- Filtros de búsqueda del catálogo público (por nombre, categoría, rango de
  precio, combinados).
- Generación correcta del mensaje y link de WhatsApp (`POST /pedidos`):
  formato del mensaje, número de destino, codificación de la URL.
- Creación del `Order`/`OrderItem` al hacer clic en "Comprar", incluyendo
  caso de `quantity` inválida o mayor al `stock`.
- Regla de máximo 4 `ProductImage` por producto y unicidad de `is_primary`.

## Convenciones
- Ubicación de tests: `tests/Feature/<Area>/...` (ej.
  `tests/Feature/Admin/ProductoCrudTest.php`,
  `tests/Feature/Catalogo/FiltrosTest.php`,
  `tests/Feature/Pedidos/GenerarPedidoWhatsAppTest.php`) y
  `tests/Unit/...` para lógica aislada (ej.
  `tests/Unit/PedidoWhatsAppServiceTest.php`).
- Nomenclatura: sintaxis funcional de Pest (`it('hace algo', function () {...})`
  o `test('descripción', ...)`), en español para que el nombre del test
  describa el comportamiento de negocio en los mismos términos que la spec
  (ej. `it('registra un pedido y arma el link de WhatsApp correctamente')`).
- Base de datos de test: PostgreSQL real (no SQLite en memoria) para
  mantener paridad con producción, usando `RefreshDatabase` de Pest/Laravel.
- Factories de Eloquent para cada entidad (`ProductFactory`,
  `CategoryFactory`, etc.), usadas tanto en tests como en seeders de
  desarrollo.

## Gaps detectados
No aplica — proyecto nuevo, la estrategia se implementa desde cero junto con
el código.

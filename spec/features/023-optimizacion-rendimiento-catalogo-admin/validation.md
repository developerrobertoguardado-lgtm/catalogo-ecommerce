# Validation: Optimizacion de rendimiento y carga de vistas del catalogo y admin

## Criterios de aceptacion a validar
- JS del catalogo publico <=170KB (antes 466KB)
- StoreSetting::current() se ejecuta maximo 1 vez por request
- Footer no ejecuta queries SQL independientes
- Indices creados en products.price, products.created_at, orders.status, orders.created_at
- recompactarPosiciones() usa bulk update (1 query en vez de N)
- OrderController::update() usa whereIn() en vez de find() en loop
- Google Fonts cargan con preconnect, no bloquean render
- Todos los 67 tests siguen pasando
- MCP: catalogo publico carga en <=2s en throttled 3G

## Plan de testing
- Test Pest: verificar que el JS built tiene chunks separados (no un solo archivo)
- Test Pest: verificar que StoreSetting usa cache (mock Cache facade)
- Test Pest: verificar que el footer del layout recibe $footerCategorias
- Test Pest: verificar que los indices existen en la BD
- Test Pest: verificar que recompactarPosiciones() usa bulk update
- MCP: abrir catalogo publico, verificar Network tab con chunks, tiempo de carga, queries en barra de debug

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada
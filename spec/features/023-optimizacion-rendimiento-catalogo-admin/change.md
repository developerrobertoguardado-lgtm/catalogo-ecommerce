# Change: Optimización de rendimiento y carga de vistas del catálogo y admin

## Problema / Usuario
Las vistas del catálogo público son lentas y no parecen reactivas. El catálogo
público carga 466KB de JS (Quill, SweetAlert2, Bootstrap, Alpine) y 273KB de
CSS en todas las páginas, aunque la mayoría de estos scripts solo se usan en
el admin. StoreSetting::current() se ejecuta 12+ veces por página (una por
card de producto) porque se llama dentro del componente Blade sin caché. El
footer ejecuta una query SQL independiente en cada carga de página. Faltan
índices en price, created_at y status.

## Descripción funcional
1. **Code splitting con Vite manualChunks**: separar vendor (Bootstrap),
   quill, sweetalert2, admin (dropzones/paleta). El catálogo público solo
   carga Bootstrap + Alpine + custom (~150KB vs 466KB). Quill CSS se carga
   solo en admin. SweetAlert2 se importa dinámicamente donde se necesita.
2. **Caché de StoreSetting**: Cache::remember() en current(). Producto-card
   recibe $currency como prop en vez de llamar StoreSetting en cada iteración.
3. **Footer optimizado**: pasar $footerCategorias desde controller en vez de
   Category::get() hardcodeado en el Blade.
4. **Índices de BD**: products(price), products(created_at),
   product_images(product_id, is_primary), orders(status), orders(created_at).
5. **Fixes N+1**: ProductController::recompactarPosiciones() por bulk update,
   OrderController::update() pre-carga productos con whereIn() antes del loop.
6. **Google Fonts**: reemplazar @import url() por link preconnect + font en
   head del layout (no render-blocking).

## Criterios de aceptación
- [ ] JS del catálogo público ≤170KB (antes 466KB)
- [ ] StoreSetting::current() se ejecuta máximo 1 vez por request (usando caché)
- [ ] Footer no ejecuta queries SQL independientes
- [ ] Índices creados en products.price, products.created_at, orders.status, orders.created_at
- [ ] recompactarPosiciones() usa bulk update (1 query en vez de N)
- [ ] OrderController::update() usa whereIn() en vez de find() en loop
- [ ] Google Fonts cargan con preconnect, no bloquean render
- [ ] Todos los 67 tests siguen pasando
- [ ] MCP: catálogo público carga en ≤2s en throttled 3G
# Tasks: Optimizacion de rendimiento y carga de vistas del catalogo y admin

- [x] Code splitting: configurar manualChunks en vite.config.js (vendor, quill, sweetalert2, admin)
- [x] Code splitting: imports dinamicos en app.js para Quill, SweetAlert2, dropzones
- [x] Cachear StoreSetting::current() con Cache::remember()
- [x] Pasar $currency como prop a producto-card desde CatalogoController
- [x] Pasar $footerCategorias desde CatalogoController y controllers admin al layout
- [x] Crear migracion de indices: products.price, products.created_at, product_images(product_id, is_primary), orders.status, orders.created_at
- [x] Ejecutar migrate
- [x] Fix N+1: ProductController::recompactarPosiciones() por bulk update
- [x] Fix N+1: OrderController::update() pre-carga productos con whereIn()
- [x] Google Fonts: reemplazar @import url() por preconnect + link en ambos layouts
- [x] Mover Quill CSS a import condicional o solo admin
- [x] Build, suite completa y validacion MCP
- [x] Actualizar progress-log.md
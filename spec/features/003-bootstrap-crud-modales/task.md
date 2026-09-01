# Tasks: Migración a Bootstrap + CRUD del admin en modales

- [x] Remover Tailwind (`package.json`, `vite.config.js`, `app.css`)
- [x] Instalar y configurar Bootstrap 5 (CSS + JS bundle)
- [x] Configurar `Paginator::useBootstrapFive()`
- [x] Reescribir layouts (público y admin) con componentes Bootstrap
- [x] Reescribir catálogo público (accordion, carousel, cards)
- [x] Convertir CRUD de productos a modales (nuevo + uno por fila para editar)
- [x] Convertir CRUD de categorías a modales
- [x] Implementar reapertura automática del modal correcto ante error de validación
- [x] Eliminar rutas/controladores/vistas `create`/`edit` ya no usadas
- [x] Verificar que los 19 tests existentes sigan en verde
- [x] Verificar manualmente por HTTP el flujo completo de modal (éxito y error)

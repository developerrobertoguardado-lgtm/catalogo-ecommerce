# Tasks: Descripción larga con texto enriquecido en admin de productos

- [ ] Instalar Quill.js via npm (`npm install quill`)
- [ ] Crear migración para `long_description` en `products`
- [ ] Actualizar modelo `Product` (agregar `long_description` al `$fillable`)
- [ ] Actualizar Form Requests (`StoreProductRequest`, `UpdateProductRequest`)
- [ ] Agregar editor Quill al modal de crear/editar producto
- [ ] Inicializar Quill en `resources/js/app.js`
- [ ] Mostrar `long_description` en el detalle público (`show.blade.php`)
- [ ] Test Pest para persistencia de `long_description`
- [ ] Build, suite y validación MCP
- [ ] Actualizar bitácora en `/spec/progress-log.md`
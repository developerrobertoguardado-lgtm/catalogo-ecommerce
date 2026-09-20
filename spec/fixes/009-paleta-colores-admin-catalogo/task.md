# Tasks: Paleta de colores - aplicación completa en admin y catálogo

- [ ] Corregir tema oscuro: mover defaults de brand a `:root` y en `html[data-theme='dark']` solo sobreescribir los valores que cambian (sin redeclarar --brand-* con defaults), para que el estilo inline en body gane
- [ ] Asegurar `.admin-sidebar` y `.admin-nav .nav-link.active` usen `--brand-menu` y `--brand-primary` respectivamente
- [ ] Cambiar botón "Comprar por WhatsApp" en detalle de producto de `btn-success` a `btn-primary`
- [ ] Extender override de botones a `.btn-success` para que también use `--brand-button`
- [ ] Simplificar vista previa en Aspecto visual: quitar badge "Acento", mostrar solo Botón (brand-button) y Menú (brand-menu)
- [ ] Agregar test de regresión: verificar que sidebar admin y botón compra usan colores configurados
- [ ] Compilar frontend (`npm run build`) y correr suite completa
- [ ] Validar con MCP en admin y catálogo (escritorio y móvil)
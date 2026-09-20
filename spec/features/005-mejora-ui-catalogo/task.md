# Tasks: Mejora UI/UX del catálogo público

- [x] Actualizar `resources/views/components/layouts/app.blade.php`: quitar el
      div `bg-dark` superior y el botón "WhatsApp" del header; dejar el header
      con logo/nombre de la tienda + barra de búsqueda de producto.
- [x] Integrar la barra de búsqueda de producto en el header (reutilizando el
      filtro `nombre` del catálogo vía GET a `catalogo.index`).
- [x] Ajustar la estética de `resources/views/components/producto-card.blade.php`
      inspirada en la web de referencia (foto cuadrada arriba, nombre, categoría,
      precio y botón de acción con hover sutil).
- [x] Ajustar la estética de `resources/views/catalogo/index.blade.php` manteniendo
      intactos los filtros (nombre, categoría, precio) y su posición.
- [x] Ajustar la estética de `resources/views/catalogo/show.blade.php` para
      consistencia, SIN romper el stepper de cantidad ni el botón "Comprar por
      WhatsApp" (preservar Alpine.js).
- [x] Agregar los estilos nuevos en `resources/css/app.css` (solo Bootstrap 5,
      sin reintroducir Tailwind).
- [x] Compilar assets (`npm run build`) y verificar visualmente el catálogo en
      `http://localhost` y el detalle de un producto.
- [x] Correr la suite de tests existente para confirmar que nada se rompe.

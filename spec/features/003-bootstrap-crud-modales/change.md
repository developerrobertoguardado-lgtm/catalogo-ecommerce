# Change: Migración a Bootstrap + CRUD del admin en modales

## Problema / Usuario
El administrador pedía dos cosas: (1) que crear/editar productos y
categorías se haga en modales en vez de páginas completas, y (2) usar
Bootstrap para los inputs del formulario (tamaño por defecto).

Se le advirtió explícitamente que mezclar Bootstrap con Tailwind (el
framework ya usado en el proyecto) causa conflictos reales de CSS
(`.container`, `.row`, `.card` significan cosas distintas en cada uno). El
usuario confirmó: **reemplazar Tailwind por Bootstrap en todo el proyecto**
(catálogo público + admin), con el tamaño de input por defecto de Bootstrap.

## Descripción funcional
Reemplazar Tailwind por Bootstrap 5 en todo el proyecto, y convertir el
CRUD de productos/categorías del admin de páginas completas a modales.

## Criterios de aceptación
- [x] Tailwind CSS removido del proyecto (`package.json`, `vite.config.js`,
      `resources/css/app.css`). Bootstrap 5 instalado como reemplazo, con su
      CSS y JS bundle (incluye Popper) cargados vía Vite.
- [x] Todas las vistas (catálogo público y admin) reescritas con clases y
      componentes nativos de Bootstrap: navbar, cards, tablas, badges,
      accordion (filtros del catálogo), carousel (galería de fotos del
      producto), offcanvas (sidebar admin en móvil), dropdown (menú de
      usuario admin).
- [x] Paginación de Laravel configurada para renderizar con estilo Bootstrap
      (`Paginator::useBootstrapFive()` en `AppServiceProvider`).
- [x] CRUD de productos y de categorías en el admin: "Nuevo" y "Editar" ya
      no son páginas separadas — son modales de Bootstrap (`data-bs-toggle`)
      abiertos desde el listado. Un modal para "nuevo" + un modal por fila
      para "editar" (patrón simple, sin JS para repoblar campos).
- [x] Si la validación del formulario falla dentro de un modal, el usuario
      vuelve al listado con el modal correcto reabierto automáticamente
      (vía un campo oculto `_modal` con el id del modal + un pequeño script
      que llama a `new bootstrap.Modal(...).show()` si hay errores) y los
      mensajes de error se muestran en los campos correspondientes.
- [x] Alpine.js se mantiene, pero solo para la lógica sin equivalente nativo
      en Bootstrap: el stepper de cantidad y el fetch a `/pedidos` en la
      página de detalle de producto. Se removieron `@alpinejs/persist` y
      `@alpinejs/collapse` (ya no hacen falta, Bootstrap cubre
      collapse/offcanvas nativamente).
- [x] Las rutas `GET admin/productos/create`, `GET admin/productos/{id}/edit`,
      `GET admin/categorias/create`, `GET admin/categorias/{id}/edit` se
      eliminaron (`Route::resource(...)->except([..., 'create', 'edit'])`)
      junto con sus métodos de controlador y vistas — ya no existen como
      páginas independientes.
- [x] Los 19 tests Pest existentes siguen pasando sin modificarlos (no
      dependían de las rutas/vistas eliminadas).

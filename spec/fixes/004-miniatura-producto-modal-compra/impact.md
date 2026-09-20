# Impact: Miniatura del producto en modal de compra

## Causa raíz
La vista de detalle cargaba las imágenes del producto, pero no cargaba explícitamente `primaryImage` ni renderizaba una imagen en el resumen del modal.

## Módulos/archivos afectados
- `CatalogoController::show()` para cargar la imagen principal.
- `resources/views/catalogo/show.blade.php` para mostrar la miniatura.
- `tests/Feature/Catalogo/FiltrosTest.php` para la regresión.

## Riesgo de la corrección
Bajo. Solo agrega una imagen al resumen; no modifica validaciones, pedidos, rutas ni redirecciones.

# Impact: Paridad visual del catálogo móvil con la referencia de diseño

## Causa raíz
En la feature 018 se rediseñó el catálogo, pero el markup móvil quedó sin tres
elementos estructurales de la referencia: (1) nunca se agregó la fila de chips
de categorías (solo chips de filtros activos), (2) la card usó cover full-bleed
(avail. la referencia pide imagen contenida sobre superficie limpia), y encuentran (3)
la paginación y los radios del drawer no fueron restilados a lenguaje app.
Todo es presentación: no hay defecto en queries, rutas ni lógica de negocio.

## Módulos/archivos afectados
- `resources/views/catalogo/index.blade.php` — sección Categorías móvil + envoltorio de paginación.
- `resources/views/components/producto-card.blade.php` — media contenida.
- `resources/css/app.css` — chips de categoría, estilo de paginación y radio del drawer.
- `tests/Feature/Catalogo/FiltrosTest.php` — regresión de markup.

## Riesgo de la corrección
Bajo. Solo presentation markup/CSS. Los tests existentes de catálogo no dependen
del estilo de paginación ni del estilo cover de la imagen.

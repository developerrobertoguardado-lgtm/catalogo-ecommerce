# Change: Paleta de colores - aplicación completa en admin y catálogo

## Reproducción
1. Configurar una paleta de colores en Configuración → Aspecto visual (ej. menú verde, botones naranja)
2. Ir al panel de administración: el menú lateral (sidebar) sigue con el color por defecto oscuro, no usa el color de menú configurado
3. Ir al catálogo público → detalle de producto: el botón "Comprar por WhatsApp" usa el color verde por defecto de Bootstrap (btn-success), no el color de botones configurado
4. En el tab Aspecto visual, la vista previa muestra un badge "Acento" con el color primario pero no queda claro qué elemento representa

## Comportamiento esperado
- El menú lateral del admin (sidebar) y el offcanvas móvil usan el "Color del menú" configurado
- Todos los botones principales (catálogo card, detalle producto, admin) usan el "Color de botones" configurado
- La vista previa en Aspecto visual es clara: cada elemento etiquetado corresponde a su uso real

## Comportamiento actual
- Sidebar/menú admin: color por defecto hardcodeado en modo oscuro
- Botón "Comprar por WhatsApp" en detalle: `btn-success` (verde Bootstrap) en vez de `btn-primary`
- Vista previa: badge "Acento" confuso sin correspondencia clara
# Change: Paridad visual del catálogo móvil con la referencia de diseño

## Reproducción
1. Abrir el catálogo en móvil (≤390px) y comparar con la referencia "Versión Móvil".
2. Falta la fila "Categorías" con chips scrollables (icono circular + nombre) bajo el hero.
3. Las cards muestran la imagen a sangre (cover) en lugar de área limpia con la imagen centrada.
4. La paginación usa el estilo Bootstrap por defecto, no el estilo redondeado tipo app de la referencia.
5. El drawer de filtros no muestra indicadores circulares de selección en las categorías.

## Comportamiento esperado
- Fila "Categorías" con "Ver todas" y chips.scrollables con avatar de categoría (imagen o fallback) y nombre, marcando la activa.
- Cards con media limpia: imagen contenida centrada sobre la superficie de la card.
- Paginación en pill redondeado con página activa en color de marca.
- Radios del drawer con círculo de selección estilo referencia.
- Conservar todo el comportamiento (filtros GET, detalle, compra WhatsApp, dark mode).

## Comportamiento actual
- Hero directo al grid sin chips de categorías.
- Cover full-bleed en media de card.
- Paginación Bootstrap sin pulir.
- Radios solo resaltan el fondo del label.

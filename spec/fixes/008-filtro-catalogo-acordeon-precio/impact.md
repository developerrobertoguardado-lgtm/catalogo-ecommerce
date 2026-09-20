# Impact: Corregir estilo y comportamiento del filtro de catálogo

## Causa raíz
El filtro usaba los estilos predeterminados de Bootstrap y ambos acordeones compartían `data-bs-parent`, que activa el comportamiento de exclusión mutua. Los campos de precio solo tenían inputs numéricos, sin una capa visual de barra y rango.

## Módulos/archivos afectados
- `resources/views/catalogo/index.blade.php`.
- `resources/css/app.css`.
- Test de regresión del catálogo.

## Riesgo de la corrección
Bajo. Se modifica la presentación y se elimina únicamente el cierre automático entre acordeones; los parámetros GET y consultas permanecen iguales.

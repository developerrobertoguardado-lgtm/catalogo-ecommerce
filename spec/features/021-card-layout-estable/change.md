# Change: Card de productos con layout estable, fallback de imagen y texto truncado

## Problema / Usuario
El cliente que navega el catálogo ve cards con alturas diferentes porque:
1. El botón "Agregar" no siempre queda abajo — se desplaza según la altura
   de la imagen, nombre y descripción de cada producto.
2. Si la foto falla (404, rota, ausente), el icono de fallback desplaza los
   elementos y rompe la card.
3. La descripción larga sobresale del card o genera cards de diferentes alturas.

## Descripción funcional
- **Botón abajo**: la card usa `d-flex flex-column` con `card-body` con
  `flex-grow-1` para que el botón "Agregar" quede siempre al final.
- **Fallback de imagen**: si la imagen falla (`error` event en `<img>`), se
  oculta la img y se muestra un placeholder con icono `image` y fondo
  `var(--admin-surface-soft)` que mantiene la misma proporción (aspect-ratio 1/1).
- **Texto truncado**: descripción máximo 3 líneas con `line-clamp: 3` y
  `overflow: hidden`. Si excede, se corta con "..." sin sobresalir del card.

## Criterios de aceptación
- [ ] El botón "Agregar" / "Agotado" queda siempre al final de la card
  (flex-grow-1 en card-body, mt-auto en el botón o su contenedor).
- [ ] Si la imagen falla, se muestra un placeholder con icono `image` que
  mantiene el aspect-ratio 1/1 y no desplaza elementos.
- [ ] La descripción se trunca a 3 líneas máximo con `line-clamp: 3`.
- [ ] Las cards de la grilla quedan alineadas (misma altura por fila).
- [ ] En desktop y móvil el comportamiento es el mismo.
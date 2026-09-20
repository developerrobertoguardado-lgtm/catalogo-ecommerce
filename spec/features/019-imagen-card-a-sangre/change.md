# Change: Imagen de card a sangre sin padding en costados ni superior

## Problema / Usuario
El fix 011 agregó `object-fit: contain` con `padding: 1rem 1.25rem` a la imagen de
las cards del catálogo, lo que deja la foto con espacio en los costados y arriba
("encajonada"), reduciendo el tamaño visual del producto. El cliente que navega el
catálogo quiere ver la foto del producto a sangre, ocupando todo el ancho superior
del card.

## Descripción funcional
- La imagen del card se muestra **a sangre del card**: sin padding ni margen en
  costados ni en la parte superior.
- La imagen respeta el redondeo de las esquinas superiores del card (`border-radius`).
- Se usa `object-fit: cover` para que el área de imagen (cuadrada) quede completamente
  cubierta, recortando el sobrante si la foto no tiene la misma proporción.
- El badge (Agotado / Últimas unidades) se mantiene sobre la imagen.
- El resto de la card (nombre, descripción, precio, CTA) queda igual.

## Criterios de aceptación
- [ ] La imagen de la card no tiene padding ni margen en costados ni superior.
- [ ] La imagen respeta el redondeo de las esquinas superiores del card.
- [ ] El área de la imagen mantiene su proporción cuadrada con `object-fit: cover`.
- [ ] El badge se mantiene visible sobre la imagen.
- [ ] El modo oscuro y el detalle de producto no se ven afectados.
- [ ] Sin cambios de rutas, datos ni controladores.
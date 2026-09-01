# Change: Dropzone de fotos con reordenamiento por arrastre

## Problema / Usuario
En el modal de crear/editar producto, subir fotos era un `<input type="file">`
plano sin previsualización ni forma de elegir cuál foto queda primero (la
"principal" se asignaba solo por el orden de subida original, sin poder
corregirlo después). El administrador pidió: (1) un dropzone para arrastrar
y soltar imágenes, (2) miniaturas mostradas en el orden de subida, y (3)
poder alterar ese orden arrastrando las miniaturas.

## Descripción funcional
Reemplazar el input de fotos por un dropzone (Alpine.js) que muestra
miniaturas en orden, permite arrastrar para reordenar (mezclando fotos ya
guardadas y nuevas), y deriva la foto principal de la posición 0.

## Criterios de aceptación
- [x] El campo de fotos en los modales de "Nuevo producto" y "Editar
      producto" es un dropzone: se puede arrastrar y soltar archivos, o
      hacer clic para abrir el selector nativo.
- [x] Al agregar fotos (nuevas o ya existentes en modo edición), se muestran
      como miniaturas en una fila, numeradas según su posición actual.
- [x] Las miniaturas — tanto las ya guardadas como las recién agregadas en
      la misma sesión del modal — se pueden arrastrar entre sí para cambiar
      el orden final, mezclando libremente existentes y nuevas.
- [x] La foto en la posición 0 (la primera) queda marcada automáticamente
      como `is_primary`; ya no existe un control manual separado para
      "marcar como principal" — se deriva del orden.
- [x] Se respeta el límite de 4 fotos por producto: el dropzone no permite
      agregar más allá del máximo restante, y la validación de servidor
      existente (`UpdateProductRequest`) sigue rechazando el exceso.
- [x] Una foto recién agregada (todavía no guardada) se puede quitar de la
      selección con un botón "×" antes de enviar el formulario. Las fotos ya
      guardadas **no** tienen esa opción en este modal — eliminarlas
      individualmente no se pidió y no está implementado (evita un botón que
      aparente borrar pero no lo haga).
- [x] Al guardar (submit normal del formulario, sin AJAX aparte), el
      servidor aplica el orden final: reordena las fotos existentes
      (`position`/`is_primary`) y crea las nuevas en su posición
      correspondiente, incluso si están intercaladas entre las existentes.
- [x] Sin JavaScript o sin el campo `orden_fotos` (compatibilidad), el
      comportamiento cae de vuelta al anterior: las fotos nuevas se agregan
      al final en el orden recibido.

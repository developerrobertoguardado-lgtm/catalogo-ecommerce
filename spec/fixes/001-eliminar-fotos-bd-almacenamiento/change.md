# Change: Eliminar fotos del modal borra BD + almacenamiento

## Reproducción
1. Ir a `/admin/productos` y abrir el modal "Editar" de un producto que ya
   tiene fotos subidas.
2. Intentar quitar una foto ya guardada (la X de "quitar").
3. Verificar en la base (`product_images`) y en el almacenamiento
   (`storage/app/public/product-images/`) si la foto se eliminó.

## Comportamiento esperado
- El usuario puede quitar tanto fotos recién seleccionadas como fotos ya
  guardadas desde el modal.
- Al confirmar, las fotos guardadas que se quitaron se eliminan de la tabla
  `product_images` **y** el archivo físico se borra del disco
  (`storage/app/public/...`) para no acumular recursos huérfanos.
- Si quedan fotos, la primera pasa a ser la principal (`is_primary = true`),
  y la regla de máximo 4 considera solo las que permanecen.

## Comportamiento actual
- En el modal de editar, las fotos ya guardadas **no tienen botón para
  eliminarlas** (la `X` solo aparece para fotos recién agregadas,
  `x-show="item.type === 'new'"`).
- El backend (`ProductController::sincronizarFotos`) solo reordena las
  existentes y agrega nuevas; **nunca borra** registros de `ProductImage` ni
  archivos del disco al actualizar.
- Por tanto, una foto guardada no se puede quitar por la UI, y si se quitara
  el token quedaría un `ProductImage` huérfano + archivo en disco consumiendo
  recursos.

# Change: Servir correctamente las imágenes públicas de productos

## Reproducción
1. Iniciar sesión en el panel de administración.
2. Crear o editar un producto y subir una imagen.
3. Guardar el producto.
4. Volver a abrir el modal de edición o revisar la tabla de productos.

## Comportamiento esperado
La imagen guardada se visualiza en la tabla del CRUD y en las miniaturas del modal de edición.

## Comportamiento actual
Después de guardar, la imagen no se visualiza o aparece rota. La URL `/storage/product-images/...` responde 403.

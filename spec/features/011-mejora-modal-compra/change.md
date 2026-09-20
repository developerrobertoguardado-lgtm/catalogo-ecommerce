# Change: Mejora visual del modal de compra

## Problema / Usuario
El modal de compra necesita una guía visual más clara para que el cliente complete los datos de entrega con menos dudas y errores.

## Descripción funcional
Rediseñar visualmente el modal de compra existente tomando como referencia la imagen proporcionada: encabezado con título y subtítulo, botón de cierre, resumen del producto, cantidad, precio y total, campos con iconos, separadores, estados visuales, método de pago y botón de confirmación destacado.

Se conservarán los campos, validaciones, creación del pedido y redirección actuales. El modal debe funcionar en modo claro/oscuro y adaptarse a móvil. Al crear correctamente el pedido, el botón mostrará `Creando pedido...` y, cuatro segundos después, se abrirá WhatsApp con toda la información del pedido.

## Criterios de aceptación
- El modal conserva todos los campos y validaciones actuales.
- La interfaz incorpora título, subtítulo, cierre, resumen, iconos, separadores, colores, total y botón destacado.
- La zona Lima/provincias permanece visible y seleccionable.
- Se conservan textos, placeholders y errores debajo de cada campo.
- El resumen muestra producto, cantidad, precio y total correctamente.
- El botón conserva el estado `Creando pedido...` durante la creación.
- El pedido se crea y mantiene su flujo actual de redirección.
- WhatsApp se abre automáticamente 4 segundos después de crear el pedido con toda la información.
- Cancelar o cerrar el modal no crea el pedido.
- El modal es usable en móvil sin desbordamiento.
- El modal se visualiza correctamente en modo claro y oscuro.

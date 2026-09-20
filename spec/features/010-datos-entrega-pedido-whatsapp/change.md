# Change: Datos de entrega antes del pedido por WhatsApp

## Problema / Usuario
Los clientes necesitan proporcionar datos adicionales de contacto y entrega para mejorar la experiencia de compra, reducir equivocaciones y permitir que el vendedor coordine correctamente cada pedido.

## Descripción funcional
Al pulsar el botón de compra en el detalle de un producto, se abrirá un modal con un formulario de entrega. El formulario solicitará nombre completo, número telefónico, zona de entrega (Lima o provincias), dirección completa, ciudad escrita por el usuario y referencias o notas opcionales.

El modal mostrará el detalle del producto, cantidad, precio unitario y total. Los campos obligatorios mostrarán errores debajo del input cuando estén vacíos o sean inválidos. Cancelar cerrará el modal sin crear el pedido. Al confirmar se mostrará `Creando pedido...`; después de crear el pedido se abrirá una página de redirección a WhatsApp con el resumen del pedido y los datos de entrega.

El pedido no será bloqueado por falta de stock ni por solicitar una cantidad superior al stock; el vendedor podrá confirmar disponibilidad posteriormente.

## Criterios de aceptación
- El botón de compra abre el modal de datos de entrega.
- El modal incluye nombre completo, teléfono, zona Lima/provincias, dirección, ciudad y notas.
- Lima o provincias es una selección obligatoria.
- Nombre, teléfono, zona, dirección y ciudad son obligatorios.
- Notas es opcional y usa el placeholder `Referencia para entrega o nota de pasos para llamar o entregar`.
- El detalle muestra producto, cantidad, precio unitario y total.
- Los errores aparecen debajo del campo correspondiente sin perder los datos ingresados.
- Cancelar no crea ningún pedido.
- Confirmar muestra `Creando pedido...` y evita envíos duplicados.
- El pedido se guarda y luego se muestra una página para continuar a WhatsApp.
- El mensaje de WhatsApp incluye los datos del pedido y entrega.
- Se permite crear el pedido con stock cero o cantidad superior al stock.
- La interfaz funciona correctamente en móvil.

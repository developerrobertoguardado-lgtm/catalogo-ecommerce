# Validation: Paleta de colores - aplicación completa en admin y catálogo

## Test de regresión
- Configurar paleta con menú `#0dc93c` (verde) y botones `#e63946` (rojo)
- Verificar que `html[data-theme='dark'] .admin-sidebar` tiene `background-color: rgb(13, 201, 60)` (el verde configurado)
- Verificar que `.btn-primary` y `.btn-success` en catálogo y admin tienen `background-color: rgb(230, 57, 70)` (el rojo configurado)
- Verificar que el botón "Comprar por WhatsApp" en detalle de producto usa el color de botones configurado
- Verificar que la vista previa en Aspecto visual muestra solo "Botón" y "Menú" (sin "Acento")

## Estado
- [ ] Corregido
- [ ] Testeado
- [ ] Verificado en el comportamiento reportado
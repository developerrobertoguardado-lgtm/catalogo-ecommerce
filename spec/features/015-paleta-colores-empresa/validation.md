# Validation: Paleta de colores de la empresa

## Criterios de aceptación a validar
- El tab `Aspecto visual` aparece en Configuración.
- Se puede fijar cada color (primario, secundario, botones, menú, fondo,
  texto) con picker o texto hex.
- La vista previa en vivo refleja los cambios antes de guardar.
- Al guardar, los colores persisten en `store_settings` sin afectar nombre,
  WhatsApp, moneda ni logo.
- Botones, menú, acentos y fondos cambian tanto en catálogo como en admin.
- Modo claro/oscuro se mantiene con contraste correcto.
- Sin paleta configurada se usan los colores por defecto.
- Formato de color inválido es rechazado con mensaje.
- No se rompen rutas ni operaciones existentes.

## Plan de testing
- Test de regresión: persiste la paleta y conserva la configuración actual.
- Test de validación: formato hex inválido es rechazado.
- Test de defaults: sin paleta no se aplica color personalizado.
- Test de compilación: `npm run build` sin errores.
- Suite completa con Pest.
- Validación visual con MCP en catálogo y admin (escritorio y móvil, claro y
  oscuro).

## Estado
- [ ] Spec aprobada
- [ ] Implementada
- [ ] Testeada
- [ ] Desplegada

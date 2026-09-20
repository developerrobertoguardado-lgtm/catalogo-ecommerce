# Tasks: Paleta de colores de la empresa

- [ ] Agregar migración que extienda `store_settings` con los campos de color
      (`primary_color`, `secondary_color`, `button_color`, `menu_color`,
      `background_color`, `text_color`).
- [ ] Actualizar el modelo `StoreSetting` con los nuevos campos fillable.
- [ ] Extender el Form Request de configuración para validar el formato hex de
      cada color.
- [ ] Actualizar `StoreSettingController` para persistir los colores.
- [ ] Agregar tab `Aspecto visual` en la vista de Configuración con los pickers
      de color.
- [ ] Implementar la vista previa en vivo de los colores antes de guardar.
- [ ] Exponer las variables CSS desde la paleta para el catálogo público.
- [ ] Aplicar las variables en botones, menú, acentos y fondos del catálogo.
- [ ] Aplicar las variables en botones, menú, acentos y fondos del panel admin.
- [ ] Mantener modo claro/oscuro con la paleta sobre ambos temas.
- [ ] Agregar tests de regresión (guardado, validación de formato, aplicación
      de variables).
- [ ] Compilar frontend (`npm run build`) y correr la suite completa.
- [ ] Validar con MCP en catálogo y admin, escritorio y móvil.

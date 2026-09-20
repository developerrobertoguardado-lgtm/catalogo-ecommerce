# Tasks: Eliminar fotos del modal borra BD + almacenamiento

- [ ] En `fotos-dropzone.js`: añadir método `quitarExistente(index)` que quita el
      item, agrega su `id` al array `eliminados` y sincroniza inputs.
- [ ] Mantener en el estado Alpine un array `eliminados` inicializado vacío y
      limpiarlo si se re-abre el modal (reinicialización).
- [ ] En `fotos-dropzone.blade.php`: mostrar el botón `X` de borrado para items
      `existing` además de `new`, y llamar `quitarExistente`/`quitarNueva` según
      tipo.
- [ ] Agregar `<input type="hidden" name="fotos_eliminar[]">` que publique los
      IDs eliminados (repitido por cada ID).
- [ ] Evitar que el drop zone siga aceptando drag si se alcanza el máximo tras
      una eliminación (la lógica de `remaining` ya lo cubre).
- [ ] En `ProductController::sincronizarFotos`: borrar las `ProductImage`
      cuyos IDs vengan en `fotos_eliminar` (y su archivo en disco) antes de
      reordenar/insertar.
- [ ] Tras el borrado, recomputar `is_primary` (primera posición) y recompactar
      `position` de las imágenes restantes.
- [ ] En `UpdateProductRequest::withValidator`: descontar `count(fotos_eliminar)`
      del cálculo de "máximo 4" (las que quedan + nuevas ≤ 4).
- [ ] Agregar test de regresión que elimine una foto guardada y verifique que se
      borra de BD y del disco en el `update`.
- [ ] Compilar assets y verificar en navegador (E2E) el flujo de quitar una foto
      guardada.

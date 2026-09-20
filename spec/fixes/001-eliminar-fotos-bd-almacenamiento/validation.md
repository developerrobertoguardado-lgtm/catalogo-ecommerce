# Validation: Eliminar fotos del modal borra BD + almacenamiento

## Test de regresión
**Pest (Feature):** en `ProductoCrudTest` agregar un test:

> "elimina una foto guardada de la BD y del almacenamiento al editar"

que: crea un producto con 2 `ProductImage` (usando `Storage::fake('public')` y
`UploadedFile` + `store` real para poblar rutas), envía un `PUT` al `update`
incluyendo en `fotos_eliminar` el id de una de ellas y un `orden_fotos` que solo
mencione la que queda, y luego verifica:
- `ProductImage::find($eliminada)` es `null` (borrada de BD),
- `Storage::disk('public')->exists($pathOriginal)` es `false` (borrado de disco),
- la imagen restante queda como `is_primary = true`,
- `fotos_eliminar` descontado en la validación (permite pasar del máximo si se
  eliminan).

Sin el fix, este test falla porque el backend nunca borra la `ProductImage` ni el
archivo (y la vista no permitía quitarla).

**E2E (navegador real):** con puppeteer-core (equivalente a Playwright), abrir
`/admin/productos`, editar un producto con fotos, hacer clic en la `X` de una
foto guardada, guardar, y confirmar en la página que la foto desapareció y en la
BD/`storage` que el registro y archivo se eliminaron.

## Estado
- [ ] Corregido
- [ ] Testeado
- [ ] Verificado en el comportamiento reportado

# Impact: Eliminar fotos del modal borra BD + almacenamiento

## Causa raíz
El dropzone (`fotos-dropzone.js` / `fotos-dropzone.blade.php`) solo permite
quitar fotos de tipo `new` (aún no persistidas); las de tipo `existing` (ID de
`ProductImage` guardado) no exponen un botón de borrado. Además, el backend
(`ProductController::sincronizarFotos`) trata únicamente los tokens `existing:`
(hacia reordenar) y `new:` (hacia insertar) de `orden_fotos`, sin recoger qué
imágenes existentes se marcaron para eliminar ni borrar su fila/archivo. Resulta,
entonces, imposible (por UI) y no soportado (por backend) eliminar fotos ya
guardadas sin dejar basura en BD y en disco.

## Módulos/archivos afectados
- `resources/js/fotos-dropzone.js` — añadir `quitarExistente()`, mantener array
  `eliminados`, y emitir `fotos_eliminar[]`.
- `resources/views/components/fotos-dropzone.blade.php` — mostrar `X` también
  para fotos `existing`, y agregar `<input name="fotos_eliminar[]">`.
- `app/Http/Controllers/Admin/ProductController.php` — `sincronizarFotos()`
  (o un método nuevo) que borre las `ProductImage` marcadas + su archivo, y
  recompute `is_primary` tras los borrados.
- `app/Http/Requests/UpdateProductRequest.php` — la regla de "máximo 4" debe
  descontar las fotos a eliminar (las que queden + nuevas ≤ 4).
- `tests/Feature/Admin/ProductoCrudTest.php` — nuevo test de regresión.

## Riesgo de la corrección
- Bajo. Afecta solo al flujo de fotos del admin, ya cubierto por tests de
  reordenamiento e inserción que deben seguir pasando.
- Debe cuidarse que al borrar la única/última foto quede el producto sin
  `is_primary` sin romper la vista (el catálogo ya maneja "Sin foto").
- El `Storage::disk('public')->delete()` debe usarse con la ruta `path` real
  del registro para no borrar archivos incorrectos.
- Nunca reindexar `position` de forma que queden huecos indebidos; se recomienda
  recompactar las posiciones de las que quedan.

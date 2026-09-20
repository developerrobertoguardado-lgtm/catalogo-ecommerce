# Impact: Servir correctamente las imágenes públicas de productos

## Causa raíz
En Laravel 12 el disco `local` tenía `serve => true` y `.env` lo definía como disco predeterminado. Eso registraba la ruta `/storage/{path}` para el disco privado `local`, que interceptaba las URLs del disco público y respondía 403 por firma inválida. Además, el directorio compartido `storage/app/public/product-images` no tenía permisos de escritura para el usuario `sail`; `store()` devolvía `false`, que terminaba persistido como `path=0`.

## Módulos/archivos afectados
- `config/filesystems.php`: desactiva el serving del disco privado y habilita el serving explícito del disco público.
- Permisos de `storage/app/public`: se ajustan para permitir escritura del proceso web en el entorno Sail/Windows.
- `tests/Feature/Admin/ProductoCrudTest.php`: prueba de regresión para la URL pública de una imagen.
- Vistas de productos y componente de dropzone: consumen `Storage::url()` y quedan cubiertos por la corrección de configuración.

## Riesgo de la corrección
Bajo. El disco privado deja de exponer rutas HTTP, el disco público sirve únicamente archivos públicos y los fallos de escritura ahora se reportan en vez de guardar rutas inválidas. Las operaciones de almacenamiento y eliminación no cambian.

# Impact: Logo de tienda en headers

## Módulos/entidades existentes que toca
- `StoreSetting` y configuración administrativa.
- Migración y Form Request de configuración.
- Headers de layout público y administrativo.
- Almacenamiento público de imágenes.
- No modifica productos, pedidos ni operaciones CRUD.

## Endpoints nuevos o modificados
Se reutiliza `PUT /admin/configuracion` con `multipart/form-data`. No se agregan rutas nuevas.

## Cambios de datos
Se agrega el campo nullable `logo_path` en `store_settings` para guardar la ruta del archivo público.

## Riesgos / dependencias con otras specs
- Debe conservar el modo claro/oscuro de `008`.
- Debe conservar las alertas SweetAlert2 de `006` para éxito y errores.
- La eliminación/reemplazo debe borrar el archivo anterior sin afectar otros archivos.
- Debe mantenerse el comportamiento responsive del layout administrativo.

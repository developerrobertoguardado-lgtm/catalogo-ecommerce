# Change: Logo de tienda en headers

## Problema / Usuario
El catálogo necesita una identidad visual más clara y consistente. La tienda debe poder mostrar su logo en lugar del header negro actual, tanto para clientes del catálogo como para administradores del panel.

## Descripción funcional
Eliminar la franja negra del header del catálogo y agregar en Configuración un campo para cargar el logo de la tienda. El logo se mostrará en el header del catálogo público y en el header administrativo. Si no existe un logo, se mostrará el nombre de la tienda como fallback.

El archivo aceptará PNG, JPG, JPEG y WEBP, con un límite máximo de 2 MB. El logo debe conservar sus proporciones, adaptarse a escritorio y móvil, y poder reemplazarse o eliminarse. Se mantendrán intactas búsqueda, navegación, rutas, operaciones CRUD, modo claro/oscuro y demás funcionalidades.

## Criterios de aceptación
- Se elimina la franja negra ubicada en o debajo del header del catálogo.
- Configuración permite cargar, reemplazar y eliminar el logo.
- Se aceptan PNG, JPG, JPEG y WEBP de hasta 2 MB.
- Se rechazan formatos no permitidos y archivos mayores a 2 MB con error visible.
- El logo aparece en el header público y administrativo.
- Si no hay logo, ambos headers muestran el nombre de la tienda.
- El logo mantiene proporciones y no se deforma.
- El header se adapta correctamente a móvil sin desbordamiento.
- Se conservan textos, rutas, búsqueda, operaciones y modo claro/oscuro.

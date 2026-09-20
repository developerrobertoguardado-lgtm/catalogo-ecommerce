# Change: Rediseño marketplace responsive del catálogo

## Problema / Usuario
El catálogo público debe transmitir una experiencia de marketplace moderna,
confiable y orientada a conversión. Los clientes necesitan encontrar productos
con rapidez en desktop y mobile, consultar filtros sin fricción y visualizar
mejor las cards y el detalle, sin registrarse ni entrar a funcionalidades de
cliente que no corresponden al alcance del catálogo.

## Descripción funcional
Rediseñar visualmente el módulo público de catálogo de Rápido Compra siguiendo
la referencia Marketplace Moderno - Versión 4. La experiencia tendrá header,
buscador, breadcrumb, filtros, categorías, grid de productos, ordenamiento y
paginación conservando la lógica existente. En mobile los filtros usarán un
drawer lateral con scroll interno, botón cerrar y `Aplicar filtros`. Las cards
mostrarán imagen, badge contextual, nombre, descripción corta, precio en PEN y
CTA `Agregar` con carrito. El detalle de producto se mantiene orientado a la
compra actual por WhatsApp, sin registro obligatorio.

La interfaz deberá consumir la paleta dinámica configurada desde Admin tanto en
modo claro como oscuro, usando Inter y componentes accesibles. No se agregan
carrito multi-producto, login, perfil, historial ni dashboard de cliente.

## Criterios de aceptación
- Desktop: header con logo, buscador y acciones del catálogo; breadcrumb;
  sidebar de filtros; grid de productos; ordenamiento/paginación existentes.
- Mobile: header compacto, buscador, categorías compactas, grid de 2 columnas
  y filtros exclusivamente dentro de drawer lateral de 80-90% de ancho.
- Drawer: botón abrir `Filtros`, botón cerrar, scroll interno, categorías,
  precio, disponibilidad y demás filtros existentes, conservando selección y
  botón inferior `Aplicar filtros`.
- Cards: imagen visible, nombre máximo dos líneas, descripción corta, precio
  jerárquico, moneda PEN, badges cuando correspondan y CTA `Agregar` con
  icono carrito; agotado debe verse deshabilitado.
- Detalle: slider, descripción, precio, stock, cantidad, CTA y modal de compra
  adaptados a desktop/mobile sin overflow.
- Light/Dark: misma estructura, superficies/textos/bordes adaptados y color de
  marca proveniente de Admin sin hardcodear la paleta.
- Se conserva la navegación sin autenticación, búsqueda, filtros, paginación,
  cantidades, registro del pedido y apertura de WhatsApp.
- No se agregan menú de órdenes/compras, historial, perfil, registro, login
  obligatorio ni dashboard de cliente.
- No se agregan entidades, columnas ni endpoints.

# Validation: Mejora UI/UX del catálogo público

## Criterios de aceptación a validar
- CA-01: Sin barra superior oscura con el texto "Pedidos directos por WhatsApp ·
  Sin registro, sin pasarela de pago" en el catálogo.
- CA-02: Sin botón "WhatsApp" verde en el header.
- CA-03: Header con solo logo/nombre de la tienda + barra de búsqueda de
  producto.
- CA-04: Filtros intactos (nombre, categoría, rango de precio) — funcionan igual
  y en la misma posición.
- CA-05: Grilla de cards con foto arriba (cuadrada), nombre, categoría, precio y
  hover sutil (mouse y teclado).
- CA-06: Detalle de producto conserva el flujo de compra por WhatsApp (slider,
  stepper de cantidad, botón "Comprar por WhatsApp").
- CA-07: Solo Bootstrap 5 como framework CSS (sin Tailwind).
- CA-08: Footer y layout funcionales y consistentes.

## Plan de testing
1. **Manual (visual):** cargar `http://localhost`, confirmar header reducido
   (logo + búsqueda), sin barra superior y sin botón WhatsApp. Verificar que la
   grilla se vea limpia y que los filtros se apliquen correctamente.
2. **Manual (búsqueda desde el header):** escribir un nombre de producto en la
   barra del header y confirmar que filtra el catálogo por nombre.
3. **Manual (detalle):** abrir un producto, cambiar cantidad, hacer "Comprar por
   WhatsApp" y confirmar que se genera el pedido y abre `wa.me`.
4. **Automático regresión:** correr la suite de tests existente
   (`./vendor/bin/sail artisan test` o `docker compose exec laravel.test php
   artisan test`) — debe seguir en verde, ya que no se cambió lógica de
   backend/rutas.
5. **Revisión de stack:** confirmar que no haya clases/utilities de Tailwind en
   las vistas tocadas (`git grep` de `class="` con utilidades Tailwind o
   `font-`/`flex-`/`mt-` estilo Tailwind fuera de Bootstrap).

## Estado
- [x] Spec aprobada
- [x] Implementada
- [x] Testeada
- [ ] Desplegada

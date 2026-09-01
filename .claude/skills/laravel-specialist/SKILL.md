---
name: laravel-specialist
description: Provee convenciones, snippets y buenas prácticas específicas de
  Laravel 12 (Blade + Bootstrap 5 + Alpine.js puntual) para EL PROYECTO
  Ecommerce Catálogo + Pedidos por WhatsApp. Úsalo siempre que se escriba,
  revise o depure código relacionado con rutas, controladores, Form
  Requests, vistas Blade, modales de Bootstrap o interactividad Alpine.js en
  este repositorio — por ejemplo al crear un nuevo controller, modificar el
  flujo de "Comprar" por WhatsApp, tocar un modal de CRUD del admin, o
  resolver errores de validación/renderizado.
---

# laravel-specialist

## Contexto de este proyecto
Aplicación Laravel 12 server-rendered (sin API JSON como interfaz principal
salvo el endpoint `POST /pedidos`). Dos superficies: catálogo público
(sin autenticación) y panel admin (`/admin`, protegido por `auth`). Ver
`/spec/04-api-contracts.md` para el listado completo de rutas.

## Convenciones específicas de este repo
- Controllers de catálogo público en `app/Http/Controllers/Catalogo/`
  (ej. `CatalogoController`, `ProductoDetalleController`,
  `PedidoController`).
- Controllers de admin en `app/Http/Controllers/Admin/`
  (ej. `ProductController`, `CategoryController`, `OrderController`,
  `StoreSettingController`), todos con middleware `auth` aplicado en las
  rutas (`routes/web.php`), no en cada controller individualmente.
- Form Requests en `app/Http/Requests/` (ej. `StoreProductRequest`,
  `UpdateProductRequest`), uno por acción de escritura del admin.
- Servicio dedicado `app/Services/PedidoWhatsAppService.php` para: validar
  `quantity` contra `stock`, armar el mensaje de texto, generar la URL
  `wa.me` codificada, y crear el `Order`/`OrderItem`. El controller
  `PedidoController` solo orquesta: recibe el request, llama al service,
  devuelve el JSON con `whatsapp_url`.
- Vistas en `resources/views/catalogo/` (público) y
  `resources/views/admin/` (panel), con parciales compartidos en
  `resources/views/components/` (ej. `<x-producto-card>`,
  `<x-slider-fotos>`, `<x-icon>`) para no duplicar markup.
- CRUD del admin (productos, categorías) es en **modales de Bootstrap**, no
  en páginas `create`/`edit` separadas — esas rutas no existen. El
  formulario de "nuevo" y un modal de "editar" por fila viven embebidos en
  la vista `index` del recurso (ver `admin/productos/index.blade.php` y el
  partial `admin/productos/_campos.blade.php`). Si la validación falla, el
  controller redirige `back()` normalmente (`withErrors`+`withInput`); el
  campo oculto `<input type="hidden" name="_modal" value="...">` en cada
  form, combinado con el script al final de la vista que llama a
  `new bootstrap.Modal(...).show()` cuando `$errors->any() && old('_modal')`,
  reabre el modal correcto con los errores visibles.

## Estructura de carpetas relevante
```
app/Http/Controllers/Catalogo/
app/Http/Controllers/Admin/
app/Http/Requests/
app/Services/PedidoWhatsAppService.php
app/Models/
resources/views/catalogo/
resources/views/admin/
resources/views/components/
routes/web.php
```

## Patrones a seguir
- Route::resource para los CRUDs de admin (`productos`, `categorias`)
  cuando el conjunto de acciones coincide con el estándar REST de Laravel;
  rutas explícitas solo para lo que no encaja (ej. reordenar fotos).
- Alpine.js solo donde Bootstrap no tiene componente nativo (el selector de
  cantidad y el fetch a `/pedidos` en el detalle de producto). Para todo lo
  demás (modal, carousel, accordion, offcanvas, dropdown) usar los
  componentes nativos de Bootstrap vía atributos `data-bs-*` — no
  reimplementar con Alpine algo que Bootstrap ya resuelve.
- Bootstrap: usar clases y componentes tal cual (`form-control`,
  `form-select`, `card`, `table`, `badge`, tamaño de input por defecto, sin
  `-sm`/`-lg` salvo que se pida explícitamente). No agregar Tailwind ni
  ningún otro framework de CSS — un solo framework en todo el proyecto (ver
  ADR-004 en `/spec/02-architecture.md`). Los pocos estilos que Bootstrap no
  cubre (tamaños de ícono, aspect-ratio de imágenes) van en clases propias
  chicas y explícitas en `resources/css/app.css` (`.icon-sm/.icon-md/.icon-lg`,
  `.product-card-img`, etc.), no en utilidades sueltas.
- El número de WhatsApp destino se lee siempre desde `StoreSetting` (con
  fallback a `.env` solo si `StoreSetting` no tiene fila creada aún) — nunca
  hardcodeado en una vista o controller.

## Errores comunes a evitar en este proyecto
- No poner lógica de armado del mensaje de WhatsApp ni de creación del
  `Order` directamente en el controller — siempre vía
  `PedidoWhatsAppService`, para que sea testeable de forma aislada
  (`tests/Unit/PedidoWhatsAppServiceTest.php`).
- No superar el límite de 4 `ProductImage` por producto al construir el
  formulario de admin ni al procesar el request — validar explícitamente,
  no confiar solo en la UI.
- No mezclar validación de negocio (ej. `quantity <= stock`) dentro del Form
  Request si depende de datos que cambian entre el render del form y el
  submit (ej. stock actual) — esa validación va en el Service, evaluada al
  momento de la escritura.

## Snippet de referencia
```php
// app/Services/PedidoWhatsAppService.php
class PedidoWhatsAppService
{
    public function crearPedido(Product $product, int $quantity): Order
    {
        abort_if($quantity > $product->stock, 409, 'Stock insuficiente');

        $order = Order::create([
            'whatsapp_number' => StoreSetting::current()->whatsapp_number,
            'total' => $product->price * $quantity,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal' => $product->price * $quantity,
        ]);

        return $order;
    }

    public function generarUrlWhatsApp(Order $order): string
    {
        $mensaje = $order->items->map(
            fn ($item) => "{$item->quantity}x {$item->product_name} - $" . number_format($item->subtotal, 2)
        )->implode("\n");

        return "https://wa.me/{$order->whatsapp_number}?text=" . urlencode($mensaje);
    }
}
```

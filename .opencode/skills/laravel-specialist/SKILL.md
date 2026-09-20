---
name: laravel-specialist
description: Provee convenciones, snippets y buenas prácticas específicas de Laravel 12 (Blade + Bootstrap 5 + Alpine.js) para EL PROYECTO Ecommerce Catálogo + Pedidos por WhatsApp. Úsalo siempre que se escriba, revise o depure código relacionado con rutas, controladores, Form Requests, vistas Blade, modales de Bootstrap o interactividad Alpine.js en este repositorio — por ejemplo al crear un nuevo controller, modificar el flujo de "Comprar" por WhatsApp, tocar un modal de CRUD del admin, o resolver errores de validación/renderizado.
---

# laravel-specialist

## Contexto de este proyecto

Laravel 12 con Blade como motor de plantillas, Bootstrap 5 para estilos/componentes (modales, carousel, accordion, offcanvas, dropdown), y Alpine.js solo para lógica sin equivalente nativo en Bootstrap (stepper de cantidad, fetch del flujo de compra por WhatsApp). El admin usa modales de Bootstrap para CRUD (no páginas separadas), con reapertura automática ante error de validación.

## Convenciones específicas de este repo

- **Rutas:** Públicas en español (`/productos`, `/categorias/*`); admin con prefijo `/admin/*` y middleware `auth`.
- **Controllers:** PascalCase (`ProductController`, `PedidoController`). CRUDs simples van directo en el controller sin Repository/Service.
- **Form Requests:** Dedicados para toda validación de entrada admin (`StoreProductRequest`, `UpdateProductRequest`). No validar inline en controller salvo casos triviales.
- **Models:** PascalCase (`Product`, `Category`, `ProductImage`, `Order`, `OrderItem`). Columnas snake_case en BD.
- **Vistas:** Blade Resources para evitar duplicar markup entre catálogo público y admin. Modales del admin embebidos en el `index` (no páginas `create`/`edit` separadas).
- **Validación con modales:** Campo oculto `_modal` + script que llama a `bootstrap.Modal(...).show()` para reabrir el modal correcto con errores visibles.

## Estructura de carpetas relevante

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── CatalogoController.php
│   │   ├── ProductoController.php
│   │   ├── PedidoController.php
│   │   └── admin/
│   │       ├── DashboardController.php
│   │       ├── ProductoController.php
│   │       ├── CategoriaController.php
│   │       └── ConfiguracionController.php
│   └── Requests/
│       ├── StoreProductRequest.php
│       └── UpdateProductRequest.php
├── Models/
├── Services/
│   └── PedidoWhatsAppService.php
└── Providers/

resources/views/
├── layouts/
├── catalogo/
├── admin/
│   ├── productos/
│   └── categorias/
└── components/
```

## Patrones a seguir

- **Repository/Service solo donde aporta valor:** Armado de mensaje de WhatsApp (`PedidoWhatsAppService`), registro de `Order`/`OrderItem`, reglas de las 4 fotos por producto. CRUDs simples van directo en el controller.
- **Modales de Bootstrap:** Un modal por fila para "editar". Usar `data-bs-toggle="modal"` y `data-bs-target="#modal-edit-{{id}}"`.
- **Alpine.js mínimo:** Solo para stepper de cantidad (`x-data`, `x-on:click`, `x-text`) y fetch a `/pedidos`. No usar para lógica que Bootstrap maneja nativamente.
- **Paginación:** `Paginator::useBootstrapFive()` ya configurado.

## Errores comunes a evitar en este proyecto

- **No mezclar Bootstrap con Tailwind:** El proyecto migró de Tailwind a Bootstrap (ADR-004). No reintroducir utilidades de Tailwind.
- **No crear rutas `create`/`edit` de página completa:** El CRUD es en modales.
- **No validar inline en controller:** Usar Form Requests dedicados.
- **No crear Repository/Service por sistema:** Solo donde hay lógica no trivial.

## Snippet de referencia

```php
// Ejemplo de controller con modal y validación
class ProductoController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        
        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente');
    }
    
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        
        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }
}
```
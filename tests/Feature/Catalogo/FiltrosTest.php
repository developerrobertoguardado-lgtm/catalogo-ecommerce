<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

it('filtra productos por nombre', function () {
    Product::factory()->create(['name' => 'Mesa de Madera']);
    Product::factory()->create(['name' => 'Silla de Oficina']);

    $this->get(route('catalogo.index', ['nombre' => 'Mesa']))
        ->assertOk()
        ->assertSee('Mesa de Madera')
        ->assertDontSee('Silla de Oficina');
});

it('filtra productos por categoría', function () {
    $ropa = Category::factory()->create(['name' => 'Ropa']);
    $tecnologia = Category::factory()->create(['name' => 'Tecnología']);

    Product::factory()->for($ropa)->create(['name' => 'Camisa']);
    Product::factory()->for($tecnologia)->create(['name' => 'Audífonos']);

    $this->get(route('catalogo.categoria', $tecnologia))
        ->assertOk()
        ->assertSee('Audífonos')
        ->assertDontSee('Camisa');
});

it('muestra imagen o fallback y marca visualmente la categoría seleccionada', function () {
    $categoria = Category::factory()->create(['name' => 'Tecnología visual']);
    Product::factory()->for($categoria)->create(['name' => 'Producto visual']);

    $this->get(route('catalogo.index', ['categoria_id' => $categoria->id]))
        ->assertOk()
        ->assertSee('category-filter-item is-selected', false)
        ->assertSee('category-avatar-fallback', false)
        ->assertSee('Eliminar filtros');
});

it('filtra productos por rango de precio', function () {
    Product::factory()->create(['name' => 'Producto Barato', 'price' => 10]);
    Product::factory()->create(['name' => 'Producto Caro', 'price' => 300]);

    $this->get(route('catalogo.index', ['precio_min' => 5, 'precio_max' => 50]))
        ->assertOk()
        ->assertSee('Producto Barato')
        ->assertDontSee('Producto Caro');
});

it('combina filtros de nombre y categoría', function () {
    $categoria = Category::factory()->create();
    $otraCategoria = Category::factory()->create();

    Product::factory()->for($categoria)->create(['name' => 'Zapato Deportivo']);
    Product::factory()->for($otraCategoria)->create(['name' => 'Zapato Formal']);

    $this->get(route('catalogo.index', ['nombre' => 'Zapato', 'categoria_id' => $categoria->id]))
        ->assertOk()
        ->assertSee('Zapato Deportivo')
        ->assertDontSee('Zapato Formal');
});

it('muestra todos los campos del modal de compra a ancho completo', function () {
    $producto = Product::factory()->create();

    $this->get(route('catalogo.show', $producto))
        ->assertOk()
        ->assertDontSee('col-md-6 purchase-field', false)
        ->assertSee('col-12 purchase-field', false);
});

it('muestra la miniatura del producto en el resumen del modal de compra', function () {
    $producto = Product::factory()->create();
    ProductImage::factory()->for($producto)->create([
        'position' => 0,
        'is_primary' => true,
        'path' => 'product-images/miniatura.jpg',
    ]);

    $this->get(route('catalogo.show', $producto))
        ->assertOk()
        ->assertSee('purchase-summary-image', false)
        ->assertSee('/storage/product-images/miniatura.jpg', false);
});

it('mantiene acordeones independientes y el estilo visual del precio', function () {
    $producto = Product::factory()->create();

    $this->get(route('catalogo.show', $producto))
        ->assertOk();

    $response = $this->get(route('catalogo.index'));
    $response->assertSee('price-progress-visual', false)
        ->assertSee('catalog-filter', false)
        ->assertDontSee('data-bs-parent="#filtrosAccordion"', false)
        ->assertSee('name="precio_min"', false)
        ->assertSee('name="precio_max"', false);
});

it('adapta filtros, cards y detalle para movil con icono de carrito', function () {
    $producto = Product::factory()->create(['name' => 'Producto responsive']);

    $this->get(route('catalogo.index'))
        ->assertOk()
        ->assertSee('catalog-filter-icon-btn', false)
        ->assertSee('offcanvas-md', false)
        ->assertSee('catalogFiltersPanel', false)
        ->assertSee('Producto responsive');

    $cardView = file_get_contents(resource_path('views/components/producto-card.blade.php'));
    expect($cardView)->toContain('name="cart"')
        ->and($cardView)->not->toContain('name="whatsapp"');

    $this->get(route('catalogo.show', $producto))
        ->assertOk()
        ->assertSee('product-detail-layout', false)
        ->assertSee('product-purchase-card', false)
        ->assertSee('quantity-stepper', false)
        ->assertSee('purchase-modal', false);
});

it('renderiza el marketplace con hero, chips de filtros y estados de card', function () {
    $agotado = Product::factory()->create(['name' => 'Producto agotado marketing', 'stock' => 0]);
    Product::factory()->create(['name' => 'Producto disponible marketing']);

    $this->get(route('catalogo.index'))
        ->assertOk()
        ->assertSee('catalog-hero', false)
        ->assertSee('theme-toggle', false)
        ->assertSee('product-badge', false)
        ->assertSee('is-soldout', false)
        ->assertSee('Aplicar filtros', false);

    // Chips de filtros activos solo cuando hay criterios aplicados
    $this->get(route('catalogo.index', ['nombre' => 'agotado']))
        ->assertOk()
        ->assertSee('filter-chip', false)
        ->assertSee('Búsqueda: agotado', false)
        ->assertSee('filter-chip-clear', false);

    expect(file_get_contents(resource_path('views/components/layouts/app.blade.php')))->toContain('fonts.googleapis.com/css2?family=Inter');
});

it('cierra la paridad movil: chips de categorias, pagination pill y media contenida', function () {
    Product::factory()->count(13)->create();

    $this->get(route('catalogo.index'))
        ->assertOk()
        ->assertSee('category-chip', false)
        ->assertSee('category-chip-icon', false)
        ->assertSee('category-chip-scroll', false)
        ->assertSee('catalog-pagination', false);

    $css = file_get_contents(resource_path('css/app.css'));
    expect($css)->toContain('.product-card-media .product-card-img')
        ->and($css)->toContain('object-fit: cover')
        ->and($css)->toContain('.catalog-pagination .page-link');
});

it('muestra la imagen del card a sangre, sin padding ni margen, respetando el curvado', function () {
    Product::factory()->count(3)->create();

    $view = view('components.producto-card', ['producto' => Product::first()])->render();
    expect($view)->toContain('product-card-media')
        ->and($view)->toMatch('/class="position-relative overflow-hidden product-card-media"/');

    $css = file_get_contents(resource_path('css/app.css'));
    preg_match('/\.product-card-media \.product-card-img\s*\{[^}]+\}/', $css, $matches);
    $block = $matches[0] ?? '';
    expect($block)->not->toContain('padding')
        ->and($block)->toContain('object-fit: cover');
});

it('no deja que los botones de filtros floten ni se sobrepongan al contenido', function () {
    $this->get(route('catalogo.index'))
        ->assertOk()
        ->assertDontSee('card-footer bg-transparent');
    $html = $this->get(route('catalogo.index'))->content();

    $css = file_get_contents(resource_path('css/app.css'));
    $block = Str::between($css, '.sticky-filter-cta', '}');
    expect($block)->not->toContain('position: sticky')
        ->and($block)->not->toContain('bottom: 0');
});

it('centra la paginacion del catalogo en la version movil', function () {
    Product::factory()->count(13)->create();

    $this->get(route('catalogo.index'));

    $css = file_get_contents(resource_path('css/app.css'));
    $block = Str::between($css, '.catalog-pagination .d-sm-none', '}');
    expect($block)->not->toBe('')
        ->and($block)->toContain('justify-content: center');
});

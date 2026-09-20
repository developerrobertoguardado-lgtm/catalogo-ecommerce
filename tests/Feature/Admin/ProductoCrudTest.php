<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create();
});

it('impide el acceso al panel de productos sin autenticarse', function () {
    $this->get(route('admin.productos.index'))
        ->assertRedirect(route('login'));
});

it('lista los productos existentes en el panel', function () {
    $categoria = Category::factory()->create();
    Product::factory()->for($categoria)->create(['name' => 'Zapatillas Urbanas']);

    $this->actingAs($this->admin)
        ->get(route('admin.productos.index'))
        ->assertOk()
        ->assertSee('Zapatillas Urbanas')
        ->assertSee('sash-table-card', false)
        ->assertSee('sash-table', false);
});

it('busca productos por nombre o descripción sin distinguir mayúsculas', function () {
    Product::factory()->create(['name' => 'Cámara Digital', 'description' => 'Equipo para fotografía']);
    Product::factory()->create(['name' => 'Mesa', 'description' => 'Mueble de oficina']);

    $this->actingAs($this->admin)
        ->get(route('admin.productos.index', ['buscar' => 'FOTOGRAFÍA']))
        ->assertOk()
        ->assertSee('Cámara Digital')
        ->assertDontSee('Mueble de oficina');
});

it('pagina productos en diez registros y conserva la búsqueda', function () {
    Product::factory()->count(11)->create(['name' => 'Producto filtrable']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.productos.index', ['buscar' => 'filtrable']));

    $response->assertOk()
        ->assertSee('page=2', false)
        ->assertSee('buscar=filtrable', false);
    expect($response->viewData('productos')->perPage())->toBe(10);
    expect($response->viewData('productos')->total())->toBe(11);
});

it('muestra el mensaje requerido cuando no hay productos', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.productos.index', ['buscar' => 'inexistente']))
        ->assertOk()
        ->assertSee('Sin resultados disponibles');
});

it('incluye el sistema global de alertas y confirmaciones en el panel', function () {
    Product::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.productos.index'))
        ->assertOk()
        ->assertSee('app-alerts-data', false)
        ->assertSee('theme-toggle', false)
        ->assertSee('ecommerce-theme', false)
        ->assertSee('data-lock-on-submit', false)
        ->assertSee('data-confirm=', false);
});

it('define contraste oscuro explícito para el cuerpo de las tablas', function () {
    $styles = file_get_contents(resource_path('css/app.css'));

    expect($styles)
        ->toContain("html[data-theme='dark'] .sash-table tbody td")
        ->toContain('--bs-table-bg-type: var(--admin-surface)');
});

it('preserva campos ocultos al bloquear formularios administrativos', function () {
    $script = file_get_contents(resource_path('js/bloqueo-formularios.js'));

    expect($script)
        ->toContain('input:not([type="hidden"])')
        ->toContain('dataset.lockMirror');
});

it('sirve las imágenes públicas guardadas mediante su URL', function () {
    $path = 'product-images/regresion.jpg';
    Storage::disk('public')->put($path, 'contenido de prueba');

    $this->get(Storage::disk('public')->url($path))
        ->assertOk()
        ->assertHeader('content-type', 'image/jpeg');
});

it('crea un producto con hasta 4 fotos', function () {
    $categoria = Category::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.productos.store'), [
        'category_id' => $categoria->id,
        'name' => 'Camiseta Básica',
        'description' => 'Camiseta de algodón',
        'price' => 25.5,
        'stock' => 10,
        'fotos' => [
            UploadedFile::fake()->image('foto1.jpg'),
            UploadedFile::fake()->image('foto2.jpg'),
        ],
    ]);

    $response->assertRedirect(route('admin.productos.index'));

    $producto = Product::where('name', 'Camiseta Básica')->firstOrFail();

    expect($producto->images)->toHaveCount(2);
    expect($producto->images->where('is_primary', true))->toHaveCount(1);
});

it('no permite superar el máximo de 4 fotos por producto', function () {
    $producto = Product::factory()->create();
    ProductImage::factory()->count(3)->for($producto)->create();

    $response = $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'fotos' => [
            UploadedFile::fake()->image('foto1.jpg'),
            UploadedFile::fake()->image('foto2.jpg'),
        ],
    ]);

    $response->assertSessionHasErrors('fotos');
    expect($producto->images()->count())->toBe(3);
});

it('elimina un producto y sus fotos', function () {
    $producto = Product::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.productos.destroy', $producto))
        ->assertRedirect(route('admin.productos.index'));

    expect(Product::find($producto->id))->toBeNull();
});

it('reordena las fotos existentes según el orden enviado por el dropzone', function () {
    $producto = Product::factory()->create();
    $foto1 = ProductImage::factory()->for($producto)->create(['position' => 0, 'is_primary' => true]);
    $foto2 = ProductImage::factory()->for($producto)->create(['position' => 1, 'is_primary' => false]);
    $foto3 = ProductImage::factory()->for($producto)->create(['position' => 2, 'is_primary' => false]);

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'orden_fotos' => json_encode([
            "existing:{$foto3->id}",
            "existing:{$foto1->id}",
            "existing:{$foto2->id}",
        ]),
    ])->assertRedirect(route('admin.productos.index'));

    expect($foto3->fresh())->position->toBe(0)->is_primary->toBeTrue();
    expect($foto1->fresh())->position->toBe(1)->is_primary->toBeFalse();
    expect($foto2->fresh())->position->toBe(2)->is_primary->toBeFalse();
});

it('intercala una foto nueva con las existentes según el orden del dropzone', function () {
    $producto = Product::factory()->create();
    $existente = ProductImage::factory()->for($producto)->create(['position' => 0, 'is_primary' => true]);

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'fotos' => [UploadedFile::fake()->image('nueva.jpg')],
        'orden_fotos' => json_encode([
            'new:0',
            "existing:{$existente->id}",
        ]),
    ])->assertRedirect(route('admin.productos.index'));

    $producto->refresh();
    $imagenes = $producto->images()->orderBy('position')->get();

    expect($imagenes)->toHaveCount(2);
    expect($imagenes[0]->is_primary)->toBeTrue();
    expect($imagenes[0]->id)->not->toBe($existente->id);
    expect($imagenes[1]->id)->toBe($existente->id);
    expect($imagenes[1]->is_primary)->toBeFalse();
});

it('elimina una foto guardada de la base y del almacenamiento al editar', function () {
    $producto = Product::factory()->create();
    $foto1 = ProductImage::factory()->for($producto)->create([
        'position' => 0,
        'is_primary' => true,
        'path' => UploadedFile::fake()->image('foto1.jpg')->store('product-images', 'public'),
    ]);
    $foto2 = ProductImage::factory()->for($producto)->create([
        'position' => 1,
        'is_primary' => false,
        'path' => UploadedFile::fake()->image('foto2.jpg')->store('product-images', 'public'),
    ]);

    expect(Storage::disk('public')->exists($foto2->path))->toBeTrue();

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'fotos_eliminar' => [$foto2->id],
        'orden_fotos' => json_encode(["existing:{$foto1->id}"]),
    ])->assertRedirect(route('admin.productos.index'));

    expect(ProductImage::find($foto2->id))->toBeNull();
    expect(Storage::disk('public')->exists($foto2->path))->toBeFalse();

    $foto1->refresh();
    expect($foto1->is_primary)->toBeTrue();
    expect($foto1->position)->toBe(0);
    expect($producto->images()->count())->toBe(1);
});

it('permite superar el máximo de 4 si se eliminan fotos existentes', function () {
    $producto = Product::factory()->create();
    $foto1 = ProductImage::factory()->for($producto)->create([
        'position' => 0,
        'is_primary' => true,
        'path' => UploadedFile::fake()->image('foto1.jpg')->store('product-images', 'public'),
    ]);
    $foto2 = ProductImage::factory()->for($producto)->create([
        'position' => 1,
        'is_primary' => false,
        'path' => UploadedFile::fake()->image('foto2.jpg')->store('product-images', 'public'),
    ]);
    $foto3 = ProductImage::factory()->for($producto)->create([
        'position' => 2,
        'is_primary' => false,
        'path' => UploadedFile::fake()->image('foto3.jpg')->store('product-images', 'public'),
    ]);
    $foto4 = ProductImage::factory()->for($producto)->create([
        'position' => 3,
        'is_primary' => false,
        'path' => UploadedFile::fake()->image('foto4.jpg')->store('product-images', 'public'),
    ]);

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'fotos' => [
            UploadedFile::fake()->image('nueva1.jpg'),
            UploadedFile::fake()->image('nueva2.jpg'),
        ],
        'fotos_eliminar' => [$foto3->id, $foto4->id],
        'orden_fotos' => json_encode([
            "existing:{$foto1->id}",
            'new:0',
            "existing:{$foto2->id}",
            'new:1',
        ]),
    ])->assertSessionHasNoErrors();

    expect($producto->images()->count())->toBe(4);
});

it('elimina la única foto y deja el producto sin foto principal', function () {
    $producto = Product::factory()->create();
    $foto = ProductImage::factory()->for($producto)->create([
        'position' => 0,
        'is_primary' => true,
        'path' => UploadedFile::fake()->image('unica.jpg')->store('product-images', 'public'),
    ]);

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'fotos_eliminar' => [$foto->id],
        'orden_fotos' => '[]',
    ])->assertRedirect(route('admin.productos.index'));

    expect(ProductImage::find($foto->id))->toBeNull();
    expect(Storage::disk('public')->exists($foto->path))->toBeFalse();
    expect($producto->images()->count())->toBe(0);
});

it('persiste la descripción larga al crear un producto', function () {
    $categoria = Category::factory()->create();

    $this->actingAs($this->admin)->post(route('admin.productos.store'), [
        'category_id' => $categoria->id,
        'name' => 'Laptop Gamer',
        'description' => 'Laptop de alto rendimiento',
        'long_description' => '<p>Procesador <strong>Intel i9</strong> y 32GB de RAM.</p>',
        'price' => 1500,
        'stock' => 5,
    ])->assertRedirect(route('admin.productos.index'));

    $producto = Product::where('name', 'Laptop Gamer')->firstOrFail();
    expect($producto->long_description)->toBe('<p>Procesador <strong>Intel i9</strong> y 32GB de RAM.</p>');
});

it('persiste la descripción larga al editar un producto', function () {
    $producto = Product::factory()->create();

    $this->actingAs($this->admin)->put(route('admin.productos.update', $producto), [
        'category_id' => $producto->category_id,
        'name' => $producto->name,
        'description' => $producto->description,
        'price' => $producto->price,
        'stock' => $producto->stock,
        'long_description' => '<h2>Especificaciones</h2><ul><li>15 pulgadas</li><li>SSD 1TB</li></ul>',
    ])->assertRedirect(route('admin.productos.index'));

    expect($producto->fresh()->long_description)->toBe('<h2>Especificaciones</h2><ul><li>15 pulgadas</li><li>SSD 1TB</li></ul>');
});

it('acepta descripción larga nula y la deja vacía', function () {
    $categoria = Category::factory()->create();

    $this->actingAs($this->admin)->post(route('admin.productos.store'), [
        'category_id' => $categoria->id,
        'name' => 'Mouse Básico',
        'description' => 'Mouse ergonomico',
        'long_description' => '',
        'price' => 15,
        'stock' => 50,
    ])->assertRedirect(route('admin.productos.index'));

    $producto = Product::where('name', 'Mouse Básico')->firstOrFail();
    expect($producto->long_description)->toBeNull();
});

it('muestra el campo long_description en el modal de producto', function () {
    $categoria = Category::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.productos.index'))
        ->assertOk()
        ->assertSee('quill-editor', false)
        ->assertSee('long_description', false)
        ->assertSee('quill-long-description', false);
});

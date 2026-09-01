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
        ->assertSee('Zapatillas Urbanas');
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

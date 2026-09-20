<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create();
});

it('crea una categoría', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.categorias.store'), ['name' => 'Electrónica'])
        ->assertRedirect(route('admin.categorias.index'));

    expect(Category::where('name', 'Electrónica')->exists())->toBeTrue();
});

it('no permite crear dos categorías con el mismo nombre', function () {
    Category::factory()->create(['name' => 'Hogar']);

    $this->actingAs($this->admin)
        ->post(route('admin.categorias.store'), ['name' => 'Hogar'])
        ->assertSessionHasErrors('name');
});

it('actualiza una categoría', function () {
    $categoria = Category::factory()->create(['name' => 'Antigua']);

    $this->actingAs($this->admin)
        ->put(route('admin.categorias.update', $categoria), ['name' => 'Nueva'])
        ->assertRedirect(route('admin.categorias.index'));

    expect($categoria->fresh()->name)->toBe('Nueva');
});

it('carga y elimina la imagen opcional de una categoría', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.categorias.store'), [
            'name' => 'Con imagen',
            'imagen' => UploadedFile::fake()->image('categoria.jpg'),
        ])
        ->assertRedirect(route('admin.categorias.index'));

    $categoria = Category::where('name', 'Con imagen')->firstOrFail();
    expect($categoria->image_path)->toStartWith('category-images/');
    Storage::disk('public')->assertExists($categoria->image_path);
    $path = $categoria->image_path;

    $this->actingAs($this->admin)
        ->delete(route('admin.categorias.destroy', $categoria))
        ->assertRedirect(route('admin.categorias.index'));

    Storage::disk('public')->assertMissing($path);
});

it('busca categorías sin distinguir mayúsculas y pagina en diez registros', function () {
    Category::factory()->create(['name' => 'Electrónica']);
    Category::factory()->create(['name' => 'Hogar']);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.categorias.index', ['buscar' => 'ELECTRÓN']))
        ->assertOk()
        ->assertSee('Electrónica')
        ->assertDontSee('Hogar')
        ->assertSee('sash-table-card', false)
        ->assertSee('sash-table', false);

    expect($response->viewData('categorias')->perPage())->toBe(10);
});

it('muestra el mensaje requerido cuando no hay categorías', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.categorias.index', ['buscar' => 'inexistente']))
        ->assertOk()
        ->assertSee('Sin resultados disponibles');
});

it('elimina una categoría', function () {
    $categoria = Category::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.categorias.destroy', $categoria))
        ->assertRedirect(route('admin.categorias.index'));

    expect(Category::find($categoria->id))->toBeNull();
});

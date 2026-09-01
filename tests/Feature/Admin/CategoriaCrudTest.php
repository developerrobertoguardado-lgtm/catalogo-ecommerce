<?php

use App\Models\Category;
use App\Models\User;

beforeEach(function () {
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

it('elimina una categoría', function () {
    $categoria = Category::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.categorias.destroy', $categoria))
        ->assertRedirect(route('admin.categorias.index'));

    expect(Category::find($categoria->id))->toBeNull();
});

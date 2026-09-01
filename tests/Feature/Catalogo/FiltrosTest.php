<?php

use App\Models\Category;
use App\Models\Product;

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

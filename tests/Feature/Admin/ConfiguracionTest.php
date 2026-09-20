<?php

use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->admin = User::factory()->create();
});

it('permite cargar un logo y lo muestra en la configuración', function () {
    $configuracion = StoreSetting::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.configuracion.update'), [
            'store_name' => $configuracion->store_name,
            'whatsapp_number' => $configuracion->whatsapp_number,
            'currency' => $configuracion->currency,
            'logo' => UploadedFile::fake()->image('logo.webp'),
        ])
        ->assertRedirect(route('admin.configuracion.edit'));

    $configuracion->refresh();
    expect($configuracion->logo_path)->toStartWith('branding/');
    Storage::disk('public')->assertExists($configuracion->logo_path);

    $this->actingAs($this->admin)
        ->get(route('admin.configuracion.edit'))
        ->assertSee('store-logo-preview', false)
        ->assertSee('logo-remove', false);
});

it('valida el formato y tamaño máximo del logo', function () {
    $configuracion = StoreSetting::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.configuracion.update'), [
            'store_name' => $configuracion->store_name,
            'whatsapp_number' => $configuracion->whatsapp_number,
            'currency' => $configuracion->currency,
            'logo' => UploadedFile::fake()->create('logo.gif', 2100, 'image/gif'),
        ])
        ->assertSessionHasErrors('logo');

    $this->actingAs($this->admin)
        ->get(route('admin.configuracion.edit'))
        ->assertSee('logo-dropzone', false)
        ->assertSee('data-lock-on-submit', false);
});

it('permite eliminar el logo y vuelve al nombre de la tienda', function () {
    $configuracion = StoreSetting::factory()->create([
        'logo_path' => UploadedFile::fake()->image('actual.png')->store('branding', 'public'),
    ]);
    $path = $configuracion->logo_path;

    $this->actingAs($this->admin)
        ->put(route('admin.configuracion.update'), [
            'store_name' => $configuracion->store_name,
            'whatsapp_number' => $configuracion->whatsapp_number,
            'currency' => $configuracion->currency,
            'eliminar_logo' => true,
        ])
        ->assertRedirect(route('admin.configuracion.edit'));

    expect($configuracion->fresh()->logo_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('persiste la paleta de colores de empresa junto con la configuración', function () {
    $configuracion = StoreSetting::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.configuracion.update'), [
            'store_name' => $configuracion->store_name,
            'whatsapp_number' => $configuracion->whatsapp_number,
            'currency' => $configuracion->currency,
            'primary_color' => '#7a2e9d',
            'secondary_color' => '#22c1c3',
            'button_color' => '#e63946',
            'menu_color' => '#1d3557',
            'background_color' => '#f1faee',
            'text_color' => '#111111',
        ])
        ->assertRedirect(route('admin.configuracion.edit'));

    $configuracion->refresh();
    expect($configuracion->primary_color)->toBe('#7a2e9d')
        ->and($configuracion->secondary_color)->toBe('#22c1c3')
        ->and($configuracion->button_color)->toBe('#e63946')
        ->and($configuracion->menu_color)->toBe('#1d3557')
        ->and($configuracion->background_color)->toBe('#f1faee')
        ->and($configuracion->text_color)->toBe('#111111');
});

it('valida el formato hexadecimal de los colores de la paleta', function () {
    $configuracion = StoreSetting::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.configuracion.update'), [
            'store_name' => $configuracion->store_name,
            'whatsapp_number' => $configuracion->whatsapp_number,
            'currency' => $configuracion->currency,
            'primary_color' => 'rojo',
        ])
        ->assertSessionHasErrors('primary_color');
});

it('muestra el tab Aspecto visual con los pickers de color en la configuración', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.configuracion.edit'))
        ->assertSee('Aspecto visual', false)
        ->assertSee('tab-visual', false)
        ->assertSee('name="primary_color"', false)
        ->assertSee('name="button_color"', false)
        ->assertSee('name="menu_color"', false);
});

it('aplica los colores configurados como variables CSS en el catálogo y el admin', function () {
    $configuracion = StoreSetting::factory()->create([
        'primary_color' => '#7a2e9d',
        'button_color' => '#e63946',
        'menu_color' => '#1d3557',
    ]);

    $this->get(route('catalogo.index'))
        ->assertSee('--brand-primary: #7a2e9d;', false)
        ->assertSee('--brand-button: #e63946;', false)
        ->assertSee('--brand-menu: #1d3557;', false);

    $this->actingAs($this->admin)
        ->get(route('admin.configuracion.edit'))
        ->assertSee('--brand-primary: #7a2e9d;', false);
});

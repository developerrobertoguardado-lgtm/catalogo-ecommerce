<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreSettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Catalogo\CatalogoController;
use App\Http\Controllers\Catalogo\PedidoController;
use Illuminate\Support\Facades\Route;

// Catálogo público
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/productos/{producto:slug}', [CatalogoController::class, 'show'])->name('catalogo.show');
Route::get('/categorias/{categoria:slug}', [CatalogoController::class, 'byCategory'])->name('catalogo.categoria');
Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

// Autenticación de administrador
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
});
Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Panel administrativo
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/productos')->name('dashboard');

    Route::resource('productos', AdminProductController::class)->parameters([
        'productos' => 'producto',
    ])->except(['show', 'create', 'edit']);

    Route::resource('categorias', AdminCategoryController::class)->parameters([
        'categorias' => 'categoria',
    ])->except(['show', 'create', 'edit']);

    Route::get('pedidos', [AdminOrderController::class, 'index'])->name('pedidos.index');
    Route::get('pedidos/{pedido}', [AdminOrderController::class, 'show'])->name('pedidos.show');

    Route::get('configuracion', [StoreSettingController::class, 'edit'])->name('configuracion.edit');
    Route::put('configuracion', [StoreSettingController::class, 'update'])->name('configuracion.update');
});

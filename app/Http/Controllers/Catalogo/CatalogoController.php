<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request): View
    {
        $productos = $this->filteredProducts($request)->paginate(12)->withQueryString();
        $categorias = Category::orderBy('name')->get();

        return view('catalogo.index', compact('productos', 'categorias'));
    }

    public function byCategory(Request $request, Category $categoria): View
    {
        $productos = $this->filteredProducts($request)
            ->where('category_id', $categoria->id)
            ->paginate(12)
            ->withQueryString();
        $categorias = Category::orderBy('name')->get();

        return view('catalogo.index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'categoriaActual' => $categoria,
        ]);
    }

    public function show(Product $producto): View
    {
        $producto->load(['images', 'category']);

        return view('catalogo.show', compact('producto'));
    }

    private function filteredProducts(Request $request): Builder
    {
        return Product::query()
            ->with(['category', 'primaryImage'])
            ->when(
                $request->filled('nombre'),
                fn (Builder $query) => $query->where('name', 'ilike', '%'.$request->string('nombre').'%')
            )
            ->when(
                $request->filled('categoria_id'),
                fn (Builder $query) => $query->where('category_id', $request->integer('categoria_id'))
            )
            ->when(
                $request->filled('precio_min'),
                fn (Builder $query) => $query->where('price', '>=', $request->input('precio_min'))
            )
            ->when(
                $request->filled('precio_max'),
                fn (Builder $query) => $query->where('price', '<=', $request->input('precio_max'))
            )
            ->latest();
    }
}

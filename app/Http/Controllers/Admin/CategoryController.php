<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->input('buscar', ''));
        $categorias = Category::withCount('products')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($buscar).'%']);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categorias.index', compact('categorias', 'buscar'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $datos = [
            'name' => $request->string('name'),
            'slug' => Str::slug($request->string('name')),
        ];
        if ($request->hasFile('imagen')) {
            $datos['image_path'] = $request->file('imagen')->store('category-images', 'public');
        }
        Category::create($datos);

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría creada.');
    }

    public function update(UpdateCategoryRequest $request, Category $categoria): RedirectResponse
    {
        $datos = [
            'name' => $request->string('name'),
            'slug' => Str::slug($request->string('name')),
        ];
        if ($request->boolean('eliminar_imagen') || $request->hasFile('imagen')) {
            if ($categoria->image_path) Storage::disk('public')->delete($categoria->image_path);
            $datos['image_path'] = $request->hasFile('imagen')
                ? $request->file('imagen')->store('category-images', 'public')
                : null;
        }
        $categoria->update($datos);

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy(Category $categoria): RedirectResponse
    {
        if ($categoria->image_path) Storage::disk('public')->delete($categoria->image_path);
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría eliminada.');
    }
}

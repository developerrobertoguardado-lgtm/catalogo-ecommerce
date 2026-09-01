<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categorias = Category::withCount('products')->orderBy('name')->paginate(15);

        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->string('name'),
            'slug' => Str::slug($request->string('name')),
        ]);

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría creada.');
    }

    public function update(UpdateCategoryRequest $request, Category $categoria): RedirectResponse
    {
        $categoria->update([
            'name' => $request->string('name'),
            'slug' => Str::slug($request->string('name')),
        ]);

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy(Category $categoria): RedirectResponse
    {
        $categoria->delete();

        return redirect()->route('admin.categorias.index')->with('status', 'Categoría eliminada.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(): View
    {
        $productos = Product::with(['category', 'primaryImage', 'images'])->latest()->paginate(15);
        $categorias = Category::orderBy('name')->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $producto = Product::create([
            ...$request->safe()->except(['fotos', 'orden_fotos']),
            'slug' => Str::slug($request->string('name')).'-'.Str::random(6),
        ]);

        $this->sincronizarFotos($producto, $request->file('fotos', []), $request->input('orden_fotos'));

        return redirect()->route('admin.productos.index')->with('status', 'Producto creado.');
    }

    public function update(UpdateProductRequest $request, Product $producto): RedirectResponse
    {
        $producto->update($request->safe()->except(['fotos', 'orden_fotos']));

        $this->sincronizarFotos($producto, $request->file('fotos', []), $request->input('orden_fotos'));

        return redirect()->route('admin.productos.index')->with('status', 'Producto actualizado.');
    }

    public function destroy(Product $producto): RedirectResponse
    {
        foreach ($producto->images as $imagen) {
            Storage::disk('public')->delete($imagen->path);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')->with('status', 'Producto eliminado.');
    }

    /**
     * Aplica el orden final de fotos (existentes reordenadas + nuevas
     * intercaladas) que arma el dropzone en el cliente. Cada token de
     * `$orden` es "existing:<id>" o "new:<indice>" (índice dentro de
     * `$fotos`, en el mismo orden en que el cliente reconstruyó el input de
     * archivos). La posición 0 siempre queda como foto principal.
     *
     * @param  array<int, UploadedFile>  $fotos
     */
    private function sincronizarFotos(Product $producto, array $fotos, ?string $ordenJson): void
    {
        $orden = json_decode((string) $ordenJson, true);

        if (! is_array($orden) || empty($orden)) {
            $this->agregarFotosSinOrden($producto, $fotos);

            return;
        }

        foreach ($orden as $position => $token) {
            if (str_starts_with($token, 'existing:')) {
                $id = (int) Str::after($token, 'existing:');

                $producto->images()->whereKey($id)->update([
                    'position' => $position,
                    'is_primary' => $position === 0,
                ]);

                continue;
            }

            if (str_starts_with($token, 'new:')) {
                $index = (int) Str::after($token, 'new:');
                $foto = $fotos[$index] ?? null;

                if ($foto) {
                    $producto->images()->create([
                        'path' => $foto->store('product-images', 'public'),
                        'position' => $position,
                        'is_primary' => $position === 0,
                    ]);
                }
            }
        }
    }

    /**
     * Fallback para envíos sin JS (o sin drag & drop): agrega las fotos
     * nuevas al final, en el orden recibido.
     *
     * @param  array<int, UploadedFile>  $fotos
     */
    private function agregarFotosSinOrden(Product $producto, array $fotos): void
    {
        if (empty($fotos)) {
            return;
        }

        $existentes = $producto->images()->count();

        foreach ($fotos as $index => $foto) {
            $producto->images()->create([
                'path' => $foto->store('product-images', 'public'),
                'position' => $existentes + $index,
                'is_primary' => $existentes === 0 && $index === 0,
            ]);
        }
    }
}

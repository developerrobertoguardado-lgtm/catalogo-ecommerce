<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->input('buscar', ''));
        $productos = Product::with(['category', 'primaryImage', 'images'])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $term = '%'.mb_strtolower($buscar).'%';

                $query->where(function ($query) use ($term) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(description) LIKE ?', [$term]);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $categorias = Category::orderBy('name')->get();

        return view('admin.productos.index', compact('productos', 'categorias', 'buscar'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $producto = Product::create([
            ...$request->safe()->except(['fotos', 'orden_fotos']),
            'slug' => Str::slug($request->string('name')).'-'.Str::random(6),
        ]);

        $this->sincronizarFotos($producto, $request->file('fotos', []), $request->input('orden_fotos'), $request->input('fotos_eliminar', []));

        return redirect()->route('admin.productos.index')->with('status', 'Producto creado.');
    }

    public function update(UpdateProductRequest $request, Product $producto): RedirectResponse
    {
        $producto->update($request->safe()->except(['fotos', 'orden_fotos']));

        $this->sincronizarFotos($producto, $request->file('fotos', []), $request->input('orden_fotos'), $request->input('fotos_eliminar', []));

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
     * archivos). Las imágenes indicadas en `$eliminar` se borran de la base y
     * de disco antes de aplicar el orden. La posición 0 siempre queda como
     * foto principal.
     *
     * @param  array<int, UploadedFile>  $fotos
     * @param  array<int, int|string>    $eliminar
     */
    private function sincronizarFotos(Product $producto, array $fotos, ?string $ordenJson, array $eliminar): void
    {
        $this->eliminarFotos($producto, $eliminar);

        $orden = json_decode((string) $ordenJson, true);

        if (! is_array($orden) || empty($orden)) {
            $this->agregarFotosSinOrden($producto, $fotos);

            $this->recompactarPosiciones($producto);

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

        $this->recompactarPosiciones($producto);
    }

    /**
     * Elimina de la base y del disco las imágenes cuyos IDs vengan en
     * `$eliminar`.
     *
     * @param  array<int, int|string>  $eliminar
     */
    private function eliminarFotos(Product $producto, array $eliminar): void
    {
        $ids = collect($eliminar)->map(fn ($id) => (int) $id)->filter()->unique()->all();

        if (empty($ids)) {
            return;
        }

        $imagenes = $producto->images()->whereKey($ids)->get();

        foreach ($imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->path);
            $imagen->delete();
        }
    }

    /**
     * Recompacta las posiciones (0..n-1) de las imágenes que quedan y asegura
     * que exactamente una (la de posición 0) sea la principal.
     */
    private function recompactarPosiciones(Product $producto): void
    {
        $imagenes = $producto->images()->orderBy('position')->get();

        foreach ($imagenes as $position => $imagen) {
            $producto->images()->where('id', $imagen->id)->update([
                'position' => $position,
                'is_primary' => $position === 0,
            ]);
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

    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $term = '%'.mb_strtolower($q).'%';
        $productos = Product::with('primaryImage')
            ->whereRaw('LOWER(name) LIKE ?', [$term])
            ->limit(20)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'stock' => $p->stock,
                    'primary_image_url' => $p->primaryImage
                        ? \Illuminate\Support\Facades\Storage::url($p->primaryImage->path)
                        : null,
                ];
            });

        return response()->json($productos);
    }
}

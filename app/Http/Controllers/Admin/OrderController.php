<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): View
    {
        $pedidos = Order::with('items.product')->latest()->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Order $pedido): View
    {
        $pedido->load('items.product');

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function edit(Order $pedido): View
    {
        $pedido->load('items.product.primaryImage');

        return view('admin.pedidos._modal', compact('pedido'));
    }

    public function update(UpdateOrderRequest $request, Order $pedido): RedirectResponse
    {
        if ($pedido->isDelivered() && $request->status !== 'ENTREGADO') {
            // Permitir reabrir: cambiar estado de ENTREGADO a otro
        } elseif ($pedido->isDelivered()) {
            return back()->with('error', 'No se puede editar un pedido en estado ENTREGADO. Cambie el estado primero.');
        }

        DB::transaction(function () use ($request, $pedido) {
            $oldStatus = $pedido->status;

            // Actualizar datos del pedido
            $pedido->update($request->safe()->except('items'));

            // Sincronizar items
            $incomingIds = collect($request->items)->pluck('id')->filter()->toArray();
            
            // Eliminar items que no vienen en el request
            $pedido->items()->whereNotIn('id', $incomingIds)->delete();

            // Pre-cargar productos nuevos para evitar N+1
            $newProductIds = collect($request->items)
                ->filter(fn ($item) => !isset($item['id']))
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->all();
            $productsMap = Product::whereIn('id', $newProductIds)->get()->keyBy('id');

            // Crear o actualizar items
            foreach ($request->items as $itemData) {
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];

                if (isset($itemData['id'])) {
                    OrderItem::where('id', $itemData['id'])->update([
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                } else {
                    $product = $productsMap->get($itemData['product_id']);
                    OrderItem::create([
                        'order_id' => $pedido->id,
                        'product_id' => $itemData['product_id'],
                        'product_name' => $product?->name ?? 'Producto eliminado',
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // Recalcular total
            $pedido->recalculateTotal();

            // Log de cambio de estado
            if ($oldStatus !== $pedido->status) {
                OrderStatusLog::create([
                    'order_id' => $pedido->id,
                    'from_status' => $oldStatus,
                    'to_status' => $pedido->status,
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('admin.pedidos.index')->with('status', 'Pedido actualizado correctamente.');
    }

    public function statusLog(Order $pedido): View
    {
        $logs = $pedido->statusLogs()->with('user')->get();

        return view('admin.pedidos._status_log', compact('pedido', 'logs'));
    }
}
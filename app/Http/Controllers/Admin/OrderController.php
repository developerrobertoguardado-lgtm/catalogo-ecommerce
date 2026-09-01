<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $pedidos = Order::with('items')->latest()->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Order $pedido): View
    {
        $pedido->load('items.product');

        return view('admin.pedidos.show', compact('pedido'));
    }
}

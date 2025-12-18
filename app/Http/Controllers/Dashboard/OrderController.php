<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrderService;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
        $this->middleware('can:orders-index')->only(['index', 'orderCash']);
        $this->middleware('can:orders-show')->only(['show', 'printLabel']);
        $this->middleware('can:orders-update')->only(['edit', 'update']);
        $this->middleware('can:orders-delete')->only('destroy');
    }
     public function index()
    {
        $data = $this->orderService->index();
        return view('dashboard.pages.order.index', compact('data'));
    }
    public function orderCash()
    {
        $data = $this->orderService->orderCash();
        return view('dashboard.pages.order.order_cash', compact('data'));
    }

    public function show(string $id)
    {
        $order = $this->orderService->show($id);
        return view('dashboard.pages.order.show', compact('order'));
    }

    public function edit(string $id)
    {
        $order = $this->orderService->show($id);
        return view('dashboard.pages.order.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);
        $this->orderService->update($data, $id);
        return redirect()->route('dashboard.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->orderService->destroy($id);
        return redirect()->route('dashboard.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function printLabel(string $id)
    {
        $order = $this->orderService->show($id);
        return view('dashboard.pages.order.print-label', compact('order'));
    }
}

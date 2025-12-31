<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrderService;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
           new Middleware('can:orders-index', only: ['index', 'orderCash']),
           new Middleware('can:orders-show', only: ['show', 'printLabel']),
           new Middleware('can:orders-update', only: ['edit', 'update']),
           new Middleware('can:orders-delete', only: ['destroy']),
        ];
    }

    public function __construct(public OrderService $orderService)
    {
    }
     public function index()
    {
        $data = $this->orderService->index();
        $stats = $this->orderService->getStats();
        
        // Get orders by status for the list
        $baseQuery = \App\Models\Order::query();
        if (!auth()->user()->hasRole('admin')) {
            $baseQuery->whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }
        $ordersByStatus = (clone $baseQuery)
            ->select('status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('dashboard.pages.order.index', compact('data', 'stats', 'ordersByStatus'));
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
            'status' => 'required|in:pending,pending_payment,processing,shipped,delivered,completed,cancelled',
        ]);
        
        try {
            $this->orderService->update($data, $id);
            return redirect()->route('dashboard.orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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

    /**
     * Generate and download invoice for an order.
     */
    public function invoice(string $id)
    {
        $order = $this->orderService->show($id);
        $invoiceService = app(\App\Services\InvoiceService::class);
        return $invoiceService->generateHtmlInvoice($order);
    }
}

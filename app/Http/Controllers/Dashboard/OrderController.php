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
           new Middleware('can:orders-update', only: ['edit', 'update', 'accept']),
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
        
        // Get vendors for filter (admin only)
        $vendors = auth()->user()->hasRole('admin') 
            ? \App\Models\User::where('type', 'vendor')->orWhereHas('roles', function($q) {
                $q->where('name', 'vendor');
            })->get()
            : collect();
        
        return view('dashboard.pages.order.index', compact('data', 'stats', 'ordersByStatus', 'vendors'));
    }
    public function orderCash()
    {
        $data = $this->orderService->orderCash();
        return view('dashboard.pages.order.order_cash', compact('data'));
    }

    public function show(string $id)
    {
        $order = $this->orderService->show($id);
        
        // Get allowed status transitions for this order
        $stateMachine = app(\App\Services\OrderStatusStateMachine::class);
        $allowedTransitions = $stateMachine->getAllowedTransitions($order->status);
        
        // Define status order for logical display
        $statusOrder = ['pending', 'pending_payment', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];
        
        // Create status options with labels
        $statusOptions = [];
        
        // Add current status first (to show it's selected)
        $statusOptions[$order->status] = $stateMachine->getStatusLabel($order->status);
        
        // Add allowed transitions in logical order
        foreach ($statusOrder as $status) {
            if (in_array($status, $allowedTransitions) && $status !== $order->status) {
                $statusOptions[$status] = $stateMachine->getStatusLabel($status);
            }
        }
        
        return view('dashboard.pages.order.show', compact('order', 'statusOptions'));
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

    /**
     * Accept an order - moves status from pending to processing.
     * This means the vendor/admin accepts the order and starts processing it.
     */
    public function accept(Request $request, string $id)
    {
        try {
            $order = $this->orderService->show($id);
            
            // Check if order can be accepted (must be pending)
            if ($order->status !== 'pending') {
                $currentStatus = ucfirst($order->status);
                return redirect()->back()->with('error', "Cannot accept order. Order is already {$currentStatus}. Only pending orders can be accepted.");
            }
            
            // Check if order is already completed or cancelled (safety check)
            if (in_array($order->status, ['completed', 'cancelled'])) {
                return redirect()->back()->with('error', 'Cannot accept an order that is already completed or cancelled.');
            }
            
            // Update order status to processing (accepting means starting to process)
            $this->orderService->update(['status' => 'processing'], $id);
            
            return redirect()->route('dashboard.orders.index')->with('success', 'Order accepted successfully. Status changed to processing.');
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

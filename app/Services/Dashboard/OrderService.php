<?php
namespace App\Services\Dashboard;

use App\Models\Order;

class OrderService
{
    public function __construct(public Order $model)
    {}
    public function index()
    {
        $query = $this->model->with(['items.product.productImages', 'items.product.user', 'returns']);

        // Check ownership if not admin
        if (!auth()->user()->hasRole('admin')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        // Apply Status Filter
        if (request()->has('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        // Apply Payment Method Filter
        if (request()->has('payment_method') && request('payment_method') !== 'all') {
            if (request('payment_method') === 'card') {
                // Card orders are non-cash orders
                $query->where('payment_method', '!=', 'cash');
            } else {
                $query->where('payment_method', request('payment_method'));
            }
        }

        // Apply Payment Status Filter
        if (request()->has('payment_status') && request('payment_status') !== 'all') {
            $query->where('payment_status', request('payment_status'));
        }

        // Apply Return Orders Filter
        if (request()->has('has_returns') && request('has_returns') == '1') {
            $query->whereHas('returns');
        }

        // Apply Date Range Filter
        if (request()->has('date_from')) {
            $query->whereDate('created_at', '>=', request('date_from'));
        }
        if (request()->has('date_to')) {
            $query->whereDate('created_at', '<=', request('date_to'));
        }

        // Apply Vendor Filter (admin only)
        if (auth()->user()->hasRole('admin') && request()->has('vendor_id') && request('vendor_id')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('user_id', request('vendor_id'));
            });
        }

        // Apply Search Filter
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    /**
     * Get order statistics for dashboard
     */
    public function getStats()
    {
        $baseQuery = $this->model->query();

        // Check ownership if not admin
        if (!auth()->user()->hasRole('admin')) {
            $baseQuery->whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return [
            'total_orders' => (clone $baseQuery)->count(),
            'pending_orders' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed_orders' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled_orders' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'card_orders' => (clone $baseQuery)->where('payment_method', '!=', 'cash')->count(),
            'cash_orders' => (clone $baseQuery)->where('payment_method', 'cash')->count(),
            'return_orders' => (clone $baseQuery)->whereHas('returns')->count(),
        ];
    }

    public function orderCash()
    {
        if (auth()->user()->hasRole('admin')) {
          return $this->model
            ->with(['items.product'])
            ->where('payment_method', 'cash')->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        return $this->model
            ->whereHas('items.product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['items.product'])
            ->where('payment_method', 'cash')->orderBy('created_at', 'desc')
            ->paginate(10);

    }

    public function show(string $id)
    {
        // return $this->model->findOrFail($id);
        return $this->model->with('items.product.productImages', 'items.product.category')->findOrFail($id);

    }

    public function update($data, string $id)
    {
        $order = $this->show($id);
        $shouldCreatePayment = false;

        // Use state machine to validate and transition status
        if (isset($data['status'])) {
            $stateMachine = app(\App\Services\OrderStatusStateMachine::class);
            
            try {
                $stateMachine->transition($order, $data['status']);
                
                // Refresh order to get updated status
                $order->refresh();
                
                if ($data['status'] == 'completed') {
                    $shouldCreatePayment = true;
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        } else {
            // Update other fields without status change
            $order->update($data);
        }

        // Create a Payment record (one per order) when marking completed/paid
        if ($shouldCreatePayment) {
            $exists = \App\Models\Payment::where('paymentable_type', \App\Models\Order::class)
                ->where('paymentable_id', $order->id)
                ->exists();

            if (! $exists) {
                $currencySvc = new \App\Services\CurrencyService();
                $baseCurrency = config('app.currency', 'EGP');
                $user = $order->user;
                $userCurrency = $user->currency ?? $currencySvc->currencyForCountry($user->country ?? null);
                $rate = $currencySvc->getRate($baseCurrency, $userCurrency);
                $convertedAmount = $currencySvc->convertFromTo($order->total, $baseCurrency, $userCurrency);

                $payment = \App\Models\Payment::create([
                    'paymentable_type' => \App\Models\Order::class,
                    'paymentable_id' => $order->id,
                    'type' => 'order',
                    'amount' => $convertedAmount,
                    'currency' => $userCurrency,
                    'status' => 'paid',
                    'method' => $order->payment_method,
                    'reference' => $order->order_number,
                    'original_amount' => $order->total,
                    'original_currency' => $baseCurrency,
                    'exchange_rate' => $rate,
                    'exchanged_at' => now(),
                ]);

                // Link vendor payments for this order to the payment record and mark paid
                \App\Models\VendorPayment::where('order_id', $order->id)
                    ->update(['payment_id' => $payment->id, 'status' => 'paid', 'paid_at' => now()]);
            }
        }

        return $order;
    }

    public function destroy(string $id)
    {
        $order = $this->show($id);
        return $order->delete();
    }
}

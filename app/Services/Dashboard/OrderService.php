<?php
namespace App\Services\Dashboard;

use App\Models\Order;

class OrderService
{
    public function __construct(public Order $model)
    {}
    public function index()
    {
        $query = $this->model->with(['items.product']);

        // Check ownership if not admin
        if (!auth()->user()->hasRole('admin')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        // Apply Status Filter
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        // Apply Payment Method Filter
        if (request()->has('payment_method')) {
            $query->where('payment_method', request('payment_method'));
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
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

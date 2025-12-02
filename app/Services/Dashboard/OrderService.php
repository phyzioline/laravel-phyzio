<?php
namespace App\Services\Dashboard;

use App\Models\Order;

class OrderService
{
    public function __construct(public Order $model)
    {}
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
          return $this->model
            ->with(['items.product'])->where('payment_method', 'card')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        return $this->model
            ->whereHas('items.product', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['items.product'])->where('payment_method', 'card')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        if($data['status'] == 'completed') {
           $order->update(['payment_status' => 'paid']);
        }elseif($data['status'] == 'cancelled') {
            $order->update(['payment_status' => 'faild']);
        }
        return $order->update($data);
    }

    public function destroy(string $id)
    {
        $order = $this->show($id);
        return $order->delete();
    }
}

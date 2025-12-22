@extends('dashboard.layouts.app')

@section('content')
<main class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Order Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Order #{{ $order->id }}</h5>
                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                            {{ucfirst($order->status) }}
                        </span>
                    </div>

                    <h6 class="mb-2">Your Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th>Commission</th>
                                    <th>Your Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->product_name_en ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                    <td class="text-danger">-${{ number_format($item->commission_amount, 2) }}</td>
                                    <td><strong class="text-success">${{ number_format($item->vendor_earnings, 2) }}</strong></td>
                                </tr>
                                @endforeach
                                <tr class="table-light">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->items->sum('total'), 2) }}</strong></td>
                                    <td class="text-danger"><strong>-${{ number_format($order->items->sum('commission_amount'), 2) }}</strong></td>
                                    <td><strong class="text-success">${{ number_format($order->items->sum('vendor_earnings'), 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Shipment Info --}}
                    @if($order->shipments->count() > 0)
                    <h6 class="mt-4 mb-2">Shipments</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Courier</th>
                                    <th>Tracking</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->shipments as $shipment)
                                <tr>
                                    <td>#{{ $shipment->id }}</td>
                                    <td><span class="badge bg-{{ $shipment->shipment_status == 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($shipment->shipment_status) }}</span></td>
                                    <td>{{ $shipment->courier ?? 'Not set' }}</td>
                                    <td>
                                        @if($shipment->tracking_number)
                                            <code>{{ $shipment->tracking_number }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('vendor.shipments.show', $shipment->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-truck me-1"></i>Manage
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Customer Information</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
                    <hr>
                    <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    <p class="mb-1"><strong>Payment Status:</strong> 
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </p>
                    <hr>
                    <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

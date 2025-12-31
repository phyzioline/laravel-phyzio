@extends('dashboard.layouts.app')
@section('title', __('Order #') . $order->id)

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Orders</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.orders.index') }}">Manage Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Details #{{ $order->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('dashboard.orders.print-label', $order->id) }}" target="_blank" class="btn btn-primary"><i class="bi bi-printer"></i> Print Packing Slip</a>
                    @if(auth()->user()->type === 'vendor' && $order->status !== 'completed')
                        <a href="{{ route('dashboard.orders.ship.create', $order->id) }}" class="btn btn-success ms-2"><i class="bi bi-truck"></i> Ship Items</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 fw-bold">Order Items</h6>
                            <div class="ms-auto">
                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product Details</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="product-box border">
                                                        @if ($item->product && $item->product->productImages->first())
                                                            <img src="{{ asset($item->product->productImages->first()->image) }}" alt="" style="width: 50px; height: 50px; object-fit: contain;">
                                                        @else
                                                            <img src="{{ asset('default/default.png') }}" class="img-fluid rounded" alt="" width="50">
                                                        @endif
                                                    </div>
                                                    <div class="product-info">
                                                        <h6 class="product-name mb-1">{{ $item->product->product_name_en ?? 'Unknown Product' }}</h6>
                                                        <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->price, 2) }} EGP</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->total, 2) }} EGP</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                        <td class="text-end">{{ number_format($order->total, 2) }} EGP</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Shipping</td>
                                        <td class="text-end">0.00 EGP</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                        <td class="text-end text-primary h5 mb-0">{{ number_format($order->total, 2) }} EGP</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Customer Details -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0 fw-bold">Customer Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="customer-info">
                                <h6 class="mb-1 fw-bold">{{ $order->name }}</h6>
                                <p class="mb-0 text-muted small">{{ $order->email ?? $order->user->email ?? 'No Email' }}</p>
                                <p class="mb-0 text-muted small">{{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0 fw-bold">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        <address class="mb-0 section-address">
                            {{ $order->address }}<br>
                            <!-- City, Country placeholders if not in DB -->
                        </address>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h6 class="mb-0 fw-bold">Payment Info</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        <p class="mb-0"><strong>Status:</strong> <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                    </div>
                </div>

                @can('orders-update')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dashboard.orders.update', $order->id) }}" method="POST" id="status-update-form">
                            @csrf
                            @method('PUT')
                            <label class="form-label">Update Status</label>
                            <select name="status" id="status-select" class="form-select mb-3" required>
                                @if(isset($statusOptions) && count($statusOptions) > 0)
                                    @foreach($statusOptions as $statusValue => $statusLabel)
                                        <option value="{{ $statusValue }}" {{ $order->status == $statusValue ? 'selected' : '' }}>
                                            {{ $statusLabel }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Fallback if statusOptions not available --}}
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                @endif
                            </select>
                            <button type="submit" class="btn btn-primary w-100" id="update-status-btn">
                                <i class="fas fa-save"></i> Update Order Status
                            </button>
                        </form>
                        <div class="mt-2">
                            <small class="text-muted">
                                Current Status: <strong>{{ ucfirst($order->status) }}</strong>
                                @if(isset($statusOptions) && count($statusOptions) > 0)
                                    | Available transitions: {{ count($statusOptions) - 1 }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('status-update-form');
    const statusSelect = document.getElementById('status-select');
    const updateBtn = document.getElementById('update-status-btn');
    
    if (form && statusSelect && updateBtn) {
        form.addEventListener('submit', function(e) {
            const selectedStatus = statusSelect.value;
            const currentStatus = '{{ $order->status }}';
            
            if (selectedStatus === currentStatus) {
                e.preventDefault();
                alert('Please select a different status. Current status is already: ' + currentStatus);
                return false;
            }
            
            // Show loading state
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            
            // Form will submit normally
        });
    }
});
</script>
@endpush
@endsection

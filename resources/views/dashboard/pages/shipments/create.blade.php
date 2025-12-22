@extends('dashboard.layouts.app')
@section('title', 'Ship Items')

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Orders</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ship items for Order #{{ $order->order_number ?? $order->id }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Create Shipment</h5>
                    </div>
                    <div class="card-body">
                         @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                         @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('dashboard.orders.ship.store', $order->id) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Select Items to Ship</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="40"><input type="checkbox" id="checkAll" checked></th>
                                                <th>Product</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vendorItems as $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="item_ids[]" value="{{ $item->id }}" class="item-check" checked>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $item->product->image }}" width="40" class="rounded me-2">
                                                            <div>{{ $item->product->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Courier / Shipping Provider <span class="text-danger">*</span></label>
                                    <input type="text" name="courier" class="form-control" placeholder="e.g. DHL, FedEx, Aramex" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tracking Number <span class="text-danger">*</span></label>
                                    <input type="text" name="tracking_number" class="form-control" placeholder="TRACK123456" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Any shipping instructions or notes..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-truck"></i> Create Shipment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection

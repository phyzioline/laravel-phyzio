@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Shipment Details') }} #{{ $shipment->id }}</h5>
                <span class="badge bg-primary">{{ ucfirst($shipment->status) }}</span>
            </div>
            <div class="card-body">
                <!-- Tracking Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ __('Tracking Information') }}</h6>
                        <dl class="row">
                            <dt class="col-sm-4">{{ __('Courier') }}</dt>
                            <dd class="col-sm-8">{{ $shipment->courier ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">{{ __('Tracking #') }}</dt>
                            <dd class="col-sm-8">
                                @if($shipment->tracking_number)
                                    <code>{{ $shipment->tracking_number }}</code>
                                @else
                                    <span class="text-muted">{{ __('Not provided') }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">{{ __('Shipped Date') }}</dt>
                            <dd class="col-sm-8">
                                {{ $shipment->shipped_at ? \Carbon\Carbon::parse($shipment->shipped_at)->format('M d, Y H:i') : 'N/A' }}
                            </dd>
                            
                            <dt class="col-sm-4">{{ __('Delivery Date') }}</dt>
                            <dd class="col-sm-8">
                                {{ $shipment->delivered_at ? \Carbon\Carbon::parse($shipment->delivered_at)->format('M d, Y H:i') : 'N/A' }}
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('Vendor Details') }}</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="ms-0">
                                <h6 class="mb-0">{{ $shipment->vendor->name ?? 'Unknown Vendor' }}</h6>
                                <small class="text-muted">{{ $shipment->vendor->email ?? '' }}</small>
                                <br>
                                <small>{{ $shipment->vendor->phone ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Order Items -->
                <h6>{{ __('Items in Shipment') }}</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Unit Price') }}</th>
                                <th>{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipment->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" width="40" height="40" class="rounded" alt="">
                                        @endif
                                        <span>{{ $item->product->name ?? 'Product Unavailable' }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">{{ __('Total Amount') }}</td>
                                <td class="fw-bold">{{ number_format($shipment->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Customer Info -->
                <div class="mt-4">
                    <h6>{{ __('Shipping Address') }}</h6>
                    <p class="mb-0">
                        <strong>{{ $shipment->order->name ?? 'Guest' }}</strong><br>
                        {{ $shipment->order->address ?? '' }}<br>
                        {{ $shipment->order->city ?? '' }}, {{ $shipment->order->country ?? '' }}<br>
                        {{ $shipment->order->phone ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Action -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Update Status') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.shipments.update-status', $shipment->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Shipment Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $shipment->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="shipped" {{ $shipment->status == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                            <option value="in_transit" {{ $shipment->status == 'in_transit' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                            <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="returned" {{ $shipment->status == 'returned' ? 'selected' : '' }}>{{ __('Returned') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Admin Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Add optional notes for this update..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">{{ __('Update Status') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

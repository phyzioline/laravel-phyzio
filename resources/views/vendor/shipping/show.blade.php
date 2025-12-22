@extends('dashboard.layouts.app')

@section('content')
<main class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shipment Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.shipments.index') }}">Shipments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shipment #{{ $shipment->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        {{-- Shipment Information --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Shipment #{{ $shipment->id }}</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order ID:</strong> #{{ $shipment->order_id }}</p>
                            <p class="mb-1"><strong>Customer:</strong> {{ $shipment->order->name }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $shipment->order->address }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $shipment->order->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status:</strong> 
                                <span class="badge bg-{{ $shipment->shipment_status == 'delivered' ? 'success' : ($shipment->shipment_status == 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Created:</strong> {{ $shipment->created_at->format('M d, Y H:i') }}</p>
                            @if($shipment->shipped_at)
                            <p class="mb-1"><strong>Shipped:</strong> {{ $shipment->shipped_at->format('M d, Y H:i') }}</p>
                            @endif
                            @if($shipment->delivered_at)
                            <p class="mb-1"><strong>Delivered:</strong> {{ $shipment->delivered_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Items in Shipment --}}
                    <h6 class="mb-2">Items in this Shipment</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shipment->items as $item)
                                <tr>
                                    <td>{{ $item->product->product_name_en ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tracking History --}}
            @if($shipment->trackingLogs->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Tracking History</h5>
                    <div class="timeline">
                        @foreach($shipment->trackingLogs as $log)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <i class="fa fa-{{ $log->source == 'api' ? 'robot' : 'user' }}"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ ucfirst($log->status) }}</strong>
                                        <small class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($log->description)
                                    <p class="mb-0 text-muted small">{{ $log->description }}</p>
                                    @endif
                                    @if($log->location)
                                    <p class="mb-0 text-muted small"><i class="fa fa-map-marker-alt me-1"></i>{{ $log->location }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Add/Update Tracking --}}
        <div class="col-lg-4">
            @if($shipment->shipment_status == 'pending' || !$shipment->tracking_number)
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Add Tracking Information</h5>
                    
                    <form action="{{ route('vendor.shipments.update', $shipment->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Courier <span class="text-danger">*</span></label>
                            <select name="courier" class="form-select" required>
                                <option value="">Select Courier</option>
                                <option value="Aramex" {{ old('courier', $shipment->courier) == 'Aramex' ? 'selected' : '' }}>Aramex</option>
                                <option value="DHL" {{ old('courier', $shipment->courier) == 'DHL' ? 'selected' : '' }}>DHL</option>
                                <option value="FedEx" {{ old('courier', $shipment->courier) == 'FedEx' ? 'selected' : '' }}>FedEx</option>
                                <option value="UPS" {{ old('courier', $shipment->courier) == 'UPS' ? 'selected' : '' }}>UPS</option>
                                <option value="Egypt Post" {{ old('courier', $shipment->courier) == 'Egypt Post' ? 'selected' : '' }}>Egypt Post</option>
                                <option value="Other" {{ old('courier', $shipment->courier) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('courier')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tracking Number <span class="text-danger">*</span></label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $shipment->tracking_number) }}" required placeholder="e.g. 1234567890">
                            @error('tracking_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any additional shipping notes...">{{ old('notes', $shipment->notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-truck me-2"></i>Update Tracking
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Tracking Information</h5>
                    <p class="mb-1"><strong>Courier:</strong> <span class="badge bg-secondary">{{ $shipment->courier }}</span></p>
                    <p class="mb-1"><strong>Tracking Number:</strong></p>
                    <code class="d-block mb-2">{{ $shipment->tracking_number }}</code>
                    @if($shipment->notes)
                    <p class="mb-1"><strong>Notes:</strong></p>
                    <p class="text-muted small">{{ $shipment->notes }}</p>
                    @endif
                    
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="fa fa-check-circle me-2"></i>Tracking added successfully!
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

@extends('dashboard.layouts.app')

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.shipping-management.index') }}">Shipping Management</a></li>
                        <li class="breadcrumb-item active">Shipment #{{ $shipment->id }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row g-4">
            {{-- Main Details --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-2 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Shipment Details #{{ $shipment->id }}</h5>
                        @php
                            $badgeClass = match($shipment->shipment_status) {
                                'pending' => 'bg-secondary',
                                'ready_to_ship' => 'bg-warning text-dark',
                                'picked_up' => 'bg-info text-dark',
                                'shipped', 'in_transit' => 'bg-primary',
                                'out_for_delivery' => 'bg-info',
                                'delivered' => 'bg-success',
                                'exception' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge rounded-pill {{ $badgeClass }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Pickup Location (Vendor)</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Vendor:</dt>
                                    <dd class="col-sm-8">{{ $shipment->vendor_name ?? $shipment->vendor->name ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Phone:</dt>
                                    <dd class="col-sm-8">{{ $shipment->vendor_phone ?? $shipment->vendor->phone ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Address:</dt>
                                    <dd class="col-sm-8">{{ $shipment->vendor_address ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">City:</dt>
                                    <dd class="col-sm-8">{{ $shipment->vendor_city ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Governorate:</dt>
                                    <dd class="col-sm-8">{{ $shipment->vendor_governorate ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Delivery Location (Customer)</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Customer:</dt>
                                    <dd class="col-sm-8">{{ $shipment->customer_name ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Phone:</dt>
                                    <dd class="col-sm-8">{{ $shipment->customer_phone ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Address:</dt>
                                    <dd class="col-sm-8">{{ $shipment->customer_address ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">City:</dt>
                                    <dd class="col-sm-8">{{ $shipment->customer_city ?? 'N/A' }}</dd>
                                    
                                    <dt class="col-sm-4">Governorate:</dt>
                                    <dd class="col-sm-8">{{ $shipment->customer_governorate ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Shipping Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-5">Provider:</dt>
                                    <dd class="col-sm-7">
                                        @if($shipment->shipping_provider)
                                            <span class="badge bg-info">{{ ucfirst($shipment->shipping_provider) }}</span>
                                        @else
                                            <span class="text-muted">Manual</span>
                                        @endif
                                    </dd>
                                    
                                    <dt class="col-sm-5">Tracking Number:</dt>
                                    <dd class="col-sm-7">
                                        @if($shipment->tracking_number)
                                            <code>{{ $shipment->tracking_number }}</code>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </dd>
                                    
                                    <dt class="col-sm-5">Shipping Method:</dt>
                                    <dd class="col-sm-7">{{ ucfirst($shipment->shipping_method ?? 'Standard') }}</dd>
                                    
                                    <dt class="col-sm-5">Shipping Cost:</dt>
                                    <dd class="col-sm-7">
                                        @if($shipment->shipping_cost)
                                            <strong>{{ number_format($shipment->shipping_cost, 2) }} EGP</strong>
                                        @else
                                            <span class="text-muted">Not calculated</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Package Details</h6>
                                <dl class="row">
                                    <dt class="col-sm-5">Weight:</dt>
                                    <dd class="col-sm-7">{{ $shipment->package_weight ?? 'N/A' }} grams</dd>
                                    
                                    <dt class="col-sm-5">Dimensions:</dt>
                                    <dd class="col-sm-7">
                                        @if($shipment->package_length)
                                            {{ $shipment->package_length }} x {{ $shipment->package_width }} x {{ $shipment->package_height }} cm
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </dd>
                                    
                                    <dt class="col-sm-5">Description:</dt>
                                    <dd class="col-sm-7">{{ $shipment->package_description ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>

                        {{-- Items in Shipment --}}
                        <hr>
                        <h6 class="fw-bold mb-3">Items in Shipment</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
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
                                        <td>{{ $item->product->product_name_en ?? 'Product Deleted' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }} EGP</td>
                                        <td>{{ number_format($item->price * $item->quantity, 2) }} EGP</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tracking History --}}
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">Tracking History</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($shipment->trackingLogs as $log)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="timeline-marker me-3">
                                        <i class="bi bi-circle-fill text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ ucfirst(str_replace('_', ' ', $log->status)) }}</strong>
                                            <small class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                        <p class="mb-1 text-muted">{{ $log->description }}</p>
                                        @if($log->location)
                                        <small class="text-info"><i class="bi bi-geo-alt"></i> {{ $log->location }}</small>
                                        @endif
                                        <small class="d-block text-muted">Source: {{ ucfirst($log->source) }}</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">No tracking history available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Sidebar --}}
            <div class="col-lg-4">
                {{-- Create with Provider --}}
                @if($shipment->shipment_status === 'pending' && !$shipment->shipping_provider)
                <div class="card border-0 shadow-sm rounded-2 mb-3 border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Create with Shipping Provider</h6>
                        <form action="{{ route('dashboard.shipping-management.create-with-provider', $shipment->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Provider</label>
                                <select name="provider" class="form-select" required>
                                    <option value="bosta">Bosta</option>
                                    <option value="aramex">Aramex</option>
                                    <option value="dhl">DHL</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shipping Method</label>
                                <select name="shipping_method" class="form-select" required>
                                    <option value="standard">Standard</option>
                                    <option value="express">Express</option>
                                    <option value="economy">Economy</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Create Shipment</button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Update Status --}}
                @if($shipment->shipment_status !== 'delivered' && $shipment->shipment_status !== 'cancelled')
                <div class="card border-0 shadow-sm rounded-2 mb-3">
                    <div class="card-body">
                        <h6 class="card-title">Update Status</h6>
                        <form action="{{ route('dashboard.shipping-management.update-status', $shipment->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" {{ $shipment->shipment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ready_to_ship" {{ $shipment->shipment_status === 'ready_to_ship' ? 'selected' : '' }}>Ready to Ship</option>
                                    <option value="picked_up" {{ $shipment->shipment_status === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                    <option value="in_transit" {{ $shipment->shipment_status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="out_for_delivery" {{ $shipment->shipment_status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $shipment->shipment_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="exception" {{ $shipment->shipment_status === 'exception' ? 'selected' : '' }}>Exception</option>
                                </select>
                            </div>
                            @if($shipment->shipment_status === 'delivered')
                            <div class="mb-3">
                                <label class="form-label">Delivered To</label>
                                <input type="text" name="delivered_to" class="form-control" placeholder="Recipient name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Delivery Notes</label>
                                <textarea name="delivery_notes" class="form-control" rows="2"></textarea>
                            </div>
                            @endif
                            @if($shipment->shipment_status === 'exception')
                            <div class="mb-3">
                                <label class="form-label">Exception Reason</label>
                                <textarea name="exception_reason" class="form-control" rows="2" required></textarea>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Update Status</button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Quick Actions --}}
                <div class="card border-0 shadow-sm rounded-2">
                    <div class="card-body">
                        <h6 class="card-title">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            @if($shipment->shipping_label_url)
                            <a href="{{ route('dashboard.shipping-management.download-label', $shipment->id) }}" class="btn btn-info" target="_blank">
                                <i class="bi bi-download"></i> Download Label
                            </a>
                            <a href="{{ route('dashboard.shipping-management.print-label', $shipment->id) }}" class="btn btn-secondary" target="_blank">
                                <i class="bi bi-printer"></i> Print Label
                            </a>
                            @endif
                            <a href="{{ route('dashboard.orders.show', $shipment->order_id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-cart"></i> View Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


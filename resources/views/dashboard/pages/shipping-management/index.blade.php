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
                        <li class="breadcrumb-item active" aria-current="page">Shipping Management</li>
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

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-2" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pending Shipments</p>
                        <h3 class="mb-0 fw-bold text-warning">{{ $stats['pending'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-2" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Ready to Ship</p>
                        <h3 class="mb-0 fw-bold text-info">{{ $stats['ready_to_ship'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-2" style="border-left: 4px solid #007bff !important;">
                    <div class="card-body">
                        <p class="text-muted mb-1">In Transit</p>
                        <h3 class="mb-0 fw-bold text-primary">{{ $stats['in_transit'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-2" style="border-left: 4px solid #28a745 !important;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Delivered Today</p>
                        <h3 class="mb-0 fw-bold text-success">{{ $stats['delivered_today'] }}</h3>
                        <small class="text-muted">{{ $stats['delivered_this_month'] }} this month</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm rounded-2 mb-4">
            <div class="card-body">
                <form action="{{ route('dashboard.shipping-management.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Tracking, Order #, Customer..." value="{{ request('search') }}">
                    </div>
                    @if(auth()->user()->hasRole('admin'))
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ready_to_ship" {{ request('status') == 'ready_to_ship' ? 'selected' : '' }}>Ready to Ship</option>
                            <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="exception" {{ request('status') == 'exception' ? 'selected' : '' }}>Exception</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">City</label>
                        <select name="city" class="form-select">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Provider</label>
                        <select name="provider" class="form-select">
                            <option value="">All Providers</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Shipments Table --}}
        <div class="card border-0 shadow-sm rounded-2">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Shipments</h5>
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Shipment
                </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Shipment ID</th>
                                <th>Order #</th>
                                @if(auth()->user()->hasRole('admin'))
                                <th>Vendor</th>
                                @endif
                                <th>From → To</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Tracking</th>
                                <th>Status</th>
                                <th>Cost</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                            <tr>
                                <td>#{{ $shipment->id }}</td>
                                <td>
                                    <a href="{{ route('dashboard.orders.show', $shipment->order_id) }}" class="text-decoration-none">
                                        {{ $shipment->order->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                @if(auth()->user()->hasRole('admin'))
                                <td>
                                    <div>
                                        <strong>{{ $shipment->vendor->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $shipment->vendor_city ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                @endif
                                <td>
                                    <div class="small">
                                        <strong>From:</strong> {{ $shipment->vendor_city ?? 'N/A' }}<br>
                                        <strong>To:</strong> {{ $shipment->customer_city ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $shipment->customer_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $shipment->customer_phone ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($shipment->shipping_provider)
                                        <span class="badge bg-info">{{ ucfirst($shipment->shipping_provider) }}</span>
                                    @else
                                        <span class="text-muted">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @if($shipment->tracking_number)
                                        <code>{{ $shipment->tracking_number }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($shipment->shipment_status) {
                                            'pending' => 'bg-secondary',
                                            'ready_to_ship' => 'bg-warning text-dark',
                                            'picked_up' => 'bg-info text-dark',
                                            'shipped', 'in_transit' => 'bg-primary',
                                            'out_for_delivery' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'exception' => 'bg-danger',
                                            'returned' => 'bg-warning',
                                            'cancelled' => 'bg-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($shipment->shipping_cost)
                                        <strong>{{ number_format($shipment->shipping_cost, 2) }} EGP</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dashboard.shipping-management.show', $shipment->id) }}" class="btn btn-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($shipment->shipping_label_url)
                                        <a href="{{ route('dashboard.shipping-management.download-label', $shipment->id) }}" class="btn btn-info" title="Download Label" target="_blank">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('admin') ? '11' : '10' }}" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-truck fa-3x mb-3 opacity-50"></i>
                                        <p>No shipments found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $shipments->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('dashboard.layouts.app')

@section('content')
<main class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">My Shipments</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shipments</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">All Shipments</h5>
                <div>
                    <a href="{{ route('vendor.shipments.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">All</a>
                    <a href="{{ route('vendor.shipments.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                    <a href="{{ route('vendor.shipments.index', ['status' => 'shipped']) }}" class="btn btn-sm btn-outline-info {{ request('status') == 'shipped' ? 'active' : '' }}">Shipped</a>
                    <a href="{{ route('vendor.shipments.index', ['status' => 'delivered']) }}" class="btn btn-sm btn-outline-success {{ request('status') == 'delivered' ? 'active' : '' }}">Delivered</a>
                </div>
            </div>

            @if($shipments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Customer</th>
                            <th>Courier</th>
                            <th>Tracking Number</th>
                            <th>Status</th>
                            <th>Shipped Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                        <tr>
                            <td><strong>#{{ $shipment->id }}</strong></td>
                            <td><a href="{{ route('vendor.orders.show', $shipment->order_id) }}">#{{ $shipment->order_id }}</a></td>
                            <td>{{ $shipment->items->count() }}</td>
                            <td>{{ $shipment->order->name ?? 'N/A' }}</td>
                            <td>
                                @if($shipment->courier)
                                    <span class="badge bg-secondary">{{ $shipment->courier }}</span>
                                @else
                                    <span class="text-muted">Not set</span>
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
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'shipped' => 'info',
                                        'in_transit' => 'primary',
                                        'delivered' => 'success',
                                        'returned' => 'danger',
                                    ];
                                    $color = $statusColors[$shipment->shipment_status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}</span>
                            </td>
                            <td>
                                @if($shipment->shipped_at)
                                    {{ $shipment->shipped_at->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not shipped</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('vendor.shipments.show', $shipment->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info mb-0">
                <i class="fa fa-info-circle me-2"></i>
                @if(request('status'))
                    No shipments found with status: <strong>{{ request('status') }}</strong>
                @else
                    No shipments found.
                @endif
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

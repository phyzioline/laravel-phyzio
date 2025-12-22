@extends('dashboard.layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('All Shipments') }}</h5>
        <div>
            <!-- Filter Dropdown -->
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-filter"></i> {{ request('status') ? ucfirst(request('status')) : __('All Status') }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dashboard.shipments.index') }}">{{ __('All') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.shipments.index', ['status' => 'pending']) }}">{{ __('Pending') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.shipments.index', ['status' => 'shipped']) }}">{{ __('Shipped') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.shipments.index', ['status' => 'delivered']) }}">{{ __('Delivered') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard.shipments.index', ['status' => 'cancelled']) }}">{{ __('Cancelled') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Vendor') }}</th>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Items') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                    <tr>
                        <td>#{{ $shipment->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-info">
                                    <h6 class="mb-0">{{ $shipment->vendor->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $shipment->vendor->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>#{{ $shipment->order_id }}</td>
                        <td>{{ $shipment->items->count() }} {{ __('items') }}</td>
                        <td>{{ number_format($shipment->total_amount, 2) }}</td>
                        <td>
                            @php
                                $badgeClass = match($shipment->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'shipped' => 'bg-info text-dark',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge rounded-pill {{ $badgeClass }}">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </td>
                        <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('dashboard.shipments.show', $shipment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> {{ __('View') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-box-seam display-4 text-muted"></i>
                                <p class="mt-3 text-muted">{{ __('No shipments found.') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection

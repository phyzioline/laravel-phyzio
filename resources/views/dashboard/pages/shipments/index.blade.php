@extends('dashboard.layouts.app')
@section('title', 'My Shipments')

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Shipments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Shipments</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Courier & Tracking</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Shipped At</th>
                                <th>Delivered At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.orders.show', $shipment->order_id) }}">#{{ $shipment->order->order_number ?? $shipment->order_id }}</a>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $shipment->courier }}</div>
                                        <div class="text-muted small">{{ $shipment->tracking_number }}</div>
                                    </td>
                                    <td>{{ $shipment->items->count() }} Items</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $shipment->shipment_status == 'delivered' ? 'success' : ($shipment->shipment_status == 'shipped' ? 'primary' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->shipped_at ? $shipment->shipped_at->format('d M Y') : '-' }}</td>
                                    <td>{{ $shipment->delivered_at ? $shipment->delivered_at->format('d M Y') : '-' }}</td>
                                     <td>{{ $shipment->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trackModal{{ $shipment->id }}">
                                            Update Status
                                        </button>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="trackModal{{ $shipment->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('dashboard.shipments.track', $shipment->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Tracking</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Status</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="shipped" {{ $shipment->shipment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                    <option value="out_for_delivery" {{ $shipment->shipment_status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                                                    <option value="delivered" {{ $shipment->shipment_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                                    <option value="exception" {{ $shipment->shipment_status == 'exception' ? 'selected' : '' }}>Exception</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Notes</label>
                                                                <textarea name="notes" class="form-control" rows="2">{{ $shipment->notes }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No shipments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $shipments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

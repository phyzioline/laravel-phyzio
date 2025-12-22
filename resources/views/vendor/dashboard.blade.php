@extends('dashboard.layouts.app')

@section('content')
<main class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vendor Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Welcome Message --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                            <p class="mb-0 text-secondary">Manage your orders and shipments</p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('vendor.shipments.index') }}" class="btn btn-primary">
                                <i class="fa fa-truck me-2"></i>View Shipments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Orders</p>
                            <h4 class="my-1">{{ $stats['total_orders'] }}</h4>
                        </div>
                        <div class="widgets-icons bg-light-success text-success ms-auto">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pending Shipments</p>
                            <h4 class="my-1 text-warning">{{ $stats['pending_shipments'] }}</h4>
                        </div>
                        <div class="widgets-icons bg-light-warning text-warning ms-auto">
                            <i class="fa fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Available Balance</p>
                            <h4 class="my-1 text-success">${{ number_format($stats['available_balance'], 2) }}</h4>
                        </div>
                        <div class="widgets-icons bg-light-success text-success ms-auto">
                            <i class="fa fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pending Balance</p>
                            <h4 class="my-1 text-info">${{ number_format($stats['pending_balance'], 2) }}</h4>
                        </div>
                        <div class="widgets-icons bg-light-info text-info ms-auto">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Shipments Table --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Pending Shipments</h5>
                <a href="{{ route('vendor.shipments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            
            @if($pendingShipments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingShipments as $shipment)
                        <tr>
                            <td><strong>#{{ $shipment->order->id }}</strong></td>
                            <td>{{ $shipment->items->count() }} item(s)</td>
                            <td>{{ $shipment->order->name }}</td>
                            <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-warning">{{ ucfirst($shipment->shipment_status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('vendor.shipments.show', $shipment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-truck me-1"></i>Add Tracking
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info mb-0">
                <i class="fa fa-info-circle me-2"></i>No pending shipments. All orders are up to date!
            </div>
            @endif
        </div>
    </div>

    {{-- Wallet Summary --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Wallet Summary</h5>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending Balance:</span>
                            <strong class="text-warning">${{ number_format($walletSummary['pending_balance'], 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Available Balance:</span>
                            <strong class="text-success">${{ number_format($walletSummary['available_balance'], 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>On Hold:</span>
                            <strong class="text-danger">${{ number_format($walletSummary['on_hold_balance'], 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span><strong>Total Earned:</strong></span>
                            <strong class="text-primary">${{ number_format($walletSummary['total_earned'], 2) }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('vendor.wallet') }}" class="btn btn-outline-primary w-100">
                        <i class="fa fa-wallet me-2"></i>View Wallet
                    </a>
                </div>
            </div>
        </div>
        
        @if($walletSummary['next_settlement_date'])
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Next Settlement</h5>
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-calendar me-2"></i>
                        Your next settlement will be available on:
                        <strong>{{ \Carbon\Carbon::parse($walletSummary['next_settlement_date'])->format('M d, Y') }}</strong>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fa fa-info-circle me-1"></i>
                        Funds are held for 14 days after delivery for security purposes.
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

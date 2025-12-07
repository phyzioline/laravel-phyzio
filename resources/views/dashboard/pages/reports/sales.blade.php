@extends('dashboard.layouts.app')
@section('title', __('Sales Reports'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reports</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboard.reports.sales') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Total Revenue</h5>
                                <h3 class="mb-0 text-white">{{ number_format($totalRevenue, 2) }} EGP</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title text-white">Total Orders</h5>
                                <h3 class="mb-0 text-white">{{ $totalOrders }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td>{{ number_format($order->total, 2) }} EGP</td>
                                    <td><span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($order->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No orders found for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

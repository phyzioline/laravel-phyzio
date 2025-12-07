@extends('dashboard.layouts.app')
@section('title', __('Order Reports'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Orders</h6>
                        <h3>{{ $stats['total_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Pending</h6>
                        <h3>{{ $stats['pending_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Completed</h6>
                        <h3>{{ $stats['completed_orders'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Cancelled</h6>
                        <h3>{{ $stats['cancelled_orders'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Orders by Status</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($ordersByStatus as $status)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($status->status) }}
                            <span class="badge bg-primary rounded-pill">{{ $status->count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('dashboard.layouts.app')
@section('title', __('Customer Analytics'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Total Customers</h5>
                        <h2>{{ $totalCustomers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-purple text-white">
                    <div class="card-body">
                        <h5>New This Month</h5>
                        <h2>{{ $newCustomers }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Top Customers by Spend</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->order_count }}</td>
                                    <td>${{ number_format($customer->total_spent, 2) }}</td>
                                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

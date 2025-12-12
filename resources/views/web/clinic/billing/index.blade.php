@extends('web.layouts.dashboard_master')

@section('title', 'Billing')
@section('header_title', 'Billing & Invoices')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <h6>Total Revenue</h6>
                <h3>$45,200</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Pending Payments</h6>
                <h3 class="text-warning">$3,450</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="font-weight-bold mb-0">Invoices</h5>
    </div>
    <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Invoice ID</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td><a href="#" class="font-weight-bold text-primary">{{ $inv->id }}</a></td>
                        <td>{{ $inv->patient }}</td>
                        <td>{{ $inv->date }}</td>
                        <td>${{ number_format($inv->amount, 2) }}</td>
                        <td>
                            <span class="badge {{ $inv->status == 'Paid' ? 'badge-success' : 'badge-warning' }}">{{ $inv->status }}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary"><i class="las la-download"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

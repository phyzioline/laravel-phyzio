@extends('dashboard.layouts.app')
@section('title', __('Customer Insights'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Average Order Value</h6>
                        <h2 class="text-primary">${{ number_format($avgOrderValue, 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Repeat Customer Rate</h6>
                        <h2 class="text-success">{{ $repeatCustomerRate }}%</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

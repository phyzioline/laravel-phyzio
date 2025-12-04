@extends('dashboard.layouts.app')
@section('title', __('View Order'))
@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row mb-5">
                <div class="col-12 col-xl-10 mx-auto">
                    <div class="card shadow-sm p-4 border-0">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                            <h4 class="text-primary mb-0">{{ __('Order Details') }}</h4>
                            <a href="{{ route('dashboard.orders.print-label', $order->id) }}" target="_blank" class="btn btn-success">
                                <i class="fa fa-print"></i> {{ __('Print Shipping Label') }}
                            </a>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>{{ __('Total') }}:</strong> {{ $order->total }}</p>
                                <p><strong>{{ __('Status') }}:</strong> <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></p>
                                <p><strong>{{ __('Payment Method') }}:</strong> {{ $order->payment_method }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Name') }}:</strong> {{ $order->name }}</p>
                                <p><strong>{{ __('Phone') }}:</strong> {{ $order->phone }}</p>
                                <p><strong>{{ __('Address') }}:</strong> {{ $order->address }}</p>
                            </div>
                        </div>

                        <h5 class="text-primary border-bottom pb-2 mt-4">{{ __('User Buyer') }}</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>{{ __('Name') }}:</strong> {{ $order->user->name }}</p>
                                <p><strong>{{ __('Phone') }}:</strong> {{ $order->user->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Email') }}:</strong> {{ $order->email ?? $order->user->email }}</p>
                            </div>
                        </div>

                        <h5 class="text-primary border-bottom pb-2 mt-4">{{ __('Products in Order') }}</h5>
                        @foreach ($order->items as $item)
                            <div class="border rounded p-3 mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if ($item->product && $item->product->productImages->first())
                                            <img src="{{ asset($item->product->productImages->first()->image) }}" class="img-fluid rounded" alt="Product Image">
                                        @else
                                            <img src="{{ asset('default/default.png') }}" class="img-fluid rounded" alt="No Image">
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <p><strong>{{ __('Product Name') }}:</strong> {{ $item->product?->{'product_name_' . app()->getLocale()} ?? '-' }}</p>
                                        <p><strong>{{ __('Category') }}:</strong> {{ $item->product?->category?->{'name_' . app()->getLocale()} ?? '-' }}</p>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p><strong>{{ __('Price') }}:</strong> {{ $item->price }}</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><strong>{{ __('Quantity') }}:</strong> {{ $item->quantity }}</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <p><strong>{{ __('Total') }}:</strong> {{ $item->total }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

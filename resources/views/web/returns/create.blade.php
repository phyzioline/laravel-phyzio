@extends('web.layouts.app')

@section('title', __('Request Return'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 font-weight-bold" style="color: #02767F;">
                        <i class="las la-undo-alt mr-2"></i>{{ __('Request Return') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Product Information -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="font-weight-bold mb-3">{{ __('Product Information') }}</h6>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($orderItem->product && $orderItem->product->productImages->first())
                                    <img src="{{ asset($orderItem->product->productImages->first()->image) }}" 
                                         alt="{{ $orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 120px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="mb-2">{{ $orderItem->product->{'product_name_' . app()->getLocale()} ?? __('Product') }}</h5>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Order Number') }}:</strong> {{ $orderItem->order->order_number }}
                                </p>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Quantity') }}:</strong> {{ $orderItem->quantity }}
                                </p>
                                <p class="text-muted mb-1">
                                    <strong>{{ __('Price') }}:</strong> {{ number_format($orderItem->total, 2) }} {{ __('EGP') }}
                                </p>
                                <p class="text-muted mb-0">
                                    <strong>{{ __('Order Date') }}:</strong> {{ $orderItem->order->created_at->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Return Form -->
                    <form action="{{ route('returns.store.' . app()->getLocale()) }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_item_id" value="{{ $orderItem->id }}">

                        <div class="form-group">
                            <label for="reason" class="font-weight-bold">
                                {{ __('Reason for Return') }} <span class="text-danger">*</span>
                            </label>
                            <textarea name="reason" 
                                      id="reason" 
                                      class="form-control @error('reason') is-invalid @enderror" 
                                      rows="5" 
                                      required
                                      minlength="10"
                                      maxlength="500"
                                      placeholder="{{ __('Please provide a detailed reason for your return request (minimum 10 characters)...') }}">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ __('Minimum 10 characters, maximum 500 characters') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="refund_amount" class="font-weight-bold">
                                {{ __('Expected Refund Amount') }} ({{ __('EGP') }})
                            </label>
                            <input type="number" 
                                   name="refund_amount" 
                                   id="refund_amount" 
                                   class="form-control @error('refund_amount') is-invalid @enderror" 
                                   value="{{ old('refund_amount', $orderItem->total) }}"
                                   step="0.01"
                                   min="0"
                                   max="{{ $orderItem->total }}">
                            @error('refund_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ __('Default: Full amount (') }}{{ number_format($orderItem->total, 2) }} {{ __('EGP') }})
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="las la-info-circle mr-2"></i>
                            <strong>{{ __('Note') }}:</strong> {{ __('Your return request will be reviewed within 2-3 business days. You will be notified via email once a decision is made.') }}
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('history_order.index.' . app()->getLocale()) }}" class="btn btn-outline-secondary">
                                <i class="las la-times mr-1"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-paper-plane mr-1"></i>{{ __('Submit Return Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('web.layouts.app')

@section('title', __('Return Request Details'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold" style="color: #02767F;">
                    <i class="las la-undo-alt mr-2"></i>{{ __('Return Request #') }}{{ $return->id }}
                </h2>
                <a href="{{ route('returns.index.' . app()->getLocale()) }}" class="btn btn-outline-primary">
                    <i class="las la-arrow-left mr-1"></i>{{ __('Back to Returns') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <!-- Main Details -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 font-weight-bold">{{ __('Return Details') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Product Information -->
                            <div class="mb-4 p-3 bg-light rounded">
                                <h6 class="font-weight-bold mb-3">{{ __('Product Information') }}</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($return->orderItem->product && $return->orderItem->product->productImages->first())
                                            <img src="{{ asset($return->orderItem->product->productImages->first()->image) }}" 
                                                 alt="{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 150px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="mb-2">{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} ?? __('Product') }}</h5>
                                        <p class="text-muted mb-1">
                                            <strong>{{ __('Order Number') }}:</strong> {{ $return->orderItem->order->order_number }}
                                        </p>
                                        <p class="text-muted mb-1">
                                            <strong>{{ __('Quantity') }}:</strong> {{ $return->orderItem->quantity }}
                                        </p>
                                        <p class="text-muted mb-0">
                                            <strong>{{ __('Original Price') }}:</strong> {{ number_format($return->orderItem->total, 2) }} {{ __('EGP') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Reason -->
                            <div class="mb-4">
                                <h6 class="font-weight-bold mb-2">{{ __('Reason for Return') }}</h6>
                                <p class="text-muted">{{ $return->reason }}</p>
                            </div>

                            <!-- Admin Notes (if available) -->
                            @if($return->admin_notes)
                                <div class="mb-4 p-3 bg-info bg-light rounded">
                                    <h6 class="font-weight-bold mb-2">
                                        <i class="las la-comment-dots mr-1"></i>{{ __('Admin Notes') }}
                                    </h6>
                                    <p class="mb-0">{{ $return->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Status & Actions -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 font-weight-bold">{{ __('Status') }}</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $statusColors = [
                                    'requested' => 'warning',
                                    'approved' => 'info',
                                    'rejected' => 'danger',
                                    'refunded' => 'success',
                                    'cancelled' => 'secondary'
                                ];
                                $statusLabels = [
                                    'requested' => __('Requested'),
                                    'approved' => __('Approved'),
                                    'rejected' => __('Rejected'),
                                    'refunded' => __('Refunded'),
                                    'cancelled' => __('Cancelled')
                                ];
                            @endphp
                            <div class="text-center mb-3">
                                <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }} badge-lg" style="font-size: 1rem; padding: 8px 16px;">
                                    {{ $statusLabels[$return->status] ?? ucfirst($return->status) }}
                                </span>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <strong>{{ __('Requested Date') }}:</strong><br>
                                <span class="text-muted">{{ $return->created_at->format('Y-m-d H:i') }}</span>
                            </div>

                            @if($return->approved_at)
                                <div class="mb-2">
                                    <strong>{{ __('Processed Date') }}:</strong><br>
                                    <span class="text-muted">{{ $return->approved_at->format('Y-m-d H:i') }}</span>
                                </div>
                            @endif

                            @if($return->resolved_at)
                                <div class="mb-2">
                                    <strong>{{ __('Resolved Date') }}:</strong><br>
                                    <span class="text-muted">{{ $return->resolved_at->format('Y-m-d H:i') }}</span>
                                </div>
                            @endif

                            @if($return->approver)
                                <div class="mb-2">
                                    <strong>{{ __('Processed By') }}:</strong><br>
                                    <span class="text-muted">{{ $return->approver->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 font-weight-bold">{{ __('Refund Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('Refund Amount') }}:</strong><br>
                                <span class="h4 text-success">{{ number_format($return->refund_amount ?? 0, 2) }} {{ __('EGP') }}</span>
                            </div>

                            @if($return->status === 'requested')
                                <form action="{{ route('returns.cancel.' . app()->getLocale(), $return->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to cancel this return request?') }}');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-block">
                                        <i class="las la-times mr-1"></i>{{ __('Cancel Request') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


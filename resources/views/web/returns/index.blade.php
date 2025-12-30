@extends('web.layouts.app')

@section('title', __('My Returns & Refunds'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold" style="color: #02767F;">
                    <i class="las la-undo-alt mr-2"></i>{{ __('My Returns & Refunds') }}
                </h2>
                <a href="{{ route('history_order.index.' . app()->getLocale()) }}" class="btn btn-outline-primary">
                    <i class="las la-arrow-left mr-1"></i>{{ __('Back to Orders') }}
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($returns->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Return ID') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Order Number') }}</th>
                                        <th>{{ __('Reason') }}</th>
                                        <th>{{ __('Refund Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Requested Date') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($returns as $return)
                                        <tr>
                                            <td>#{{ $return->id }}</td>
                                            <td>
                                                @if($return->orderItem->product)
                                                    <div class="d-flex align-items-center">
                                                        @if($return->orderItem->product->productImages->first())
                                                            <img src="{{ asset($return->orderItem->product->productImages->first()->image) }}" 
                                                                 alt="{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                                                 class="mr-2" 
                                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <span>{{ Str::limit($return->orderItem->product->{'product_name_' . app()->getLocale()}, 30) }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">{{ __('Product removed') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $return->orderItem->order->order_number ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($return->reason, 50) }}</td>
                                            <td>
                                                <strong class="text-success">{{ number_format($return->refund_amount ?? 0, 2) }} {{ __('EGP') }}</strong>
                                            </td>
                                            <td>
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
                                                <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$return->status] ?? ucfirst($return->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $return->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('returns.show.' . app()->getLocale(), $return->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-eye"></i> {{ __('View') }}
                                                </a>
                                                @if($return->status === 'requested')
                                                    <form action="{{ route('returns.cancel.' . app()->getLocale(), $return->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('Are you sure you want to cancel this return request?') }}');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="las la-times"></i> {{ __('Cancel') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        {{ $returns->links() }}
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <i class="las la-undo-alt" style="font-size: 64px; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">{{ __('No Return Requests') }}</h4>
                        <p class="text-muted">{{ __('You haven\'t requested any returns yet.') }}</p>
                        <a href="{{ route('history_order.index.' . app()->getLocale()) }}" class="btn btn-primary mt-3">
                            <i class="las la-shopping-bag mr-1"></i>{{ __('View My Orders') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


@extends('dashboard.layouts.app')

@section('title', __('Return Management'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold">
                    <i class="las la-undo-alt mr-2"></i>{{ __('Return Management') }}
                </h2>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Requested') }}</h5>
                        <h2 class="mb-0">{{ $stats['requested'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Approved') }}</h5>
                        <h2 class="mb-0">{{ $stats['approved'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Rejected') }}</h5>
                        <h2 class="mb-0">{{ $stats['rejected'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Refunded') }}</h5>
                        <h2 class="mb-0">{{ $stats['refunded'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                           href="{{ route('dashboard.returns.index', ['status' => 'all']) }}">
                            {{ __('All Returns') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'requested' ? 'active' : '' }}" 
                           href="{{ route('dashboard.returns.index', ['status' => 'requested']) }}">
                            {{ __('Requested') }} <span class="badge badge-warning">{{ $stats['requested'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                           href="{{ route('dashboard.returns.index', ['status' => 'approved']) }}">
                            {{ __('Approved') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                           href="{{ route('dashboard.returns.index', ['status' => 'rejected']) }}">
                            {{ __('Rejected') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'refunded' ? 'active' : '' }}" 
                           href="{{ route('dashboard.returns.index', ['status' => 'refunded']) }}">
                            {{ __('Refunded') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Returns Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Customer') }}</th>
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
                            @forelse($returns as $return)
                                <tr>
                                    <td>#{{ $return->id }}</td>
                                    <td>
                                        {{ $return->orderItem->order->user->name ?? __('Guest') }}<br>
                                        <small class="text-muted">{{ $return->orderItem->order->user->email ?? $return->orderItem->order->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($return->orderItem->product)
                                            <div class="d-flex align-items-center">
                                                @if($return->orderItem->product->productImages->first())
                                                    <img src="{{ asset($return->orderItem->product->productImages->first()->image) }}" 
                                                         alt="{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                                         class="mr-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                @endif
                                                <span>{{ Str::limit($return->orderItem->product->{'product_name_' . app()->getLocale()}, 30) }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('Product removed') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $return->orderItem->order->order_number ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($return->reason, 40) }}</td>
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
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                            {{ ucfirst($return->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $return->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.returns.show', $return->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="las la-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="las la-inbox" style="font-size: 48px; color: #ccc;"></i>
                                        <p class="text-muted mt-3">{{ __('No return requests found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($returns->hasPages())
                <div class="card-footer bg-white">
                    {{ $returns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


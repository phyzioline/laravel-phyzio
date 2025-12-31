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
            <div class="col-md-2">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Total Returns') }}</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Requested') }}</h6>
                        <h3 class="mb-0">{{ $stats['requested'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Approved') }}</h6>
                        <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Rejected') }}</h6>
                        <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Refunded') }}</h6>
                        <h3 class="mb-0">{{ $stats['refunded'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('Total Refunded') }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_refund_amount'], 0) }}</h3>
                        <small>EGP</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter"></i> {{ __('Advanced Filters') }}
                    </button>
                </h5>
            </div>
            <div id="filtersCollapse" class="collapse {{ request()->hasAny(['search', 'date_from', 'date_to', 'order_id']) ? 'show' : '' }}">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard.returns.index') }}" class="row g-3">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Search') }}</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ $search }}" 
                                   placeholder="{{ __('Return ID, Order #, Customer, Product...') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('Date From') }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('Date To') }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Order ID') }}</label>
                            <input type="number" name="order_id" class="form-control" value="{{ $orderId }}" placeholder="Order ID">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> {{ __('Apply Filters') }}
                            </button>
                            <a href="{{ route('dashboard.returns.index', ['status' => $status]) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('Clear Filters') }}
                            </a>
                        </div>
                    </form>
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
                <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table table-hover mb-0 table-sm" style="font-size: 0.85rem;">
                        <thead class="bg-light" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th style="width: 4%;">{{ __('ID') }}</th>
                                <th style="width: 5%;">{{ __('Product') }}</th>
                                <th style="width: 10%;">{{ __('Customer') }}</th>
                                <th style="width: 8%;">{{ __('Order #') }}</th>
                                <th style="width: 15%;">{{ __('Reason') }}</th>
                                <th style="width: 8%;">{{ __('Refund Amount') }}</th>
                                <th style="width: 7%;">{{ __('Status') }}</th>
                                <th style="width: 8%;">{{ __('Requested') }}</th>
                                <th style="width: 8%;">{{ __('Processed') }}</th>
                                <th style="width: 10%;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $return)
                                <tr>
                                    <td><strong>#{{ $return->id }}</strong></td>
                                    <td>
                                        @if($return->orderItem->product)
                                            <div class="d-flex align-items-center">
                                                @if($return->orderItem->product->productImages->first())
                                                    <img src="{{ asset($return->orderItem->product->productImages->first()->image) }}" 
                                                         alt="{{ $return->orderItem->product->{'product_name_' . app()->getLocale()} }}" 
                                                         class="mr-2 img-thumbnail" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                @endif
                                                <span style="font-size: 0.75rem;">{{ Str::limit($return->orderItem->product->{'product_name_' . app()->getLocale()}, 20) }}</span>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ __('Removed') }}</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.8rem;">
                                        <strong>{{ Str::limit($return->orderItem->order->user->name ?? $return->orderItem->order->name ?? __('Guest'), 15) }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($return->orderItem->order->user->email ?? $return->orderItem->order->email ?? 'N/A', 20) }}</small>
                                    </td>
                                    <td style="font-size: 0.75rem;"><strong>{{ $return->orderItem->order->order_number ?? 'N/A' }}</strong></td>
                                    <td style="font-size: 0.75rem;">{{ Str::limit($return->reason, 35) }}</td>
                                    <td style="font-size: 0.8rem;">
                                        <strong class="text-success">{{ number_format($return->refund_amount ?? 0, 0) }}</strong><br>
                                        <small>EGP</small>
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
                                        <span class="badge bg-{{ $statusColors[$return->status] ?? 'secondary' }}" style="font-size: 0.75rem;">
                                            {{ ucfirst($return->status) }}
                                        </span>
                                    </td>
                                    <td style="font-size: 0.75rem;">
                                        {{ $return->created_at->format('Y-m-d') }}<br>
                                        <small>{{ $return->created_at->format('H:i') }}</small>
                                    </td>
                                    <td style="font-size: 0.75rem;">
                                        @if($return->approved_at)
                                            {{ $return->approved_at->format('Y-m-d') }}<br>
                                            <small>{{ $return->approved_at->format('H:i') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm" role="group">
                                            <a href="{{ route('dashboard.returns.show', $return->id) }}" 
                                               class="btn btn-sm btn-primary mb-1" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                <i class="fas fa-eye"></i> {{ __('View') }}
                                            </a>
                                            @if($return->orderItem->order)
                                            <a href="{{ route('dashboard.orders.show', $return->orderItem->order->id) }}" 
                                               class="btn btn-sm btn-info" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;" 
                                               title="{{ __('View Order') }}">
                                                <i class="fas fa-shopping-cart"></i> {{ __('Order') }}
                                            </a>
                                            @endif
                                        </div>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted" style="font-size: 0.85rem;">
                            {{ __('Showing') }} {{ $returns->firstItem() ?? 0 }} {{ __('to') }} {{ $returns->lastItem() ?? 0 }} {{ __('of') }} {{ $returns->total() }} {{ __('returns') }}
                        </div>
                        <div>
                            {!! $returns->appends(request()->query())->links('pagination::bootstrap-5', ['class' => 'pagination-sm mb-0']) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


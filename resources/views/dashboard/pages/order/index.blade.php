@extends('dashboard.layouts.app')
@section('title', __('Orders Management'))

@push('styles')
<style>
    /* Orders table responsive layout */
    #example2 {
        table-layout: fixed !important;
        width: 100% !important;
        margin: 0 !important;
    }
    
    #example2 th,
    #example2 td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 6px 8px !important;
    }
    
    /* Ensure table fits within viewport accounting for sidebar */
    .table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Adjust max-height to account for header, stats, and filters */
    @media screen and (min-width: 992px) {
        .table-responsive {
            max-height: calc(100vh - 380px) !important;
        }
    }
    
    /* Ensure sticky header stays on top */
    #example2 thead th {
        position: sticky;
        top: 0;
        z-index: 100;
        background-color: #f8f9fa !important;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Better text wrapping for specific columns */
    #example2 td:nth-child(4), /* Order # */
    #example2 td:nth-child(5), /* Name */
    #example2 td:nth-child(6), /* Phone */
    #example2 td:nth-child(7) { /* Address */
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.3;
    }
    
    /* Remove any left margin/padding that creates empty space */
    .card {
        margin: 0 !important;
    }
    
    .card-body {
        padding: 1rem !important;
        margin: 0 !important;
    }
    
    .main-content {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
</style>
@endpush

@section('content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!-- Order Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Orders</h6>
                            <h3>{{ $stats['total_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6>Pending</h6>
                            <h3>{{ $stats['pending_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Completed</h6>
                            <h3>{{ $stats['completed_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Cancelled</h6>
                            <h3>{{ $stats['cancelled_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Card Orders</h6>
                            <h3>{{ $stats['card_orders'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h6>Return Orders</h6>
                            <h3>{{ $stats['return_orders'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders by Status -->
            <div class="row mb-4">
                <div class="col-12">
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

            <div class="row mb-5">
                <div class="col-12">
                    <div class="card" style="width: 100%; overflow: hidden; margin: 0;">
                        <div class="card-body" style="padding: 1rem; margin: 0;">
                            <!-- Advanced Filters -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                                            <i class="fas fa-filter"></i> {{ __('Advanced Filters') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="filtersCollapse" class="collapse {{ request()->hasAny(['status', 'payment_method', 'payment_status', 'date_from', 'date_to', 'vendor_id', 'search', 'has_returns']) ? 'show' : '' }}">
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('dashboard.orders.index') }}" class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Search') }}</label>
                                                <input type="text" name="search" class="form-control" 
                                                       value="{{ request('search') }}" 
                                                       placeholder="{{ __('Order #, Name, Phone, Email') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Status') }}</label>
                                                <select name="status" class="form-select">
                                                    <option value="all">{{ __('All Statuses') }}</option>
                                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Payment Method') }}</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="all">{{ __('All Methods') }}</option>
                                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>{{ __('Card') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Payment Status') }}</label>
                                                <select name="payment_status" class="form-select">
                                                    <option value="all">{{ __('All Statuses') }}</option>
                                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                                                </select>
                                            </div>
                                            @if(auth()->user()->hasRole('admin') && $vendors->count() > 0)
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Vendor') }}</label>
                                                <select name="vendor_id" class="form-select">
                                                    <option value="">{{ __('All Vendors') }}</option>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                            {{ $vendor->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Date From') }}</label>
                                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Date To') }}</label>
                                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">{{ __('Has Returns') }}</label>
                                                <select name="has_returns" class="form-select">
                                                    <option value="">{{ __('All Orders') }}</option>
                                                    <option value="1" {{ request('has_returns') == '1' ? 'selected' : '' }}>{{ __('With Returns') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> {{ __('Apply Filters') }}
                                                </button>
                                                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> {{ __('Clear Filters') }}
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Tabs -->
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link {{ !request('status') && !request('payment_method') && !request('has_returns') ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index') }}">
                                        {{ __('All Orders') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['status' => 'pending']) }}">
                                        {{ __('Pending') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}">
                                        {{ __('Completed') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['status' => 'cancelled']) }}">
                                        {{ __('Cancelled') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('payment_method') == 'cash' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['payment_method' => 'cash']) }}">
                                        {{ __('Cash Orders') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('payment_method') == 'card' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['payment_method' => 'card']) }}">
                                        {{ __('Card Orders') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request('has_returns') == '1' ? 'active' : '' }}" 
                                       href="{{ route('dashboard.orders.index', ['has_returns' => '1']) }}">
                                        {{ __('Return Orders') }}
                                    </a>
                                </li>
                            </ul>

                            <div class="table-responsive text-center" style="max-height: calc(100vh - 380px); overflow-x: auto; overflow-y: auto; width: 100%; margin: 0; padding: 0;">
                                <table id="example2" class="table table-striped table-bordered table-sm" style="font-size: 0.85rem; width: 100%; table-layout: fixed; margin: 0;">
                                    <thead class="table-light" style="position: sticky; top: 0; z-index: 100; background-color: #f8f9fa !important;">
                                        <tr>
                                            @if(auth()->user()->hasRole('admin'))
                                                {{-- Admin view: 15 columns --}}
                                                <th style="width: 4%;">{{ __('ID') }}</th>
                                                <th style="width: 5%;">{{ __('Product') }}</th>
                                                <th style="width: 6%;">{{ __('Vendor') }}</th>
                                                <th style="width: 7%;">{{ __('Order #') }}</th>
                                                <th style="width: 6%;">{{ __('Name') }}</th>
                                                <th style="width: 6%;">{{ __('Phone') }}</th>
                                                <th style="width: 8%;">{{ __('Address') }}</th>
                                                <th style="width: 5%;">{{ __('Total') }}</th>
                                                <th style="width: 5%;">{{ __('Status') }}</th>
                                                <th style="width: 6%;">{{ __('Pay Status') }}</th>
                                                <th style="width: 5%;">{{ __('Method') }}</th>
                                                <th style="width: 6.5%;">{{ __('Order Date') }}</th>
                                                <th style="width: 6.5%;">{{ __('Updated') }}</th>
                                                <th style="width: 5%;">{{ __('Returns') }}</th>
                                                <th style="width: 8%;">{{ __('Actions') }}</th>
                                            @else
                                                {{-- Vendor view: 14 columns --}}
                                                <th style="width: 4.5%;">{{ __('ID') }}</th>
                                                <th style="width: 5.5%;">{{ __('Product') }}</th>
                                                <th style="width: 8%;">{{ __('Order #') }}</th>
                                                <th style="width: 7%;">{{ __('Name') }}</th>
                                                <th style="width: 7%;">{{ __('Phone') }}</th>
                                                <th style="width: 9%;">{{ __('Address') }}</th>
                                                <th style="width: 5.5%;">{{ __('Total') }}</th>
                                                <th style="width: 5.5%;">{{ __('Status') }}</th>
                                                <th style="width: 6.5%;">{{ __('Pay Status') }}</th>
                                                <th style="width: 5.5%;">{{ __('Method') }}</th>
                                                <th style="width: 7%;">{{ __('Order Date') }}</th>
                                                <th style="width: 7%;">{{ __('Updated') }}</th>
                                                <th style="width: 5.5%;">{{ __('Returns') }}</th>
                                                <th style="width: 8.5%;">{{ __('Actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>
                                                    @if($order->items->first()?->product?->productImages->first())
                                                        <img src="{{ asset($order->items->first()->product->productImages->first()->image) }}" 
                                                             alt="{{ $order->items->first()->product->product_name_en ?? 'Product' }}" 
                                                             class="img-thumbnail" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">No Img</span>
                                                    @endif
                                                </td>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <td style="font-size: 0.75rem;">{{ Str::limit($order->items->first()?->product?->user?->name ?? 'N/A', 12) }}</td>
                                                @endif
                                                <td style="font-size: 0.75rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <strong>{{ Str::limit($order->order_number ?? 'N/A', 18) }}</strong>
                                                </td>
                                                <td style="font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($order->name, 15) }}</td>
                                                <td style="font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $order->phone }}</td>
                                                <td style="font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($order->address, 25) }}</td>
                                                <td style="font-size: 0.8rem;"><strong>{{ number_format($order->total, 0) }}</strong><br><small>EGP</small></td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                        ];
                                                        $color = $statusColors[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $paymentStatusColors = [
                                                            'pending' => 'warning',
                                                            'paid' => 'success',
                                                            'failed' => 'danger',
                                                        ];
                                                        $paymentColor = $paymentStatusColors[$order->payment_status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $paymentColor }}">{{ ucfirst($order->payment_status) }}</span>
                                                </td>
                                                <td>
                                                    @if($order->payment_method == 'cash')
                                                        <span class="badge bg-info">{{ __('Cash') }}</span>
                                                    @else
                                                        <span class="badge bg-primary">{{ __('Card') }}</span>
                                                    @endif
                                                </td>
                                                <td style="font-size: 0.75rem;">{{ $order->created_at->format('Y-m-d') }}<br><small>{{ $order->created_at->format('H:i') }}</small></td>
                                                <td style="font-size: 0.75rem;">{{ $order->updated_at->format('Y-m-d') }}<br><small>{{ $order->updated_at->format('H:i') }}</small></td>
                                                <td>
                                                    @if($order->returns->count() > 0)
                                                        <a href="{{ route('dashboard.returns.index', ['order_id' => $order->id]) }}" 
                                                           class="btn btn-sm btn-warning">
                                                            <i class="fas fa-undo"></i> {{ $order->returns->count() }}
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group-vertical btn-group-sm" role="group" style="flex-wrap: wrap;">
                                                        @can('orders-show')
                                                            <a href="{{ route('dashboard.orders.show', $order) }}"
                                                               class="btn btn-sm btn-warning mb-1" title="{{ __('View') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('orders-update')
                                                            @if($order->status == 'pending')
                                                                <form action="{{ route('dashboard.orders.accept', $order->id) }}" method="POST" class="d-inline mb-1">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-success" title="{{ __('Accept Order') }}"
                                                                            style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"
                                                                            onclick="return confirm('{{ __('Are you sure you want to accept this order? Status will be changed to processing.') }}')">
                                                                        <i class="fas fa-check"></i> {{ __('Accept') }}
                                                                    </button>
                                                                </form>
                                                            @elseif($order->status == 'processing')
                                                                <span class="badge bg-info" style="font-size: 0.65rem;">{{ __('Processing') }}</span>
                                                            @elseif($order->status == 'shipped')
                                                                <span class="badge bg-primary" style="font-size: 0.65rem;">{{ __('Shipped') }}</span>
                                                            @elseif($order->status == 'delivered')
                                                                <span class="badge bg-success" style="font-size: 0.65rem;">{{ __('Delivered') }}</span>
                                                            @elseif($order->status == 'completed')
                                                                <span class="badge bg-success" style="font-size: 0.65rem;">{{ __('Completed') }}</span>
                                                            @elseif($order->status == 'cancelled')
                                                                <span class="badge bg-danger" style="font-size: 0.65rem;">{{ __('Cancelled') }}</span>
                                                            @endif
                                                            <a href="{{ route('dashboard.orders.print-label', $order->id) }}" 
                                                               target="_blank"
                                                               class="btn btn-sm btn-secondary mb-1" title="{{ __('Print Label') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        @endcan
                                                        @can('orders-delete')
                                                            <button type="button" class="btn btn-sm btn-danger delete-country-btn mb-1"
                                                                    data-id="{{ $order->id }}" title="{{ __('Delete') }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->hasRole('admin') ? '15' : '14' }}" class="text-center">
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-info-circle"></i> {{ __('No orders found matching your criteria.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 pt-2 border-top" style="padding: 8px 15px; direction: ltr; background-color: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="text-muted mb-2 mb-md-0" style="font-size: 0.8rem;">
                                        <i class="fas fa-info-circle"></i> {{ __('Showing') }} <strong>{{ $data->firstItem() ?? 0 }}</strong> {{ __('to') }} <strong>{{ $data->lastItem() ?? 0 }}</strong> {{ __('of') }} <strong>{{ $data->total() }}</strong> {{ __('orders') }}
                                    </div>
                                    <div>
                                        {!! $data->withQueryString()->links('pagination::bootstrap-5', ['class' => 'pagination-sm mb-0', 'list_classes' => ['justify-content-end']]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--end main wrapper-->
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-country-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: "{{ __('Do you want to delete this item') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DC143C',
                        cancelButtonColor: '#696969',
                        cancelButtonText: "{{ __('Cancel') }}",
                        confirmButtonText: '{{ __('Yes, delete it!') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = document.createElement('form');
                            form.action = '{{ url('/dashboard/orders') }}/' + id;
                            form.method = 'POST';
                            form.style.display = 'none';

                            let csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';

                            let methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush

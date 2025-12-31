@extends('dashboard.layouts.app')
@section('title', __('Orders Management'))

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
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
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

                            <div class="table-responsive text-center">
                                <table id="example2" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Product') }}</th>
                                            @if(auth()->user()->hasRole('admin'))
                                                <th>{{ __('Vendor') }}</th>
                                            @endif
                                            <th>{{ __('Order #') }}</th>
                                            <th>{{ __('Name Buyer') }}</th>
                                            <th>{{ __('Phone Buyer') }}</th>
                                            <th>{{ __('Address Buyer') }}</th>
                                            <th>{{ __('Total Price') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Payment Status') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Order Date') }}</th>
                                            <th>{{ __('Updated Date') }}</th>
                                            <th>{{ __('Returns') }}</th>
                                            <th>{{ __('Actions') }}</th>
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
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-secondary">No Image</span>
                                                    @endif
                                                </td>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <td>{{ $order->items->first()?->product?->user?->name ?? 'N/A' }}</td>
                                                @endif
                                                <td>
                                                    <strong>{{ $order->order_number ?? 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $order->name }}</td>
                                                <td>{{ $order->phone }}</td>
                                                <td>{{ Str::limit($order->address, 30) }}</td>
                                                <td><strong>{{ number_format($order->total, 2) }} EGP</strong></td>
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
                                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                                <td>{{ $order->updated_at->format('Y-m-d H:i') }}</td>
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
                                                    <div class="btn-group" role="group">
                                                        @can('orders-show')
                                                            <a href="{{ route('dashboard.orders.show', $order) }}"
                                                               class="btn btn-sm btn-warning" title="{{ __('View') }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('orders-update')
                                                            <a href="{{ route('dashboard.orders.edit', $order) }}"
                                                               class="btn btn-sm btn-info" title="{{ __('Edit') }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('dashboard.orders.print-label', $order->id) }}" 
                                                               target="_blank"
                                                               class="btn btn-sm btn-secondary" title="{{ __('Print Label') }}">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        @endcan
                                                        @can('orders-delete')
                                                            <button type="button" class="btn btn-sm btn-danger delete-country-btn"
                                                                    data-id="{{ $order->id }}" title="{{ __('Delete') }}">
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
                                <div style="padding:5px;direction: ltr;">
                                    {!! $data->withQueryString()->links('pagination::bootstrap-5') !!}
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

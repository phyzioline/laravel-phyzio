@extends('dashboard.layouts.app')
@section('title', __('Manage Inventory'))

@push('styles')
<style>
    .filters-bar {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 24px;
        border: 1px solid #e0e0e0;
    }
    .quick-filters .btn {
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 6px;
        border: 1px solid #e7e7e7;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .stat-card h6 {
        color: #666;
        font-size: 13px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: 600;
        color: #333;
    }
    .inventory-table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    .stock-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .stock-in { background: #d4edda; color: #155724; }
    .stock-low { background: #fff3cd; color: #856404; }
    .stock-out { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{ __('Manage Inventory') }}</h4>
            <div>
                <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> {{ __('Add Product') }}
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <h6>{{ __('Total Products') }}</h6>
                <div class="value">{{ $stats['total_products'] }}</div>
            </div>
            <div class="stat-card">
                <h6>{{ __('Active Products') }}</h6>
                <div class="value text-success">{{ $stats['active_products'] }}</div>
            </div>
            <div class="stat-card">
                <h6>{{ __('Low Stock') }}</h6>
                <div class="value text-warning">{{ $stats['low_stock'] }}</div>
            </div>
            <div class="stat-card">
                <h6>{{ __('Out of Stock') }}</h6>
                <div class="value text-danger">{{ $stats['out_of_stock'] }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="filters-bar">
            <!-- Quick Filters -->
            <div class="quick-filters mb-3">
                <h6 class="mb-2">{{ __('Quick Filters') }}</h6>
                <a href="{{ route('dashboard.inventory.manage') }}" class="btn btn-sm btn-outline-primary {{ !request('stock_status') ? 'active' : '' }}">
                    {{ __('All Stock') }} ({{ $stats['total_products'] }})
                </a>
                <a href="{{ route('dashboard.inventory.manage', ['stock_status' => 'in_stock']) }}" class="btn btn-sm btn-outline-success {{ request('stock_status') == 'in_stock' ? 'active' : '' }}">
                    {{ __('In Stock') }}
                </a>
                <a href="{{ route('dashboard.inventory.manage', ['stock_status' => 'low_stock']) }}" class="btn btn-sm btn-outline-warning {{ request('stock_status') == 'low_stock' ? 'active' : '' }}">
                    {{ __('Low Stock') }} ({{ $stats['low_stock'] }})
                </a>
                <a href="{{ route('dashboard.inventory.manage', ['stock_status' => 'out_of_stock']) }}" class="btn btn-sm btn-outline-danger {{ request('stock_status') == 'out_of_stock' ? 'active' : '' }}">
                    {{ __('Out of Stock') }} ({{ $stats['out_of_stock'] }})
                </a>
                <a href="{{ route('dashboard.inventory.manage', ['listing_status' => 'active']) }}" class="btn btn-sm btn-outline-info {{ request('listing_status') == 'active' ? 'active' : '' }}">
                    {{ __('Active Listings') }}
                </a>
            </div>

            <!-- Search & Filters Form -->
            <form method="GET" action="{{ route('dashboard.inventory.manage') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search SKU, Title, ASIN') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->{'name_' . app()->getLocale()} }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="listing_status" class="form-select">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="active" {{ request('listing_status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="inactive" {{ request('listing_status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                    <a href="{{ route('dashboard.inventory.manage') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>

        <!-- Inventory Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive inventory-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Product Name & SKU') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Available') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Last Updated') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->productImages->first())
                                        <img src="{{ asset($product->productImages->first()->image) }}" alt="{{ $product->{'product_name_' . app()->getLocale()} }}">
                                    @else
                                        <div class="bg-light" style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $product->{'product_name_' . app()->getLocale()} }}</div>
                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                </td>
                                <td>{{ $product->category->{'name_' . app()->getLocale()} ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $product->amount == 0 ? 'stock-out' : ($product->amount <= 10 ? 'stock-low' : 'stock-in') }}">
                                        {{ $product->amount }}
                                    </span>
                                </td>
                                <td>{{ $product->product_price }} {{ __('EGP') }}</td>
                                <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-info" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateStockModal{{ $product->id }}" title="{{ __('Update Stock') }}">
                                            <i class="bi bi-box-seam"></i>
                                        </button>
                                    </div>

                                    <!-- Update Stock Modal -->
                                    <div class="modal fade" id="updateStockModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('dashboard.inventory.update-stock') }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Update Stock') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>{{ $product->{'product_name_' . app()->getLocale()} }}</strong></p>
                                                        <p class="text-muted">{{ __('Current stock') }}: {{ $product->amount }}</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('New Stock Quantity') }}</label>
                                                            <input type="number" name="amount" class="form-control" value="{{ $product->amount }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="submit" class="btn btn-primary">{{ __('Update Stock') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">{{ __('No products found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

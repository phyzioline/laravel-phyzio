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
                                        <div style="width: 60px; height: 60px; overflow: hidden; border-radius: 4px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; background: #fff;">
                                            <img src="{{ asset($product->productImages->first()->image) }}" 
                                                 alt="{{ $product->{'product_name_' . app()->getLocale()} }}"
                                                 style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    @else
                                        <div class="bg-light" style="width:60px;height:60px;display:flex;align-items:center;justify-content:center; border-radius: 4px; border: 1px solid #eee;">
                                            <i class="bi bi-image text-muted"></i>
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
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" 
                                               class="form-control form-control-sm stock-quantity-input" 
                                               value="{{ $product->amount }}" 
                                               min="0" 
                                               data-product-id="{{ $product->id }}"
                                               data-original-value="{{ $product->amount }}"
                                               style="width: 80px; text-align: center;"
                                               id="stock-input-{{ $product->id }}">
                                        <button type="button" 
                                                class="btn btn-sm btn-success update-stock-btn" 
                                                data-product-id="{{ $product->id }}"
                                                style="display: none;"
                                                title="{{ __('Update') }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-secondary cancel-stock-btn" 
                                                data-product-id="{{ $product->id }}"
                                                style="display: none;"
                                                title="{{ __('Cancel') }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <small class="stock-status-badge d-block mt-1 {{ $product->amount == 0 ? 'text-danger' : ($product->amount <= 10 ? 'text-warning' : 'text-success') }}">
                                        @if($product->amount == 0)
                                            {{ __('Out of Stock') }}
                                        @elseif($product->amount <= 10)
                                            {{ __('Low Stock') }}
                                        @else
                                            {{ __('In Stock') }}
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $product->product_price }} {{ __('EGP') }}</td>
                                <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dashboard.products.edit', $product) }}" class="btn btn-sm btn-info" title="{{ __('Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Show update buttons when quantity input changes
    $(document).on('input', '.stock-quantity-input', function() {
        const productId = $(this).data('product-id');
        const originalValue = $(this).data('original-value');
        const currentValue = $(this).val();
        
        if (currentValue != originalValue) {
            $('#stock-input-' + productId).addClass('border-warning');
            $('.update-stock-btn[data-product-id="' + productId + '"]').show();
            $('.cancel-stock-btn[data-product-id="' + productId + '"]').show();
        } else {
            $('#stock-input-' + productId).removeClass('border-warning');
            $('.update-stock-btn[data-product-id="' + productId + '"]').hide();
            $('.cancel-stock-btn[data-product-id="' + productId + '"]').hide();
        }
    });

    // Update stock on button click
    $(document).on('click', '.update-stock-btn', function() {
        const productId = $(this).data('product-id');
        const input = $('#stock-input-' + productId);
        const newAmount = parseInt(input.val());
        
        if (newAmount < 0) {
            alert('{{ __("Quantity cannot be negative") }}');
            return;
        }

        // Disable input and show loading
        input.prop('disabled', true);
        $(this).prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

        $.ajax({
            url: '{{ route("dashboard.inventory.update-stock") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                amount: newAmount
            },
            success: function(response) {
                // Update original value
                input.data('original-value', newAmount);
                input.removeClass('border-warning').addClass('border-success');
                
                // Hide buttons
                $('.update-stock-btn[data-product-id="' + productId + '"]').hide();
                $('.cancel-stock-btn[data-product-id="' + productId + '"]').hide();
                
                // Update status badge
                const statusBadge = input.closest('td').find('.stock-status-badge');
                if (newAmount == 0) {
                    statusBadge.removeClass('text-warning text-success').addClass('text-danger').text('{{ __("Out of Stock") }}');
                } else if (newAmount <= 10) {
                    statusBadge.removeClass('text-danger text-success').addClass('text-warning').text('{{ __("Low Stock") }}');
                } else {
                    statusBadge.removeClass('text-danger text-warning').addClass('text-success').text('{{ __("In Stock") }}');
                }
                
                // Re-enable input
                input.prop('disabled', false);
                $('.update-stock-btn[data-product-id="' + productId + '"]').prop('disabled', false).html('<i class="bi bi-check"></i>');
                
                // Show success message
                setTimeout(function() {
                    input.removeClass('border-success');
                }, 2000);
                
                // Show toast notification if available
                if (typeof toastr !== 'undefined') {
                    toastr.success('{{ __("Stock updated successfully") }}');
                }
            },
            error: function(xhr) {
                alert('{{ __("Error updating stock. Please try again.") }}');
                input.prop('disabled', false);
                $('.update-stock-btn[data-product-id="' + productId + '"]').prop('disabled', false).html('<i class="bi bi-check"></i>');
            }
        });
    });

    // Cancel changes
    $(document).on('click', '.cancel-stock-btn', function() {
        const productId = $(this).data('product-id');
        const input = $('#stock-input-' + productId);
        const originalValue = input.data('original-value');
        
        input.val(originalValue);
        input.removeClass('border-warning border-success');
        $('.update-stock-btn[data-product-id="' + productId + '"]').hide();
        $('.cancel-stock-btn[data-product-id="' + productId + '"]').hide();
    });

    // Update on Enter key
    $(document).on('keypress', '.stock-quantity-input', function(e) {
        if (e.which === 13) { // Enter key
            const productId = $(this).data('product-id');
            $('.update-stock-btn[data-product-id="' + productId + '"]').click();
        }
    });
});
</script>
@endpush

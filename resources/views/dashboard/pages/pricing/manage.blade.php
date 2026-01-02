@extends('dashboard.layouts.app')
@section('title', __('Manage Pricing'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{ __('Manage Pricing') }}</h4>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">{{ __('Total Products') }}</h6>
                        <h3>{{ $stats['total_products'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">{{ __('Average Price') }}</h6>
                        <h3>{{ $stats['avg_price'] }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">{{ __('Highest Price') }}</h6>
                        <h3>{{ $stats['highest_price'] }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">{{ __('Lowest Price') }}</h6>
                        <h3>{{ $stats['lowest_price'] }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or SKU') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="min_price" class="form-control" placeholder="{{ __('Min Price') }}" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="max_price" class="form-control" placeholder="{{ __('Max Price') }}" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('SKU') }}</th>
                                <th>{{ __('Current Price') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->productImages->first())
                                        <img src="{{ asset($product->productImages->first()->image) }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;" alt="">
                                    @else
                                        <div class="bg-light" style="width:50px;height:50px;border-radius:4px;"></div>
                                    @endif
                                </td>
                                <td>{{ $product->{'product_name_' . app()->getLocale()} }}</td>
                                <td>{{ $product->sku }}</td>
                                <td><strong>{{ $product->product_price }}</strong> {{ __('EGP') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary update-price-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#priceModal"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->{'product_name_' . app()->getLocale()} }}"
                                            data-current-price="{{ $product->product_price }}">
                                        <i class="bi bi-pencil"></i> {{ __('Update Price') }}
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No products found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Single Price Update Modal (Used for all products) -->
<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.pricing.update-price') }}">
                @csrf
                <input type="hidden" name="product_id" id="modal-product-id" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceModalLabel">{{ __('Update Price') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong id="modal-product-name"></strong></p>
                    <p class="text-muted">{{ __('Current price') }}: <span id="modal-current-price"></span> {{ __('EGP') }}</p>
                    <div class="mb-3">
                        <label class="form-label">{{ __('New Price (EGP)') }}</label>
                        <input type="number" name="price" id="modal-new-price" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update Price') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the single modal
    var priceModal = document.getElementById('priceModal');
    
    // Handle modal show event to populate it with product data
    if (priceModal) {
        priceModal.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            var button = event.relatedTarget;
            
            // Get product data from button data attributes
            var productId = button.getAttribute('data-product-id');
            var productName = button.getAttribute('data-product-name');
            var currentPrice = button.getAttribute('data-current-price');
            
            // Populate modal fields
            document.getElementById('modal-product-id').value = productId;
            document.getElementById('modal-product-name').textContent = productName;
            document.getElementById('modal-current-price').textContent = currentPrice;
            document.getElementById('modal-new-price').value = currentPrice;
        });
    }
});
</script>
@endpush

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
                                            data-bs-target="#priceModal{{ $product->id }}"
                                            data-product-id="{{ $product->id }}">
                                        <i class="bi bi-pencil"></i> {{ __('Update Price') }}
                                    </button>

                                    <!-- Price Modal -->
                                    <div class="modal fade" id="priceModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('dashboard.pricing.update-price') }}">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Update Price') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>{{ $product->{'product_name_' . app()->getLocale()} }}</strong></p>
                                                        <p class="text-muted">{{ __('Current price') }}: {{ $product->product_price }} {{ __('EGP') }}</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">{{ __('New Price (EGP)') }}</label>
                                                            <input type="number" name="price" class="form-control" value="{{ $product->product_price }}" step="0.01" min="0" required>
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
@endsection

@push('styles')
<style>
    /* Position modal near the clicked button */
    .modal-dialog.positioned {
        position: fixed !important;
        margin: 0 !important;
        top: auto !important;
        left: auto !important;
        transform: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal show event to position it near the clicked button without scrolling
    document.querySelectorAll('[id^="priceModal"]').forEach(function(modalElement) {
        modalElement.addEventListener('show.bs.modal', function(event) {
            // Get the button that triggered the modal
            var button = event.relatedTarget;
            
            // Small delay to ensure modal is rendered
            setTimeout(function() {
                // Get the modal dialog element
                var modalDialog = modalElement.querySelector('.modal-dialog');
                
                if (modalDialog && button) {
                    // Calculate button position relative to viewport
                    var buttonRect = button.getBoundingClientRect();
                    
                    // Calculate modal position (centered horizontally, above button vertically)
                    var modalWidth = modalDialog.offsetWidth || 500; // Default modal width
                    var modalHeight = modalDialog.offsetHeight || 300; // Default modal height
                    var viewportWidth = window.innerWidth;
                    var viewportHeight = window.innerHeight;
                    
                    // Position horizontally centered relative to button
                    var left = buttonRect.left + (buttonRect.width / 2) - (modalWidth / 2);
                    
                    // Ensure modal doesn't go off screen horizontally
                    if (left < 20) left = 20;
                    if (left + modalWidth > viewportWidth - 20) {
                        left = viewportWidth - modalWidth - 20;
                    }
                    
                    // Position vertically above button with some spacing
                    var top = buttonRect.top - modalHeight - 20;
                    
                    // If not enough space above, position below button
                    if (top < 20) {
                        top = buttonRect.bottom + 20;
                    }
                    
                    // Ensure modal doesn't go off screen vertically
                    if (top + modalHeight > viewportHeight - 20) {
                        top = viewportHeight - modalHeight - 20;
                    }
                    
                    // Apply positioning
                    modalDialog.classList.add('positioned');
                    modalDialog.style.left = left + 'px';
                    modalDialog.style.top = top + 'px';
                    modalDialog.style.maxWidth = '500px';
                }
            }, 10);
        });
        
        // Reset positioning when modal is hidden
        modalElement.addEventListener('hidden.bs.modal', function() {
            var modalDialog = modalElement.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.classList.remove('positioned');
                modalDialog.style.left = '';
                modalDialog.style.top = '';
            }
        });
    });
});
</script>
@endpush

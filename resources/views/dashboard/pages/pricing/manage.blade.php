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
                                <td>
                                    <div class="price-editor" data-product-id="{{ $product->id }}">
                                        <!-- Display Mode -->
                                        <div class="price-display d-flex align-items-center gap-2">
                                            <strong class="price-text">{{ $product->product_price }}</strong>
                                            <span>{{ __('EGP') }}</span>
                                            <button type="button" class="btn btn-sm btn-link p-0 edit-price-btn" title="{{ __('Edit Price') }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                        <!-- Edit Mode -->
                                        <form class="price-edit-form d-none d-flex align-items-center gap-2" method="POST" action="{{ route('dashboard.pricing.update-price') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="number" 
                                                   name="price" 
                                                   class="form-control form-control-sm price-input" 
                                                   value="{{ $product->product_price }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required 
                                                   style="width: 100px;">
                                            <span>{{ __('EGP') }}</span>
                                            <button type="submit" class="btn btn-sm btn-success save-price-btn" title="{{ __('Save') }}">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary cancel-price-btn" title="{{ __('Cancel') }}">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('No products found') }}</td>
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
    .price-editor {
        min-width: 200px;
    }
    .price-display, .price-edit-form {
        align-items: center;
    }
    .price-input {
        width: 100px !important;
    }
    .edit-price-btn, .save-price-btn, .cancel-price-btn {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button click - switch to edit mode
    document.querySelectorAll('.edit-price-btn').forEach(function(editBtn) {
        editBtn.addEventListener('click', function() {
            var priceEditor = this.closest('.price-editor');
            var displayDiv = priceEditor.querySelector('.price-display');
            var editForm = priceEditor.querySelector('.price-edit-form');
            var originalPrice = priceEditor.querySelector('.price-text').textContent;
            
            // Store original price for cancel
            editForm.dataset.originalPrice = originalPrice;
            
            // Switch to edit mode
            displayDiv.classList.add('d-none');
            editForm.classList.remove('d-none');
            editForm.querySelector('.price-input').focus();
            editForm.querySelector('.price-input').select();
        });
    });
    
    // Handle cancel button click - switch back to display mode
    document.querySelectorAll('.cancel-price-btn').forEach(function(cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var priceEditor = this.closest('.price-editor');
            var displayDiv = priceEditor.querySelector('.price-display');
            var editForm = priceEditor.querySelector('.price-edit-form');
            var originalPrice = editForm.dataset.originalPrice;
            
            // Reset input to original price
            editForm.querySelector('.price-input').value = originalPrice;
            
            // Switch back to display mode
            editForm.classList.add('d-none');
            displayDiv.classList.remove('d-none');
        });
    });
    
    // Handle form submission - update price via AJAX
    document.querySelectorAll('.price-edit-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var priceEditor = this.closest('.price-editor');
            var displayDiv = priceEditor.querySelector('.price-display');
            var priceText = displayDiv.querySelector('.price-text');
            var formData = new FormData(this);
            var submitBtn = this.querySelector('.save-price-btn');
            var originalBtnHtml = submitBtn.innerHTML;
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            
            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update displayed price
                    priceText.textContent = data.price || formData.get('price');
                    
                    // Switch back to display mode
                    this.classList.add('d-none');
                    displayDiv.classList.remove('d-none');
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message || '{{ __("Price updated successfully") }}');
                    }
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __("Failed to update price") }}');
                } else {
                    alert('{{ __("Failed to update price") }}');
                }
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            });
        });
    });
});
</script>
@endpush

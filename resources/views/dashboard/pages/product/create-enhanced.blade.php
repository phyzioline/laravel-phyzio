@extends('dashboard.layouts.app')
@section('title', __('Add Product'))

@push('styles')
<style>
    .product-form-tabs .nav-link {
        color: #666;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        font-weight: 500;
    }
    .product-form-tabs .nav-link:hover {
        border-bottom-color: #dee2e6;
    }
    .product-form-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom-color: #0d6efd;
        background: transparent;
    }
    .tab-content {
        min-height: 400px;
    }
    .form-section {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .form-section h6 {
        margin-bottom: 15px;
        color: #495057;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 mb-0">{{ __('Add Product') }}</h1>
                    <p class="text-muted">{{ __('Fill in all required information to create your product listing.') }}</p>
                </div>
            </div>

            <form method="post" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data" id="product-form">
                @csrf
                
                <!-- Product Overview (if copying) -->
                @if(isset($sourceProduct) && $sourceProduct)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Copying from: :name', ['name' => $sourceProduct->product_name_en]) }}
                    </div>
                @endif

                <!-- Tab Navigation -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs product-form-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>{{ __('Product Details') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                    <i class="fas fa-images me-2"></i>{{ __('Images') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="variations-tab" data-bs-toggle="tab" data-bs-target="#variations" type="button" role="tab">
                                    <i class="fas fa-layer-group me-2"></i>{{ __('Variations') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="offer-tab" data-bs-toggle="tab" data-bs-target="#offer" type="button" role="tab">
                                    <i class="fas fa-tag me-2"></i>{{ __('Offer') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="safety-tab" data-bs-toggle="tab" data-bs-target="#safety" type="button" role="tab">
                                    <i class="fas fa-shield-alt me-2"></i>{{ __('Safety & Compliance') }}
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content p-4" id="productTabsContent">
                            <!-- Product Details Tab -->
                            <div class="tab-pane fade show active" id="details" role="tabpanel">
                                @include('dashboard.pages.product.tabs.details', [
                                    'categories' => $categories,
                                    'sub_categories' => $sub_categories,
                                    'tags' => $tags
                                ])
                            </div>

                            <!-- Images Tab -->
                            <div class="tab-pane fade" id="images" role="tabpanel">
                                @include('dashboard.pages.product.tabs.images')
                            </div>

                            <!-- Variations Tab -->
                            <div class="tab-pane fade" id="variations" role="tabpanel">
                                @include('dashboard.pages.product.tabs.variations')
                            </div>

                            <!-- Offer Tab -->
                            <div class="tab-pane fade" id="offer" role="tabpanel">
                                @include('dashboard.pages.product.tabs.offer')
                            </div>

                            <!-- Safety & Compliance Tab -->
                            <div class="tab-pane fade" id="safety" role="tabpanel">
                                @include('dashboard.pages.product.tabs.safety')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('dashboard.products.list') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <div>
                                <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-save me-2"></i>{{ __('Save as Draft') }}
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>{{ __('Save and Finish') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Prevent scroll to top on form submission
    $('#product-form').on('submit', function(e) {
        var scrollPosition = $(window).scrollTop();
        sessionStorage.setItem('productFormScrollPosition', scrollPosition);
    });

    // Restore scroll position
    var savedScrollPosition = sessionStorage.getItem('productFormScrollPosition');
    if (savedScrollPosition !== null) {
        sessionStorage.removeItem('productFormScrollPosition');
        setTimeout(function() {
            $(window).scrollTop(savedScrollPosition);
        }, 100);
    }

    // Form validation before submit
    $('#product-form').on('submit', function(e) {
        const activeTab = $('.nav-link.active').attr('data-bs-target');
        
        // Basic validation - check required fields in active tab
        let isValid = true;
        $(activeTab + ' [required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('{{ __("Please fill in all required fields in the current tab.") }}');
            return false;
        }
    });
});
</script>
@endpush


@extends('dashboard.layouts.app')
@section('title', __('List Your Products'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 mb-2">{{ __('List Your Products') }}</h1>
                    <p class="text-muted">{{ __('Select an option to get started.') }}</p>
                </div>
            </div>

            <!-- Listing Options -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row g-3" id="listing-options">
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="search" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-search fa-2x mb-2 text-primary"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Search') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="image" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-image fa-2x mb-2 text-info"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Product Image') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="product-ids" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-barcode fa-2x mb-2 text-success"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Product IDs') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="web-url" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-globe fa-2x mb-2 text-warning"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Web URL') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="blank" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-file-alt fa-2x mb-2 text-secondary"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Blank Form') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <div class="listing-option-card text-center p-3 border rounded cursor-pointer" data-option="spreadsheet" style="cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-table fa-2x mb-2 text-danger"></i>
                                        <p class="mb-0 fw-semibold">{{ __('Spreadsheet') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Section (Default Active) -->
            <div class="row mb-4" id="search-section">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Search your catalog or Phyzioline catalog for a listing to sell or copy.') }}</h5>
                            <form id="product-search-form">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="product-search-input" 
                                           placeholder="{{ __('Enter product title, description, or keywords') }}" 
                                           autocomplete="off">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search me-2"></i>{{ __('Search') }}
                                    </button>
                                </div>
                            </form>
                            <div id="search-results" class="mt-4" style="display: none;">
                                <h6 class="mb-3">{{ __('Search Results') }}</h6>
                                <div id="search-results-list" class="list-group"></div>
                                <div class="mt-3">
                                    <a href="{{ route('dashboard.products.create') }}" class="btn btn-link">
                                        {{ __('Listing not found? Create a new listing') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Option Sections (Hidden by default) -->
            <div class="row mb-4" id="image-section" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Add Product by Image') }}</h5>
                            <p class="text-muted">{{ __('Upload a product image and we\'ll help you create a listing.') }}</p>
                            <form action="{{ route('dashboard.products.create') }}" method="GET">
                                <input type="hidden" name="method" value="image">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>{{ __('Upload Product Image') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4" id="product-ids-section" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Add Product by IDs') }}</h5>
                            <p class="text-muted">{{ __('Enter product identifiers (UPC, EAN, ISBN, etc.) to find existing products.') }}</p>
                            <form action="{{ route('dashboard.products.create') }}" method="GET">
                                <input type="hidden" name="method" value="product-ids">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-barcode me-2"></i>{{ __('Enter Product IDs') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4" id="web-url-section" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Add Product by Web URL') }}</h5>
                            <p class="text-muted">{{ __('Enter a product URL and we\'ll extract product information.') }}</p>
                            <form action="{{ route('dashboard.products.create') }}" method="GET">
                                <input type="hidden" name="method" value="web-url">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-globe me-2"></i>{{ __('Enter Web URL') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4" id="blank-section" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Create Blank Listing') }}</h5>
                            <p class="text-muted">{{ __('Start with a blank form to manually enter all product details.') }}</p>
                            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i>{{ __('Create Blank Listing') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4" id="spreadsheet-section" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('Import via Spreadsheet') }}</h5>
                            <p class="text-muted">{{ __('Upload a CSV or Excel file to import multiple products at once.') }}</p>
                            <a href="{{ route('dashboard.products.index') }}" class="btn btn-primary">
                                <i class="fas fa-table me-2"></i>{{ __('Go to Import') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Draft Listings -->
            @if($draftCount > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x text-info me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ __('Complete your listings') }}</h6>
                                    <p class="mb-0 text-muted">
                                        {{ __('You have :count unfinished listing(s) in Drafts.', ['count' => $draftCount]) }}
                                        <a href="{{ route('dashboard.products.index') }}?status=inactive" class="ms-2">
                                            {{ __('View my drafts') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
    .listing-option-card {
        transition: all 0.3s ease;
    }
    .listing-option-card:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .listing-option-card.active {
        background-color: #e7f1ff;
        border-color: #0d6efd !important;
        border-width: 2px;
    }
    .product-result-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .product-result-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let selectedOption = 'search';
    
    // Handle option selection
    $('.listing-option-card').on('click', function() {
        const option = $(this).data('option');
        selectedOption = option;
        
        // Update active state
        $('.listing-option-card').removeClass('active');
        $(this).addClass('active');
        
        // Show/hide sections
        $('[id$="-section"]').hide();
        $('#' + option + '-section').show();
    });
    
    // Set search as default active
    $('.listing-option-card[data-option="search"]').addClass('active');
    
    // Product search
    let searchTimeout;
    $('#product-search-form').on('submit', function(e) {
        e.preventDefault();
        performSearch();
    });
    
    $('#product-search-input').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                performSearch();
            }, 500);
        } else {
            $('#search-results').hide();
        }
    });
    
    function performSearch() {
        const query = $('#product-search-input').val().trim();
        
        if (!query) {
            $('#search-results').hide();
            return;
        }
        
        $.ajax({
            url: '{{ route("dashboard.products.search-catalog") }}',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                displaySearchResults(response.products);
            },
            error: function() {
                $('#search-results-list').html('<div class="alert alert-danger">{{ __("Error searching products. Please try again.") }}</div>');
                $('#search-results').show();
            }
        });
    }
    
    function displaySearchResults(products) {
        const resultsList = $('#search-results-list');
        resultsList.empty();
        
        if (products.length === 0) {
            resultsList.html('<div class="alert alert-info">{{ __("No products found.") }}</div>');
        } else {
            products.forEach(function(product) {
                const imageUrl = product.product_images && product.product_images.length > 0 
                    ? '{{ asset("") }}' + product.product_images[0].image 
                    : '{{ asset("dashboard/assets/images/no-image.png") }}';
                
                const item = `
                    <div class="list-group-item product-result-item" data-product-id="${product.id}">
                        <div class="d-flex align-items-center">
                            <img src="${imageUrl}" alt="${product.product_name_en}" 
                                 class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${product.product_name_en}</h6>
                                <small class="text-muted">SKU: ${product.sku} | ${product.category ? product.category.name_en : 'N/A'}</small>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-primary copy-product-btn" data-product-id="${product.id}">
                                    <i class="fas fa-copy me-1"></i>{{ __('Copy & Sell') }}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                resultsList.append(item);
            });
        }
        
        $('#search-results').show();
    }
    
    // Handle copy product
    $(document).on('click', '.copy-product-btn', function() {
        const productId = $(this).data('product-id');
        window.location.href = '{{ route("dashboard.products.create") }}?copy_from=' + productId;
    });
});
</script>
@endpush


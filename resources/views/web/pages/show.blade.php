@extends('web.layouts.app')

@section('title', 'Physical Therapy Products & Medical Equipment Shop | PhyzioLine')

@push('meta')
    <meta name="description" content="Shop high-quality physical therapy products, medical equipment, and rehabilitation supplies. Browse our extensive collection of professional physiotherapy tools and devices.">
    <meta name="keywords" content="physical therapy products, physiotherapy equipment, medical supplies, rehabilitation equipment, therapy products, medical devices">
    <meta property="og:title" content="Physical Therapy Products Shop | PhyzioLine">
    <meta property="og:description" content="Professional physical therapy equipment and medical supplies for therapists and clinics.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Physical Therapy Products Shop">
    <meta name="twitter:description" content="Professional physical therapy equipment and medical supplies.">
@endpush

@section('content')
<main>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            /* Hero Banner */
            .shop-hero-banner {
                background: linear-gradient(185deg, #02767F 0%, #04b8c4 100%);
                padding: 180px 0 40px;
                margin-bottom: 90px;
                position: relative;
                overflow: hidden;
            }
             .shop-hero-banner {
             padding: 180px 0 40px;         
        
            }   

            
            .shop-hero-banner::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
                opacity: 0.3;
            }
            
            .shop-hero-content {
                position: relative;
                z-index: 1;
                text-align: center;
                color: white;
            }
            
            .shop-hero-content h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 15px;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            }
            
            .shop-hero-content p {
                font-size: 1.2rem;
                opacity: 0.95;
            }
            
            /* Search Bar */
            .shop-search-bar {
                max-width: 800px;
                margin: 30px auto;
                position: relative;
            }
            
            .shop-search-form {
                display: flex;
                background: white;
                border-radius: 50px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .shop-search-input {
                flex: 1;
                padding: 18px 25px;
                border: none;
                font-size: 16px;
                outline: none;
            }
            
            .shop-search-btn {
                padding: 18px 40px;
                background: #02767F;
                color: white;
                border: none;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .shop-search-btn:hover {
                background: #04b8c4;
            }
            
            /* Category Icons Bar - Noon.com Style */
            .category-icons-bar {
                background: white;
                padding: 20px 0;
                margin-bottom: 30px;
                border-bottom: 2px solid #f0f0f0;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }
            
            .category-icons-container {
                display: flex;
                gap: 25px;
                padding: 0 20px;
                min-width: max-content;
            }
            
            .category-icon-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                min-width: 100px;
                cursor: pointer;
                transition: all 0.3s ease;
                padding: 10px;
                border-radius: 12px;
                text-decoration: none !important;
                color: #333 !important;
            }
            
            .category-icon-item:hover {
                background: #f8f9fa;
                transform: translateY(-3px);
            }
            
            .category-icon-item.active {
                background: linear-gradient(135deg, #02767F, #04b8c4);
                color: white !important;
            }
            
            .category-icon-circle {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: linear-gradient(135deg, #f0f7f8, #e0eff0);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }
            
            .category-icon-item:hover .category-icon-circle,
            .category-icon-item.active .category-icon-circle {
                background: white;
                border-color: #04b8c4;
                transform: scale(1.1);
            }
            
            .category-icon-item.active .category-icon-circle {
                background: rgba(255,255,255,0.2);
                border-color: white;
            }
            
            .category-icon-circle i {
                font-size: 32px;
                color: #02767F;
            }
            
            .category-icon-item.active .category-icon-circle i {
                color: white;
            }
            
            .category-icon-name {
                font-size: 13px;
                font-weight: 500;
                margin-top: 5px;
                line-height: 1.2;
            }
            

            .shop-hero-banner {
             position: relative;
              z-index: 1;
            }

             
/* Make web header transparent over hero */
header, 
.header-section, 
.navbar, 
.web-header {
    background: transparent !important;
    box-shadow: none !important;
    
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
}


            
            /* Product Grid - Noon.com Style */
            .products-section {
                padding: 20px 0 50px;
            }
            
            .products-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 25px;
                padding: 0 15px;
            }
            
            .products-count {
                font-size: 16px;
                color: #666;
            }
            
            .products-count strong {
                color: #02767F;
                font-weight: 700;
            }
            
            /* Product Card - Noon.com Style */
            .noon-product-card {
                background: white;
                border: 1px solid #e7e7e7;
                border-radius: 8px;
                overflow: hidden;
                transition: all 0.3s ease;
                height: 100%;
                display: flex;
                flex-direction: column;
                position: relative;
            }
            
            .noon-product-card:hover {
                box-shadow: 0 4px 20px rgba(4, 184, 196, 0.15);
                border-color: #04b8c4;
                transform: translateY(-5px);
            }
            
            .noon-product-image-wrapper {
                position: relative;
                width: 100%;
                height: 220px;
                overflow: hidden;
                background: #f8f9fa;
            }
            
            .noon-product-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            
            .noon-product-card:hover .noon-product-image {
                transform: scale(1.05);
            }
            
            .noon-product-badge {
                position: absolute;
                top: 10px;
                left: 10px;
                background: #ff6b35;
                color: white;
                padding: 5px 12px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                z-index: 2;
            }
            
            .noon-product-actions {
                position: absolute;
                top: 10px;
                right: 10px;
                display: flex;
                flex-direction: column;
                gap: 8px;
                z-index: 2;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .noon-product-card:hover .noon-product-actions {
                opacity: 1;
            }
            
            .noon-action-btn {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                border: none;
                background: white;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .noon-action-btn:hover {
                background: #02767F;
                color: white;
                transform: scale(1.1);
            }
            
            .noon-action-btn.favorite.active {
                background: #ff4757;
                color: white;
            }
            
            .noon-action-btn.compare.active {
                background: #04b8c4;
                color: white;
            }

            .noon-action-btn.compare {
                box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
            }
            
            .noon-product-info {
                padding: 15px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            
            .noon-product-title {
                font-size: 14px;
                font-weight: 500;
                color: #333;
                margin-bottom: 10px;
                line-height: 1.4;
                min-height: 40px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-decoration: none;
            }
            
            .noon-product-title:hover {
                color: #02767F;
            }
            
            .noon-product-price {
                font-size: 18px;
                font-weight: 700;
                color: #02767F;
                margin-bottom: 10px;
            }
            
            .noon-product-rating {
                display: flex;
                align-items: center;
                gap: 3px;
                margin-bottom: 12px;
            }
            
            .noon-product-rating i {
                color: #ffc107;
                font-size: 14px;
            }
            
            .noon-product-rating i.inactive {
                color: #e0e0e0;
            }
            
            .noon-product-footer {
                display: flex;
                gap: 8px;
                margin-top: auto;
            }
            
            .noon-btn-add-cart {
                flex: 1;
                padding: 10px;
                background: #02767F;
                color: white;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
            }
            
            .noon-btn-add-cart:hover {
                background: #04b8c4;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(4, 184, 196, 0.3);
            }
            
            .noon-btn-buy-now {
                padding: 10px 20px;
                background: linear-gradient(135deg, #ff6b35, #f7931e);
                color: white;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s ease;
                white-space: nowrap;
            }
            
            .noon-btn-buy-now:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .shop-hero-content h1 {
                    font-size: 1.8rem;
                }
                
                .category-icons-container {
                    gap: 15px;
                }
                
                .category-icon-item {
                    min-width: 80px;
                }
                
                .category-icon-circle {
                    width: 60px;
                    height: 60px;
                }
                
                .category-icon-circle i {
                    font-size: 24px;
                }
                
                .noon-product-image-wrapper {
                    height: 180px;
                }
                
                .noon-product-actions {
                    opacity: 1;
                }
            }
            
            /* Pagination */
            .pagination-wrapper {
                margin-top: 40px;
                display: flex;
                justify-content: center;
            }
        </style>
    @endpush

    <!-- Hero Banner -->
    <section class="shop-hero-banner">
        <div class="container">
            <div class="shop-hero-content">
                <h1>Physical Therapy Products Shop</h1>
                <p>Discover Professional Medical Equipment & Rehabilitation Supplies</p>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="shop-search-bar">
        <div class="container">
            <form action="{{ route('web.shop.search') }}" method="GET" class="shop-search-form">
                <input type="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search for products, categories..." 
                       class="shop-search-input" />
                <button type="submit" class="shop-search-btn">
                    <i class="las la-search"></i> Search
                </button>
            </form>
        </div>
    </section>

    <!-- Category Icons Bar -->
    <section class="category-icons-bar">
        <div class="category-icons-container">
            <a href="{{ route('show') }}" class="category-icon-item {{ !request()->has('category') ? 'active' : '' }}">
                <div class="category-icon-circle">
                    <i class="las la-border-all"></i>
                </div>
                <span class="category-icon-name">All Products</span>
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('show', ['category' => $category->id]) }}" 
                   class="category-icon-item {{ request('category') == $category->id ? 'active' : '' }}"
                   data-category-id="{{ $category->id }}">
                        <div class="category-icon-circle">
                            @php
                                $catName = strtolower($category->{'name_en'});
                            @endphp
                            @if(str_contains($catName, 'ortho'))
                                <i class="las la-bone"></i>
                            @elseif(str_contains($catName, 'neuro') || str_contains($catName, 'brain'))
                                <i class="las la-brain"></i>
                            @elseif(str_contains($catName, 'cardio') || str_contains($catName, 'heart'))
                                <i class="las la-heartbeat"></i>
                            @elseif(str_contains($catName, 'spine') || str_contains($catName, 'back'))
                                <i class="las la-allergies"></i> <!-- Closest spine-like or vertebrae visual if available, or universal access -->
                            @elseif(str_contains($catName, 'sport'))
                                <i class="las la-dumbbell"></i>
                            @elseif(str_contains($catName, 'pediatric') || str_contains($catName, 'child'))
                                <i class="las la-baby"></i>
                            @elseif(str_contains($catName, 'electro') || str_contains($catName, 'tens'))
                                <i class="las la-bolt"></i>
                            @elseif(str_contains($catName, 'manual'))
                                <i class="las la-hand-holding-heart"></i>
                            @elseif(str_contains($catName, 'exercise') || str_contains($catName, 'gym'))
                                <i class="las la-running"></i>
                            @else
                                <i class="las la-briefcase-medical"></i>
                            @endif
                        </div>
                    <span class="category-icon-name">{{ $category->{'name_' . app()->getLocale()} }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <div class="products-count">
                    We found <strong>{{ $count_product ?? $products->total() }}</strong> physical therapy products for you
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
                        <div class="noon-product-card">
                            <!-- Product Image -->
                            <div class="noon-product-image-wrapper">
                                <a href="{{ route('product.show', $product->id) }}">
                                    <img src="{{ asset($product->productImages->first()?->image ?? 'web/assets/images/default-product.png') }}" 
                                         alt="{{ $product->{'product_name_' . app()->getLocale()} }}" 
                                         class="noon-product-image" />
                                </a>
                                
                                <!-- Best Seller Badge -->
                                <div class="noon-product-badge">Best Seller</div>
                                
                                <!-- Action Buttons -->
                                <div class="noon-product-actions">
                                    <button type="button" 
                                            class="noon-action-btn favorite {{ $product->favorite && $product->favorite->favorite_type ? 'active' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="Add to Favorites">
                                        <i class="fa-heart {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"></i>
                                    </button>
                                    <button type="button" 
                                            class="noon-action-btn compare"
                                            data-product-id="{{ $product->id }}"
                                            title="Compare">
                                        <i class="las la-balance-scale"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="noon-product-info">
                                <a href="{{ route('product.show', $product->id) }}" class="noon-product-title">
                                    {{ $product->{'product_name_' . app()->getLocale()} }}
                                </a>
                                
                                <div class="noon-product-price">{{ number_format($product->product_price, 2) }} EGP</div>
                                
                                <div class="noon-product-rating">
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star active"></i>
                                    <i class="las la-star inactive"></i>
                                </div>
                                
                                <div class="noon-product-footer">
                                    <button type="button" 
                                            class="noon-btn-add-cart"
                                            data-product-id="{{ $product->id }}">
                                        <i class="las la-shopping-basket"></i>
                                        Add to Cart
                                    </button>
                                    <button type="button" 
                                            class="noon-btn-buy-now"
                                            data-product-id="{{ $product->id }}">
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h3>No products found</h3>
                        <p class="text-muted">Try adjusting your search or filters</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pagination-wrapper">
                    {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                // Add to Cart
                $(document).on('click', '.noon-btn-add-cart', function(e) {
                    e.preventDefault();
                    var productId = $(this).data('product-id');
                    var btn = $(this);

                    $.ajax({
                        url: '{{ route('carts.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            quantity: 1
                        },
                        beforeSend: function() {
                            btn.html('<i class="las la-spinner la-spin"></i> Adding...');
                            btn.prop('disabled', true);
                        },
                        success: function(response) {
                            toastr.success('Product added to cart');
                            btn.html('<i class="las la-shopping-basket"></i> Add to Cart');
                            btn.prop('disabled', false);
                            // Update cart count if needed
                            location.reload();
                        },
                        error: function() {
                            toastr.error('Error adding product to cart');
                            btn.html('<i class="las la-shopping-basket"></i> Add to Cart');
                            btn.prop('disabled', false);
                        }
                    });
                });

                // Buy Now
                $(document).on('click', '.noon-btn-buy-now', function(e) {
                    e.preventDefault();
                    var productId = $(this).data('product-id');

                    $.ajax({
                        url: '{{ route('carts.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            quantity: 1
                        },
                        success: function(response) {
                            toastr.success('Product added to cart');
                            window.location.href = '{{ route('carts.index') }}';
                        },
                        error: function() {
                            toastr.error('Error adding product to cart');
                        }
                    });
                });

                // Add to Favorite
                $(document).on('click', '.noon-action-btn.favorite', function(e) {
                    e.preventDefault();
                    var productId = $(this).data('product-id');
                    var btn = $(this);
                    var icon = btn.find('i');

                    $.ajax({
                        url: '{{ route('favorites.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId
                        },
                        success: function(response) {
                            if (icon.hasClass('far')) {
                                icon.removeClass('far').addClass('fas');
                                btn.addClass('active');
                                toastr.success('Added to favorites');
                            } else {
                                icon.removeClass('fas').addClass('far');
                                btn.removeClass('active');
                                toastr.info('Removed from favorites');
                            }
                        },
                        error: function() {
                            toastr.error('Please login to add favorites');
                        }
                    });
                });

                // Compare
                $(document).on('click', '.noon-action-btn.compare', function(e) {
                    e.preventDefault();
                    var productId = $(this).data('product-id');
                    var btn = $(this);

                    $.ajax({
                        url: '{{ route('compare.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId
                        },
                        success: function(response) {
                            if (response.action === 'added') {
                                btn.addClass('active');
                                toastr.success(response.message);
                                window.location.href = '{{ route('compare.index') }}';
                            } else {
                                btn.removeClass('active');
                                toastr.info(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                toastr.error('Please login to compare products');
                            } else {
                                toastr.error(xhr.responseJSON?.message || 'Error adding to compare');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</main>
@endsection

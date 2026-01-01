@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('shop');
@endphp

@section('title', $pageMeta['title'])

@push('meta')
    <meta name="description" content="{{ $pageMeta['description'] }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $pageMeta['title'] }}">
    <meta property="og:description" content="{{ $pageMeta['description'] }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageMeta['title'] }}">
    <meta name="twitter:description" content="{{ $pageMeta['description'] }}">
@endpush

@push('structured-data')
<script type="application/ld+json">
@json(\App\Services\SEO\SEOService::shopSchema())
</script>
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
                min-width: 110px;
                cursor: pointer;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                padding: 15px 12px;
                border-radius: 16px;
                text-decoration: none !important;
                color: #333 !important;
                position: relative;
            }
            
            .category-icon-item:hover {
                background: linear-gradient(135deg, #f8f9fa, #ffffff);
                transform: translateY(-5px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            }
            
            .category-icon-item.active {
                background: linear-gradient(135deg, #02767F, #04b8c4);
                color: white !important;
                box-shadow: 0 8px 24px rgba(2, 118, 127, 0.3);
                transform: translateY(-3px);
            }
            
            .category-icon-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, #ffffff, #f8f9fa);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 12px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border: 3px solid #e9ecef;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                position: relative;
                overflow: hidden;
            }
            
            .category-icon-circle::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(2, 118, 127, 0.05), rgba(4, 184, 196, 0.05));
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .category-icon-item:hover .category-icon-circle::before {
                opacity: 1;
            }
            
            .category-icon-item:hover .category-icon-circle {
                background: linear-gradient(135deg, #ffffff, #f0f7f8);
                border-color: #04b8c4;
                transform: scale(1.15) translateY(-5px);
                box-shadow: 0 8px 20px rgba(2, 118, 127, 0.2);
            }
            
            .category-icon-item.active .category-icon-circle {
                background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.15));
                border-color: rgba(255,255,255,0.5);
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            }
            
            .category-icon-circle i {
                font-size: 36px;
                color: #02767F;
                transition: all 0.3s ease;
                position: relative;
                z-index: 1;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            }
            
            .category-icon-item:hover .category-icon-circle i {
                transform: scale(1.1);
                filter: drop-shadow(0 4px 8px rgba(2, 118, 127, 0.3));
            }
            
            .category-icon-item.active .category-icon-circle i {
                color: white;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            }
            
            .category-icon-name {
                font-size: 13px;
                font-weight: 600;
                margin-top: 8px;
                line-height: 1.3;
                max-width: 100px;
                word-wrap: break-word;
                transition: all 0.3s ease;
            }
            
            .category-icon-item:hover .category-icon-name {
                font-weight: 700;
                color: #02767F;
            }
            
            .category-icon-item.active .category-icon-name {
                color: white !important;
                font-weight: 700;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
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
            <form action="{{ route('web.shop.search.' . app()->getLocale()) }}" method="GET" class="shop-search-form">
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
            <a href="{{ route('web.shop.show.' . app()->getLocale()) }}" class="category-icon-item {{ !request()->has('category') ? 'active' : '' }}">
                <div class="category-icon-circle">
                    <i class="las la-border-all"></i>
                </div>
                <span class="category-icon-name">All Products</span>
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('web.shop.show.' . app()->getLocale(), ['category' => $category->id]) }}" 
                   class="category-icon-item {{ request('category') == $category->id ? 'active' : '' }}"
                   data-category-id="{{ $category->id }}">
                        <div class="category-icon-circle">
                            @php
                                $catName = strtolower($category->{'name_en'});
                                $iconClass = 'las la-briefcase-medical'; // Default icon
                                $iconColor = '#02767F'; // Default color
                                
                                // Enhanced icon mapping with specific categories
                                if(str_contains($catName, 'shock wave') || str_contains($catName, 'rpw') || str_contains($catName, 'shockwave')) {
                                    $iconClass = 'las la-wave-square';
                                    $iconColor = '#FF6F00';
                                } elseif(str_contains($catName, 'sport') || str_contains($catName, 'athletic')) {
                                    $iconClass = 'las la-dumbbell';
                                    $iconColor = '#E91E63';
                                } elseif(str_contains($catName, 'multi current') || str_contains($catName, 'electro') || str_contains($catName, 'tens') || str_contains($catName, 'current')) {
                                    $iconClass = 'las la-bolt';
                                    $iconColor = '#FFC107';
                                } elseif(str_contains($catName, 'massage gun') || str_contains($catName, 'massage') || str_contains($catName, 'percussion')) {
                                    $iconClass = 'las la-hand-holding';
                                    $iconColor = '#9C27B0';
                                } elseif(str_contains($catName, 'nutrition') || str_contains($catName, 'supplement') || str_contains($catName, 'vitamin')) {
                                    $iconClass = 'las la-apple-alt';
                                    $iconColor = '#4CAF50';
                                } elseif(str_contains($catName, 'consumable') || str_contains($catName, 'supply') || str_contains($catName, 'clinic supply')) {
                                    $iconClass = 'las la-boxes';
                                    $iconColor = '#2196F3';
                                } elseif(str_contains($catName, 'clinic bed') || str_contains($catName, 'bed') || str_contains($catName, 'table')) {
                                    $iconClass = 'las la-bed';
                                    $iconColor = '#607D8B';
                                } elseif(str_contains($catName, 'cupping') || str_contains($catName, 'hijama')) {
                                    $iconClass = 'las la-circle';
                                    $iconColor = '#F44336';
                                } elseif(str_contains($catName, 'lymphatic') || str_contains($catName, 'drainage') || str_contains($catName, 'lymph')) {
                                    $iconClass = 'las la-tint';
                                    $iconColor = '#00BCD4';
                                } elseif(str_contains($catName, 'ortho') || str_contains($catName, 'bone') || str_contains($catName, 'musculoskeletal')) {
                                    $iconClass = 'las la-bone';
                                    $iconColor = '#795548';
                                } elseif(str_contains($catName, 'neuro') || str_contains($catName, 'brain') || str_contains($catName, 'neurological')) {
                                    $iconClass = 'las la-brain';
                                    $iconColor = '#673AB7';
                                } elseif(str_contains($catName, 'cardio') || str_contains($catName, 'heart') || str_contains($catName, 'pulmonary')) {
                                    $iconClass = 'las la-heartbeat';
                                    $iconColor = '#E91E63';
                                } elseif(str_contains($catName, 'spine') || str_contains($catName, 'back') || str_contains($catName, 'vertebra')) {
                                    $iconClass = 'las la-allergies';
                                    $iconColor = '#FF9800';
                                } elseif(str_contains($catName, 'pediatric') || str_contains($catName, 'child') || str_contains($catName, 'kid')) {
                                    $iconClass = 'las la-baby';
                                    $iconColor = '#FFC107';
                                } elseif(str_contains($catName, 'manual') || str_contains($catName, 'therapy') || str_contains($catName, 'hands-on')) {
                                    $iconClass = 'las la-hand-holding-heart';
                                    $iconColor = '#E91E63';
                                } elseif(str_contains($catName, 'exercise') || str_contains($catName, 'gym') || str_contains($catName, 'fitness')) {
                                    $iconClass = 'las la-running';
                                    $iconColor = '#4CAF50';
                                } elseif(str_contains($catName, 'ultrasound') || str_contains($catName, 'ultrasonic')) {
                                    $iconClass = 'las la-wave-square';
                                    $iconColor = '#00BCD4';
                                } elseif(str_contains($catName, 'laser') || str_contains($catName, 'light therapy')) {
                                    $iconClass = 'las la-lightbulb';
                                    $iconColor = '#FFC107';
                                } elseif(str_contains($catName, 'ice') || str_contains($catName, 'cold') || str_contains($catName, 'cryo')) {
                                    $iconClass = 'las la-snowflake';
                                    $iconColor = '#00BCD4';
                                } elseif(str_contains($catName, 'heat') || str_contains($catName, 'hot') || str_contains($catName, 'thermal')) {
                                    $iconClass = 'las la-fire';
                                    $iconColor = '#FF5722';
                                } elseif(str_contains($catName, 'balance') || str_contains($catName, 'stability')) {
                                    $iconClass = 'las la-balance-scale';
                                    $iconColor = '#9E9E9E';
                                } elseif(str_contains($catName, 'wheelchair') || str_contains($catName, 'mobility')) {
                                    $iconClass = 'las la-wheelchair';
                                    $iconColor = '#607D8B';
                                } elseif(str_contains($catName, 'brace') || str_contains($catName, 'splint') || str_contains($catName, 'orthosis')) {
                                    $iconClass = 'las la-shield-alt';
                                    $iconColor = '#795548';
                                } elseif(str_contains($catName, 'bandage') || str_contains($catName, 'tape') || str_contains($catName, 'kinesio')) {
                                    $iconClass = 'las la-band-aid';
                                    $iconColor = '#F44336';
                                }
                            @endphp
                            <i class="{{ $iconClass }}" style="color: {{ $iconColor }};"></i>
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
                                <a href="{{ route('product.show.' . (app()->getLocale() ?: 'en'), $product->id) }}">
                                    <img src="{{ asset($product->productImages->first()?->image ?? 'web/assets/images/default-product.png') }}" 
                                         alt="{{ $product->{'product_name_' . app()->getLocale()} }}" 
                                         class="noon-product-image"
                                         loading="lazy"
                                         width="300"
                                         height="300"
                                         style="aspect-ratio: 1/1; object-fit: cover;" />
                                </a>
                                
                                {{-- Dynamic Badges - Amazon Style --}}
                                @if($product->primaryBadge)
                                <div class="noon-product-badge" style="background: {{ $product->primaryBadge->badge_type === 'best_seller' ? '#FF6F00' : ($product->primaryBadge->badge_type === 'top_clinic_choice' ? '#02767F' : '#4CAF50') }};">
                                    {{ $product->primaryBadge->label }}
                                </div>
                                @endif
                                
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
                                <a href="{{ route('product.show.' . (app()->getLocale() ?: 'en'), $product->id) }}" class="noon-product-title">
                                    {{ $product->{'product_name_' . app()->getLocale()} }}
                                </a>
                                
                                <div class="noon-product-price">{{ number_format($product->product_price, 2) }} EGP</div>
                                
                                {{-- Rating with Review Count (Emphasized) - Amazon Style --}}
                                <div class="noon-product-rating d-flex align-items-center gap-2">
                                    <div style="font-size: 12px;">
                                        @php $avgRating = $product->average_rating; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                            <i class="las la-star active" style="color: #FFA500;"></i>
                                            @else
                                            <i class="las la-star inactive" style="color: #ddd;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span style="font-size: 14px; font-weight: 600; color: #02767F;">{{ $product->review_count }}</span>
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
            // Wait for jQuery to be available
            (function() {
                function initShopButtons() {
                    if (typeof jQuery === 'undefined') {
                        setTimeout(initShopButtons, 100);
                        return;
                    }
                    
                    $(document).ready(function() {
                // Add to Cart
                $(document).on('click', '.noon-btn-add-cart', function(e) {
                    e.preventDefault();
                    var productId = $(this).data('product-id');
                    var btn = $(this);

                    $.ajax({
                        url: '{{ route('carts.store.' . app()->getLocale()) }}',
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
                        url: '{{ route('carts.store.' . app()->getLocale()) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            quantity: 1
                        },
                        success: function(response) {
                            toastr.success('Product added to cart');
                            window.location.href = "{{ route('carts.index.' . app()->getLocale()) }}";
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
                        url: '{{ route('favorites.store.' . app()->getLocale()) }}',
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
                        url: '{{ route('compare.store.' . app()->getLocale()) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId
                        },
                        success: function(response) {
                            if (response.action === 'added') {
                                btn.addClass('active');
                                toastr.success(response.message);
                                window.location.href = '{{ route('compare.index.' . app()->getLocale()) }}';
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
                }
                
                // Start initialization
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initShopButtons);
                } else {
                    initShopButtons();
                }
            })();
        </script>
    @endpush
</main>
@endsection

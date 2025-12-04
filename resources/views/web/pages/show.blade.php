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


            <!-- custom - css include -->
            <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/style.css') }}">
                        <style>
                /* Breadcrumb Styles - Bigger Size */
                #slider-section {
                    margin-bottom: 0;
                }

                #slider-section .item {
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                }

                #slider-section h1 {
                    color: white;
                    font-size: 3rem;
                    font-weight: 700;
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                }

                /* Hero Slider Styles - Images Only */
                .hero-slider-section {
                    margin-bottom: 2rem;
                }

                .hero-slider {
                    height: 35vh;
                    position: relative;
                    overflow: hidden;
                }

                .heroSwiper {
                    height: 100%;
                    width: 100%;
                }

                .slide-content {
                    height: 40vh;
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                .slide-content img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                }

                /* Swiper Navigation Styles */
                .swiper-button-next,
                .swiper-button-prev {
                    color: white;
                    background: rgba(16, 184, 196, 0.8);
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    transition: all 0.3s ease;
                }

                .swiper-button-next:hover,
                .swiper-button-prev:hover {
                    background: rgba(16, 184, 196, 1);
                    transform: scale(1.1);
                }

                .swiper-pagination-bullet {
                    background: white;
                    opacity: 0.7;
                }

                .swiper-pagination-bullet-active {
                    background: #10b8c4;
                    opacity: 1;
                }

                /* Products Category Styles */
                .products-category ul {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .products-category li {
                    margin-bottom: 10px;
                }

                .products-category>ul>li>a {
                    display: block;
                    padding: 10px 14px;
                    background-color: #f5f5f5;
                    color: #36415A;
                    text-decoration: none;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                    cursor: pointer;
                    position: relative;
                }

                .products-category>ul>li>a:hover {
                    background-color: #e0e0e0;
                }

                .products-category li ul {
                    display: none;
                    margin-top: 10px;
                    background-color: #fff;
                    padding: 5px 0;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                    border-radius: 6px;
                    transition: all 0.3s ease;
                }

                .products-category li ul li {
                    padding: 0;
                }

                .products-category li ul a {
                    display: block;
                    padding: 8px 16px;
                    color: #36415A;
                    text-decoration: none;
                    background-color: transparent;
                    transition: background-color 0.3s;
                }

                .products-category li ul a:hover {
                    background-color: #f0f0f0;
                }

                .products-category li.open>ul {
                    display: block;
                }
                
                .cart-dropdown{
                    max-height: 399px !important;
                    overflow: auto !important;
                }

                .products-category>ul>li>a::after {
                    content: "▼";
                    float: right;
                    transition: transform 0.3s ease;
                    font-size: 12px;
                    margin-left: 10px;
                }

                .products-category li.open>a::after {
                    transform: rotate(-180deg);
                }

                .item-image {
                    position: relative;
                    height: 250px;
                    overflow: hidden;
                }

                .swiper {
                    height: 100%;
                }

                .swiper-slide img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .post-label {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    z-index: 10;
                }

                /* Physio Product Cards - Same as index.html */
                .physio-product-card {
                    height: 400px;
                    display: flex;
                    flex-direction: column;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    box-shadow: none;
                    animation: slideInFromBottom 0.8s ease-out;
                    animation-fill-mode: both;
                    margin-bottom: 20px;
                    background: #fff;
                }

                .physio-product-card:nth-child(1) { animation-delay: 0.1s; }
                .physio-product-card:nth-child(2) { animation-delay: 0.2s; }
                .physio-product-card:nth-child(3) { animation-delay: 0.3s; }
                .physio-product-card:nth-child(4) { animation-delay: 0.4s; }

                .physio-product-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                    border-color: #04b8c4;
                }

                .physio-product-card:hover .physio-product-img {
                    transform: scale(1.1);
                }

                .physio-product-card:hover .physio-product-title a {
                    color: #04b8c4;
                }

                .physio-image-container {
                    position: relative;
                    height: 180px;
                    overflow: hidden;
                    background: #fff;
                    border-bottom: 1px solid #e7e7e7;
                }

                .physio-product-img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                    padding: 0;
                    background: #fff;
                }

                .physio-product-card:hover .physio-product-img {
                    transform: scale(1.05);
                }

                .physio-content-wrapper {
                    padding: 15px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    background: white;
                }

                .physio-product-title {
                    font-size: 14px;
                    font-weight: 600;
                    margin-bottom: 8px;
                    line-height: 1.3;
                    min-height: 36px;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }

                .physio-product-title a {
                    color: #333;
                    text-decoration: none;
                }

                .physio-product-title a:hover {
                    color: #04b8c4;
                }

                .physio-product-price {
                    font-size: 16px;
                    font-weight: 700;
                    color: #04b8c4;
                    margin-bottom: 12px;
                }

                .physio-product-card .btns-group {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    z-index: 10;
                }

                .physio-product-card .btns-group ul {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }

                .physio-product-card .btns-group li {
                    margin-bottom: 5px;
                }

                .physio-product-card .add-to-cart,
                .physio-product-card .add-to-favorite,
                .physio-product-card .buy-now-btn {
                    width: 28px;
                    height: 28px;
                    border-radius: 50%;
                    border: none;
                    background: rgba(255, 255, 255, 0.9);
                    color: #333;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    backdrop-filter: blur(5px);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }

                .physio-product-card .add-to-cart:hover,
                .physio-product-card .add-to-favorite:hover {
                    background: #04b8c4;
                    color: white;
                    transform: scale(1.1);
                    box-shadow: 0 4px 12px rgba(4, 184, 196, 0.4);
                }

                /* Buy Now Button - Special Golden/Orange Color */
                .physio-product-card .buy-now-btn:hover {
                    background: linear-gradient(135deg, #FF6B35, #F7931E);
                    color: white;
                    transform: scale(1.1);
                    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
                }

                .physio-product-card .add-to-favorite:hover .favorite-icon {
                    color: white !important;
                }

                .physio-product-card .rating-star {
                    margin-top: auto;
                }

                .physio-product-card .rating-star ul {
                    margin: 0;
                    padding: 0;
                    list-style: none;
                    display: flex;
                    justify-content: center;
                    gap: 2px;
                }

                .physio-product-card .rating-star li {
                    color: #ffc107;
                }

                .physio-product-card .rating-star li:not(.active) {
                    color: #e0e0e0;
                }

                /* Enhanced Hover Effects */
                .physio-product-card:hover {
                    animation: bounce 0.6s ease-in-out;
                }

                /* Product Item Container Spacing */
                .product-item {
                    margin-bottom: 30px;
                }

                /* Column Layout Spacing */
                .has-column-2 .product-item {
                    margin-bottom: 40px;
                }

                /* Swiper Container Styling */
                .swiper-container {
                    height: 100%;
                    width: 100%;
                }

                .swiper-button-next,
                .swiper-button-prev {
                    width: 30px !important;
                    height: 30px !important;
                    border-radius: 50% !important;
                    color: white !important;
                    font-size: 14px !important;
                }

                .swiper-button-next:hover,
                .swiper-button-prev:hover {
                    background: rgba(4, 184, 196, 1) !important;
                    transform: scale(1.1);
                }

                .swiper-pagination-bullet {
                    background: #04b8c4 !important;
                    opacity: 0.7;
                }

                .swiper-pagination-bullet-active {
                    background: #04b8c4 !important;
                    opacity: 1;
                }

                /* 5-Column Desktop Grid */
                .col-lg-2dot4 {
                    position: relative;
                    width: 100%;
                    padding-right: 15px;
                    padding-left: 15px;
                }

                @media (min-width: 992px) {
                    .col-lg-2dot4 {
                        flex: 0 0 20%;
                        max-width: 20%;
                    }
                }

                /* Product Card Size Adjustments for 5-column */
                @media (min-width: 992px) {
                    .physio-product-card {
                        height: 380px;
                    }
                    
                    .physio-image-container {
                        height: 170px;
                    }
                    
                    .physio-product-title {
                        font-size: 13px;
                        min-height: 32px;
                    }
                    
                    .physio-product-price {
                        font-size: 15px;
                    }
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    #slider-section .item {
                        height: 15vh !important;
                    }
                    
                    #slider-section h1 {
                        font-size: 2rem;
                    }
                    
                    .hero-slider {
                        height: 30vh;
                    }
                    
                    .slide-content {
                        height: 30vh;
                    }

                    /* Product Cards Responsive */
                    .physio-product-card {
                        margin-bottom: 25px;
                    }

                    .product-item {
                        margin-bottom: 25px;
                    }

                    .has-column-2 .product-item {
                        margin-bottom: 30px;
                    }

                    .physio-image-container {
                        height: 160px;
                    }
                    
                    .physio-content-wrapper {
                        padding: 12px;
                    }
                    
                    .physio-product-title {
                        font-size: 13px;
                        min-height: 32px;
                    }

                    .physio-product-price {
                        font-size: 15px;
                    }

                    .physio-product-card .add-to-cart,
                    .physio-product-card .add-to-favorite {
                        width: 26px;
                        height: 26px;
                    }
                }

                /* Mobile Single Column */
                @media (max-width: 576px) {
                    .product-item {
                        flex: 0 0 100% !important;
                        max-width: 100% !important;
                    }
                    
                    .physio-product-card {
                        margin-bottom: 20px;
                        height: 420px;
                    }

                    .has-column-2 .product-item {
                        margin-bottom: 25px;
                    }

                    .physio-image-container {
                        height: 200px;
                    }
                    
                    .physio-content-wrapper {
                        padding: 15px;
                    }
                    
                    .physio-product-title {
                        font-size: 14px;
                        min-height: 36px;
                    }

                    .physio-product-price {
                        font-size: 16px;
                    }

                    .physio-product-card .add-to-cart,
                    .physio-product-card .add-to-favorite {
                        width: 28px;
                        height: 28px;
                    }
                }

                @media (max-width: 480px) {
                    .physio-product-card {
                        margin-bottom: 15px;
                    }

                    .product-item {
                        margin-bottom: 15px;
                    }

                    .has-column-2 .product-item {
                        margin-bottom: 20px;
                    }

                    .physio-image-container {
                        height: 140px;
                    }
                    
                    .physio-content-wrapper {
                        padding: 8px;
                    }
                    
                    .physio-product-title {
                        font-size: 11px;
                        min-height: 24px;
                    }

                    .physio-product-price {
                        font-size: 13px;
                    }

                    .physio-product-card .add-to-cart,
                    .physio-product-card .add-to-favorite {
                        width: 22px;
                        height: 22px;
                    }
                }

                /* List Layout Styling */
                .product-list {
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 20px;
                    background: white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                }

                .product-list:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                }

                .product-list .item-image {
                    height: 200px;
                    border-radius: 8px;
                    overflow: hidden;
                    margin-bottom: 15px;
                }

                .product-list .item-content {
                    padding: 15px 0;
                }

                .product-list .post-type {
                    background: #04b8c4;
                    color: white;
                    padding: 5px 12px;
                    border-radius: 15px;
                    font-size: 12px;
                    font-weight: 600;
                    display: inline-block;
                    margin-bottom: 10px;
                }

                .product-list .item-title {
                    font-size: 18px;
                    font-weight: 600;
                    margin-bottom: 10px;
                }

                .product-list .item-title a {
                    color: #333;
                    text-decoration: none;
                }

                .product-list .item-title a:hover {
                    color: #04b8c4;
                }

                .product-list .item-price {
                    font-size: 20px;
                    font-weight: 700;
                    color: #04b8c4;
                    margin-bottom: 15px;
                    display: block;
                }

                .product-list p {
                    color: #666;
                    line-height: 1.6;
                    margin-bottom: 20px;
                }

                .product-list .btn {
                    background: #04b8c4;
                    color: white;
                    border: none;
                    padding: 10px 25px;
                    border-radius: 25px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-decoration: none;
                    display: inline-block;
                }

                .product-list .btn:hover {
                    background: #039ba6;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                    color: white;
                    text-decoration: none;
                }

                /* List Layout Responsive */
                @media (max-width: 768px) {
                    .product-list {
                        padding: 15px;
                        margin-bottom: 15px;
                    }

                    .product-list .item-image {
                        height: 180px;
                    }

                    .product-list .item-title {
                        font-size: 16px;
                    }

                    .product-list .item-price {
                        font-size: 18px;
                    }
                }

                @media (max-width: 576px) {
                    .product-list {
                        padding: 12px;
                    }

                    .product-list .item-image {
                        height: 160px;
                    }

                    .product-list .item-title {
                        font-size: 15px;
                    }

                    .product-list .item-price {
                        font-size: 16px;
                    }

                    .product-list .btn {
                        padding: 8px 20px;
                        font-size: 14px;
                    }
                }
            .swiper-button-next, .swiper-button-prev {
background : none !important;
}

/* Amazon-Style Category Bar */
.category-dropdown-bar {
    background: #fff;
    border: 1px solid #e7e7e7;
    border-radius: 4px;
    padding: 0;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.category-dropdown-bar .dropdown {
    display: inline-block;
    width: 100%;
}

.category-dropdown-bar .dropdown-toggle {
    width: 100%;
    background: #fff;
    border: none;
    padding: 12px 20px;
    text-align: left;
    color: #333;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s;
}

.category-dropdown-bar .dropdown-toggle:hover {
    background: #f8f9fa;
}

.category-dropdown-bar .dropdown-toggle::after {
    content: '\f107';
    font-family: 'Line Awesome Free';
    font-weight: 900;
    border: none;
    vertical-align: middle;
}

.category-dropdown-bar .dropdown-menu {
    width: 100%;
    border-radius: 4px;
    border: 1px solid #e7e7e7;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 8px 0;
    max-height: 500px;
    overflow-y: auto;
}

.category-dropdown-bar .dropdown-item {
    padding: 10px 20px;
    color: #333;
    transition: all 0.2s;
    font-weight: 500;
    border-left: 3px solid transparent;
}

.category-dropdown-bar .dropdown-item:hover {
    background: #f0f7f8;
    color: #04b8c4;
    border-left-color: #04b8c4;
    padding-left: 23px;
}

.category-dropdown-bar .dropdown-divider {
    margin: 4px 0;
    border-color: #e7e7e7;
}

.subcategory-item {
    padding-left: 40px !important;
    font-size: 14px;
    font-weight: 400;
    color: #666;
}

.subcategory-item:hover {
    color: #04b8c4;
    background: #f8f9fa;
}

/* Compact Filter Bar */
.filter-wrap {
    padding: 12px 0 !important;
    margin-bottom: 15px !important;
    border-bottom: 2px solid #e7e7e7 !important;
}

.filter-wrap .result-text {
    font-size: 14px;
    color: #666;
    margin: 0;
    line-height: 36px;
}

.filter-wrap .result-text span {
    color: #04b8c4;
    font-weight: 600;
}

.layout-tab ul {
    margin: 0;
    padding: 0;
}

.layout-tab ul li a {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e7e7e7;
    border-radius: 4px;
    transition: all 0.2s;
    background: #fff;
}

.layout-tab ul li a:hover,
.layout-tab ul li a.active {
    background: #04b8c4;
    border-color: #04b8c4;
}

.layout-tab ul li a:hover svg path,
.layout-tab ul li a.active svg path {
    fill: #fff;
}

/* Product Grid Spacing */
.shop-section {
    padding: 30px 0 50px;
}

.mb-70 {
    margin-bottom: 40px !important;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .category-dropdown-bar {
        margin-bottom: 15px;
    }
    
    .filter-wrap {
        padding: 10px 0 !important;
    }
}
                          
            </style>
        @endpush
        <!-- breadcrumb-section - start
           ================================================== -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 20vh">
                <div class="container">
                    <div class="text-center mt-5 mb-5">
                         <h1 style="margin-top : 60px">Physical Therapy Products Shop</h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb-section - end
           ================================================== -->

        <!-- hero-slider-section - start
           ================================================== -->
        <section class="hero-slider-section clearfix">
            <div class="hero-slider">
                <div class="swiper heroSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="slide-content" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-content" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-content" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>
        <!-- hero-slider-section - end
           ================================================== -->



        <!-- shop-section - start
           ================================================== -->
        <section class="shop-section sec-ptb-100 mb-3 decoration-wrap clearfix">
            <div class="container">
                <!-- Category Dropdown Bar -->
                <div class="category-dropdown-bar">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="categoryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;">
                            <span><i class="las la-th-large" style="margin-right: 8px;"></i>All Categories</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <a class="dropdown-item" href="{{ route('show') }}">
                                <i class="las la-border-all" style="margin-right: 8px;"></i>All Products
                            </a>
                            <div class="dropdown-divider"></div>
                            @foreach ($categories as $category)
                                <h6 class="dropdown-header" style="color: #04b8c4; font-weight: 600; font-size: 13px;">
                                    {{ $category->{'name_' . app()->getLocale()} }}
                                </h6>
                                @foreach ($category->subcategories as $subcategory)
                                    <a class="dropdown-item subcategory-item" href="{{ route('web.shop.category', ['id' => $subcategory->id]) }}">
                                        {{ $subcategory->{'name_' . app()->getLocale()} }}
                                    </a>
                                @endforeach
                                @if (!$loop->last)
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Filter Bar and Products -->
                <div class="row">
                    <div class="col-12">
                        <div class="filter-wrap border-bottom clearfix">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                    <div class="layout-tab ul-li clearfix">
                                        <ul class="nav" role="tablist">
                                            <li>
                                                <a class="active" data-toggle="tab" href="#column-3-tab">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 16 16" fill="000000">
                                                        <path id="_3_Grid" data-name="3 Grid" class="cls-1"
                                                            d="M675,571h3.812v3.812H675V571Zm0,6.094h3.812v3.812H675v-3.812Zm0,6.093h3.812V587H675v-3.813ZM681.094,571h3.812v3.812h-3.812V571Zm0,6.094h3.812v3.812h-3.812v-3.812Zm0,6.093h3.812V587h-3.812v-3.813ZM687.188,571H691v3.812h-3.812V571Zm0,6.094H691v3.812h-3.812v-3.812Zm0,6.093H691V587h-3.812v-3.813Z"
                                                            transform="translate(-675 -571)" />
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#column-2-tab">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 16 16" fill="000000">
                                                        <path id="_2_Grid" data-name="2 Grid" class="cls-1"
                                                            d="M707,580h7v7h-7v-7Zm10,1h6v6h-6v-6Zm-10-10h7v7h-7v-7Zm9,9h7v7h-7v-7Zm1-9h6v6h-6v-6Zm-1,0h7v7h-7v-7Z"
                                                            transform="translate(-707 -571)" />
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#list-layout-tab">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 16 16" fill="000000">
                                                        <path id="_1_Grid" data-name="1 Grid" class="cls-1"
                                                            d="M738,571h4v4h-4v-4Zm0,6h4v4h-4v-4Zm0,6h4v4h-4v-4Zm6-12h10v4H744v-4Zm0,6h10v4H744v-4Zm0,6h10v4H744v-4Z"
                                                            transform="translate(-738 -571)" />
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 text-center">
                                    <p class="result-text">We found <span>{{ $count_product ?? App\Models\Product::where('amount','>','0')->count() }}</span> products are available
                                        for you</p>
                                </div>

                                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
                                    <form action="{{ route('web.shop.search') }}" method="GET" class="d-flex">
                                        <input type="search" value="{{ old('search') }}" name="search"
                                            placeholder="Search products..." class="form-control form-control-sm" style="border-radius: 4px; border: 1px solid #e7e7e7; padding: 8px 12px; font-size: 14px;">
                                        <button type="submit" class="btn btn-sm ml-2" style="background: #04b8c4; color: white; border-radius: 4px; padding: 8px 16px;">
                                            <i class="las la-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div id="column-3-tab" class="tab-pane active">
                                <div class="row mb-70 justify-content-center">
                                    @foreach ($products as $index => $product)
                                        <div class="col-lg-2dot4 col-md-4 col-sm-12 col-xs-12 product-item"
                                            data-name="{{ Str::lower($product->{'product_name_' . app()->getLocale()}) }}">
                                            <div class="product-grid text-center clearfix physio-product-card">
                                                <div class="item-image physio-image-container">
                                                    <div class="swiper-container">
                                                        <div class="swiper-wrapper">
                                                            @foreach ($product->productImages as $img)
                                                                <div class="swiper-slide">
                                                                    <a href="{{ route('product.show', $product->id) }}"
                                                                        class="image-wrap">
                                                                        <img src="{{ asset($img->image) }}"
                                                                            alt="{{ $product->{'product_name_' . app()->getLocale()} }} - Physical Therapy Equipment" class="physio-product-img" />
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="swiper-pagination"></div>
                                                        <div class="swiper-button-next"></div>
                                                        <div class="swiper-button-prev"></div>
                                                    </div>

                                                    <div class="post-label ul-li-right clearfix">
                                                        <!--<ul class="clearfix">-->
                                                        <!--    <li class="bg-skyblue">-19%</li>-->
                                                        <!--    <li class="bg-skyblue">TOP</li>-->
                                                        <!--</ul>-->
                                                    </div>

                                                    <div class="btns-group ul-li-center clearfix">
                                                        <ul class="clearfix">
                                                            <li>
                                                                <button type="button" class="add-to-cart" data-product-id="{{ $product->id }}"
                                                                    data-toggle="tooltip" data-placement="top" title="Add To Cart">
                                                                    <i class="las la-shopping-basket" style="font-size:18px"></i>
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="add-to-favorite border-0 bg-transparent" data-product-id="{{ $product->id }}"
                                                                    data-toggle="tooltip" data-placement="top" title="Add To Favorite">
                                                                    <i class="fa-heart favorite-icon {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"
                                                                       style="font-size:18px; color: {{ $product->favorite && $product->favorite->favorite_type ? 'red' : 'inherit' }};"></i>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="item-content physio-content-wrapper">
                                                    <h3 class="item-title physio-product-title">
                                                        <a href="{{ route('product.show', $product->id) }}">
                                                            {{ $product->{'product_name_' . app()->getLocale()} }}
                                                        </a>
                                                    </h3>
                                                    <span class="item-price physio-product-price">{{ $product->product_price }} EGP</span>
                                                    <div class="rating-star ul-li-center clearfix">
                                                        <ul class="clearfix">
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li><i class="las la-star"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!--<div class="pagination-nav ul-li-center clearfix">-->
                                <!--    <ul class="clearfix">-->
                                <!--        <li><a href="#!"><i class="las la-angle-left"></i></a></li>-->
                                <!--        <li><a href="#!">1</a></li>-->
                                <!--        <li><a href="#!">2</a></li>-->
                                <!--        <li><a href="#!">...</a></li>-->
                                <!--        <li><a href="#!">6</a></li>-->
                                <!--        <li><a href="#!"><i class="las la-angle-right"></i></a></li>-->
                                <!--    </ul>-->
                                <!--</div>-->
                                <div class="pagination-nav ul-li-center clearfix">
                                    {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>


                            <div id="column-2-tab" class="tab-pane fade">
                                <div class="row has-column-2 mb-70 justify-content-center">
                                    @foreach ($products as $index => $product)
                                        <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12 product-item"
                                            data-name="{{ Str::lower($product->{'product_name_' . app()->getLocale()}) }}">
                                            <div class="product-grid text-center clearfix physio-product-card">
                                                <div class="item-image physio-image-container">
                                                    <div class="swiper-container">
                                                        <div class="swiper-wrapper">
                                                            @foreach ($product->productImages as $img)
                                                                <div class="swiper-slide">
                                                                    <a href="{{ route('product.show', $product->id) }}"
                                                                        class="image-wrap">
                                                                        <img src="{{ asset($img->image) }}"
                                                                            alt="{{ $product->{'product_name_' . app()->getLocale()} }} - Physical Therapy Equipment" class="physio-product-img" />
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="swiper-pagination"></div>
                                                        <div class="swiper-button-next"></div>
                                                        <div class="swiper-button-prev"></div>
                                                    </div>

                                                    <div class="post-label ul-li-right clearfix">
                                                        <!--<ul class="clearfix">-->
                                                        <!--    <li class="bg-skyblue">-19%</li>-->
                                                        <!--    <li class="bg-skyblue">TOP</li>-->
                                                        <!--</ul>-->
                                                    </div>

                                                    <div class="btns-group ul-li-center clearfix">
                                                        <ul class="clearfix">
                                                            <li>
                                                                <button type="button" class="add-to-cart" data-product-id="{{ $product->id }}"
                                                                    data-toggle="tooltip" data-placement="top" title="Add To Cart">
                                                                    <i class="las la-shopping-basket" style="font-size:18px"></i>
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button type="button" class="add-to-favorite border-0 bg-transparent" data-product-id="{{ $product->id }}"
                                                                    data-toggle="tooltip" data-placement="top" title="Add To Favorite">
                                                                    <i class="fa-heart favorite-icon {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"
                                                                       style="font-size:18px; color: {{ $product->favorite && $product->favorite->favorite_type ? 'red' : 'inherit' }};"></i>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="item-content physio-content-wrapper">
                                                    <h3 class="item-title physio-product-title">
                                                        <a href="{{ route('product.show', $product->id) }}">
                                                            {{ Str::limit($product->{'product_name_' . app()->getLocale()}, 20) }}
                                                        </a>
                                                    </h3>
                                                    <span class="item-price physio-product-price">{{ $product->product_price }} EGP</span>
                                                    <div class="rating-star ul-li-center clearfix">
                                                        <ul class="clearfix">
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li class="active"><i class="las la-star"></i></li>
                                                            <li><i class="las la-star"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!--<div class="pagination-nav ul-li-center clearfix">-->
                                <!--    <ul class="clearfix">-->
                                <!--        <li><a href="#!"><i class="las la-angle-left"></i></a></li>-->
                                <!--        <li><a href="#!">1</a></li>-->
                                <!--        <li><a href="#!">2</a></li>-->
                                <!--        <li><a href="#!">...</a></li>-->
                                <!--        <li><a href="#!">6</a></li>-->
                                <!--        <li><a href="#!"><i class="las la-angle-right"></i></a></li>-->
                                <!--    </ul>-->
                                <!--</div>-->
                                <div class="pagination-nav ul-li-center clearfix">
                                    {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>


                            <div id="list-layout-tab" class="tab-pane fade">
                                <div class="mb-70 clearfix">
                                    @foreach ($products as $product)
                                        <div class="product-list clearfix">
                                            <div class="item-image">
                                                <!-- Swiper for Product Images -->
                                                <div class="swiper mySwiper-{{ $product->id }}">
                                                    <div class="swiper-wrapper">
                                                        @foreach ($product->productImages as $img)
                                                            <div class="swiper-slide">
                                                                <img src="{{ asset($img->image) }}" alt="image1"
                                                                    loading="lazy">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="swiper-pagination"></div>
                                                </div>


                                                <div class="post-label ul-li-right clearfix">
                                                    <ul class="clearfix">
                                                        <!--<li class="bg-skyblue">-30%</li>-->
                                                        <!--<li class="bg-skyblue">TOP</li>-->
                                                    </ul>
                                                </div>
                                                <div class="btns-group ul-li-center clearfix">
                                                    <ul class="clearfix" style="margin-left : 143px">
                                                        <li>
                                                            <button type="button" class="add-to-cart"
                                                                data-product-id="{{ $product->id }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Add To Cart">
                                                                <i class="las la-shopping-basket"
                                                                    style="font-size:18px"></i>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button"
                                                                class="add-to-favorite border-0 bg-transparent"
                                                                data-product-id="{{ $product->id }}"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Add To Favorite">
                                                                <i class="fa-heart favorite-icon {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"
                                                                    style="font-size:18px; color: {{ $product->favorite && $product->favorite->favorite_type ? 'red' : 'inherit' }};"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="rating-star ul-li clearfix">
                                                <ul class="clearfix">
                                                    <li class="active"><i class="las la-star"></i></li>
                                                    <li class="active"><i class="las la-star"></i></li>
                                                    <li class="active"><i class="las la-star"></i></li>
                                                    <li class="active"><i class="las la-star"></i></li>
                                                    <li><i class="las la-star"></i></li>
                                                </ul>
                                            </div>

                                            <div class="item-content">
                                                <span
                                                    class="post-type">{{ $product->category->{'name_' . app()->getLocale()} }}</span>
                                                <h3 class="item-title">
                                                    <a
                                                        href="{{ route('product.show', $product->id) }}">{{ $product->{'product_name_' . app()->getLocale()} }}</a>
                                                </h3>
                                                <span class="item-price mb-2">{{ $product->product_price }} EGP</span>
                                                <p class="mb-30">
                                                    {{ $product->{'short_description_' . app()->getLocale()} }}
                                                </p>

                                                <form id="add-to-cart-form-{{ $product->id }}"
                                                    action="{{ route('carts.store') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                </form>


                                                <a href="#!" class="btn bg-royal-blue"
                                                    onclick="event.preventDefault(); document.getElementById('add-to-cart-form-{{ $product->id }}').submit();">
                                                    Add To Cart
                                                </a>

                                            </div>
                                        </div>
                                    @endforeach



                                </div>

                                <!--<div class="pagination-nav ul-li-center clearfix">-->
                                <!--    <ul class="clearfix">-->
                                <!--        <li><a href="#!"><i class="las la-angle-left"></i></a></li>-->
                                <!--        <li><a href="#!">1</a></li>-->
                                <!--        <li><a href="#!">2</a></li>-->
                                <!--        <li><a href="#!">...</a></li>-->
                                <!--        <li><a href="#!">6</a></li>-->
                                <!--        <li><a href="#!"><i class="las la-angle-right"></i></a></li>-->

                                <!--    </ul>-->
                                <!--</div>-->
                                <div class="pagination-nav ul-li-center clearfix">
                                    {!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </section>

        <!-- product quick view - start -->
        <div class="quickview-modal modal fade" id="quickview-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="item-image">
                        <img src="{{ asset('web/assets/images/product/img_12.jpg') }}" alt="image_not_found">
                    </div>
                    <div class="item-content">
                        <h2 class="item-title mb-15">Digital Infrared Thermometer</h2>
                        <div class="rating-star ul-li mb-30 clearfix">
                            <ul class="float-left mr-2">
                                <li class="active"><i class="las la-star"></i></li>
                                <li class="active"><i class="las la-star"></i></li>
                                <li class="active"><i class="las la-star"></i></li>
                                <li class="active"><i class="las la-star"></i></li>
                                <li><i class="las la-star"></i></li>
                            </ul>
                            <span class="review-text">(12 Reviews)</span>
                        </div>
                        <span class="item-price mb-15">$49.50</span>
                        <p class="mb-30">
                            Best Electronic Digital Thermometer adipiscing elit, sed do eiusmod teincididunt ut labore et
                            dolore magna aliqua. Quis ipsum suspendisse us ultrices gravidaes. Risus commodo viverra
                            maecenas accumsan lacus vel facilisis.
                        </p>
                        <div class="quantity-form mb-30 clearfix">
                            <strong class="list-title">Quantity:</strong>
                            <div class="quantity-input">
                                <form action="#">
                                    <span class="input-number-decrement">–</span>
                                    <input class="input-number-1" type="text" value="1">
                                    <span class="input-number-increment">+</span>
                                </form>
                            </div>
                        </div>
                        <div class="btns-group ul-li mb-30">
                            <ul class="clearfix">
                                <li><a href="#!" class="btn bg-royal-blue">Add to Cart</a></li>
                                <li><a href="#!" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Compare Product"><i class="las la-sync"></i></a></li>
                                <li><a href="#!" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Add To Wishlist"><i class="lar la-heart"></i></a></li>
                            </ul>
                        </div>
                        <div class="info-list ul-li-block">
                            <ul class="clearfix">
                                <li><strong class="list-title">Category:</strong> <a href="#!">Medical Equipment</a>
                                </li>
                                <li class="social-icon ul-li">
                                    <strong class="list-title">Share:</strong>
                                    <ul class="clearfix">
                                        <li><a href="#!"><i class="lab la-facebook"></i></a></li>
                                        <li><a href="#!"><i class="lab la-twitter"></i></a></li>
                                        <li><a href="#!"><i class="lab la-instagram"></i></a></li>
                                        <li><a href="#!"><i class="lab la-pinterest-p"></i></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- product quick view - end -->
        <!-- shop-section - end
           ================================================== -->
        @push('scripts')
            <!-- jquery include -->
            <script src="{{ asset('web/assets/js/jquery-3.4.1.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/jquery-ui.js') }}"></script>
            <script src="{{ asset('web/assets/js/popper.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/magnific-popup.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/owl.carousel.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/owl.carousel2.thumbs.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/isotope.pkgd.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/masonry.pkgd.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/imagesloaded.pkgd.min.js') }}"></script>
            <script src="{{ asset('web/assets/js/countdown.js') }}"></script>

            <!-- google map - jquery include -->
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDk2HrmqE4sWSei0XdKGbOMOHN3Mm2Bf-M&ver=2.1.6"></script>
            <script src="{{ asset('web/assets/js/gmaps.min.js') }}"></script>

            <!-- mobile menu - jquery include -->
            <script src="{{ asset('web/assets/js/mCustomScrollbar.js') }}"></script>

            <script>
                document.querySelectorAll('.products-category > ul > li > a').forEach(item => {
                    item.addEventListener('click', function() {
                        const parent = this.parentElement;
                        parent.classList.toggle('open');
                    });
                });
            </script>
            <!-- custom - jquery include -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                var swiper = new Swiper(".mySwiper", {
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        /* Time in milliseconds between slides */
                        disableOnInteraction: false,
                        /* Allows autoplay to continue even if the user interacts with the swiper */
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: false,
                    /* Disables navigation arrows */
                });
            </script>

            <script>
                // Hero Slider
                var heroSwiper = new Swiper(".heroSwiper", {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    effect: "fade",
                    fadeEffect: {
                        crossFade: true
                    }
                });

                // Product Swipers
                document.addEventListener('DOMContentLoaded', function() {
                    const swipers = document.querySelectorAll('[class^="swiper mySwiper-"]');

                    swipers.forEach((swiperEl) => {
                        new Swiper(swiperEl, {
                            slidesPerView: 1,
                            spaceBetween: 10,
                            pagination: {
                                el: swiperEl.querySelector('.swiper-pagination'),
                                clickable: true,
                            },
                        });
                    });
                });
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll('[class^="mySwiper-"]').forEach((el) => {
                        new Swiper(`.${el.classList[1]}`, {
                            pagination: {
                                el: el.querySelector(".swiper-pagination"),
                                clickable: true,
                            },
                            loop: true,
                            lazy: true
                        });
                    });
                });
            </script>

            <script>
                $(document).ready(function() {

                    // منع تكرار الحدث باستخدام off().on()
                    $(document).off('click', '.add-to-cart').on('click', '.add-to-cart', function(e) {
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
                                toastr.success('تم إضافة المنتج إلى السلة');
                                // يمكنك هنا تحديث عداد السلة أو إظهار Toast
                            },
                            error: function() {
                                toastr.error('حدث خطأ أثناء الإضافة للسلة');
                            }
                        });
                    });

                    $(document).off('click', '.add-to-favorite').on('click', '.add-to-favorite', function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var productId = button.data('product-id');

                        $.ajax({
                            url: '{{ route('favorites.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                product_id: productId
                            },
                            success: function(response) {
                                // غيّر شكل القلب حسب الحالة
                                var icon = button.find('.favorite-icon');
                                if (icon.hasClass('far')) {
                                    icon.removeClass('far').addClass('fas').css('color', 'red');
                                    toastr.success('تمت الإضافة إلى المفضلة');

                                } else {
                                    icon.removeClass('fas').addClass('far').css('color', 'inherit');
                                    toastr.info('تمت الإزالة من المفضلة');

                                }
                            },
                            error: function() {
                                // alert('حدث خطأ أثناء الإضافة للمفضلة');
                                toastr.error('حدث خطأ أثناء الإضافة للمفضلة');

                            }
                        });
                    });

                });
            </script>


            <script src="{{ asset('web/assets/js/custom.js') }}"></script>
        @endpush

    </main>
@endsection

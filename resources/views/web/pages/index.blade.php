@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('about');
@endphp

@section('title', $isArabic ? 'فيزيولاين - جميع احتياجات أخصائي العلاج الطبيعي من PT إلى PT' : 'Phyzioline - All Physical Therapist Needs From PT to PT')

@push('meta')
    <meta name="description" content="{{ $isArabic 
        ? 'فيزيولاين: جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT. حلول برمجية شاملة، منتجات طبية، زيارات منزلية، إدارة عيادات، دورات، وظائف، ومعلومات.'
        : 'Phyzioline: All Physical Therapist Needs is Our Mission From PT to PT. Comprehensive software solutions, medical products, home visits, clinic management, courses, jobs, and data hub.' }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $isArabic ? 'فيزيولاين - جميع احتياجات أخصائي العلاج الطبيعي' : 'Phyzioline - All Physical Therapist Needs From PT to PT' }}">
    <meta property="og:description" content="{{ $isArabic 
        ? 'حلول برمجية شاملة للعلاج الطبيعي. جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا.'
        : 'Comprehensive physical therapy software solutions. All Physical Therapist Needs is Our Mission From PT to PT.' }}">
    <meta property="og:type" content="website">
@endpush

@push('css')
<style>
    /* Make header transparent to show hero background image */
    #header-section {
        background: transparent !important;
        position: absolute;
        width: 100%;
        z-index: 999;
        top: 0;
    }
    
    #header-section.stuck {
        background: #02767F !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Fix selected text color - make it colored instead of white with maximum specificity */
    *::selection,
    *::-moz-selection,
    body *::selection,
    body *::-moz-selection,
    html *::selection,
    html *::-moz-selection,
    .home-v1 *::selection,
    .home-v1 *::-moz-selection,
    .home-v1::selection,
    .home-v1::-moz-selection,
    section *::selection,
    section *::-moz-selection,
    div *::selection,
    div *::-moz-selection,
    p *::selection,
    p *::-moz-selection,
    h1 *::selection,
    h1 *::-moz-selection,
    h2 *::selection,
    h2 *::-moz-selection,
    h3 *::selection,
    h3 *::-moz-selection,
    h4 *::selection,
    h4 *::-moz-selection,
    h5 *::selection,
    h5 *::-moz-selection,
    h6 *::selection,
    h6 *::-moz-selection,
    span *::selection,
    span *::-moz-selection,
    a *::selection,
    a *::-moz-selection {
        color: #02767F !important;
        background: rgba(4, 184, 196, 0.3) !important;
    }
    
    /* Also target text directly (not just children) */
    ::selection,
    ::-moz-selection {
        color: #02767F !important;
        background: rgba(4, 184, 196, 0.3) !important;
    }
    
    /* Ensure hero text has text shadow for visibility */
    .slider-section .hero-text.text-white,
    .slider-section .hero-praph.text-white {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    /* Fix white text on white backgrounds in main content (not header/hero) */
    .ecosystem-section .text-white,
    .bg-white .text-white,
    [style*="background-color: #fff"] .text-white:not(.hero-text):not(.hero-praph),
    [style*="background-color: white"] .text-white:not(.hero-text):not(.hero-praph),
    [style*="background: white"] .text-white:not(.hero-text):not(.hero-praph),
    [style*="background: #fff"] .text-white:not(.hero-text):not(.hero-praph) {
        color: #02767F !important;
    }
    
    /* Ensure sections with light backgrounds don't have white text */
    .ecosystem-section:not(.slider-section),
    section[style*="background-color: #f8f9fa"]:not(.slider-section),
    section[style*="background-color: #ffffff"]:not(.slider-section),
    .bg-light:not(.slider-section),
    .bg-white:not(.slider-section) {
        color: #36415a !important;
    }
    
    .ecosystem-section .text-white:not(.hero-text):not(.hero-praph),
    section[style*="background-color: #f8f9fa"] .text-white:not(.hero-text):not(.hero-praph),
    section[style*="background-color: #ffffff"] .text-white:not(.hero-text):not(.hero-praph),
    .bg-light .text-white:not(.hero-text):not(.hero-praph),
    .bg-white .text-white:not(.hero-text):not(.hero-praph) {
        color: #02767F !important;
    }
</style>
@endpush

@section('content')
    <main>
        <!-- slider-section - start
       ================================================== -->
        <!-- Hero Section - Start -->
    <!-- hero-section - start
    ================================================== -->
    <style>
        .hero-section {
            padding-top: 120px;
            padding-bottom: 60px;
        }
        @media (min-width: 992px) {
            .hero-section {
                padding-top: 180px;
                padding-bottom: 100px;
            }
        }
    </style>
    <section id="hero-section" class="hero-section clearfix" style="background: linear-gradient(135deg, #02767F 0%, #004d57 100%); position: relative; overflow: hidden;">
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="hero-content text-white" data-aos="fade-right" data-aos-duration="1000">
                        <span class="sub-title text-uppercase mb-3 d-block font-weight-bold" style="letter-spacing: 2px; color: #7de8f0;">
                            {{ __('The #1 Physical Therapy Platform') }}
                        </span>
                        <h1 class="display-3 font-weight-bold mb-4" style="line-height: 1.2;">
                            {{ __('Empowering The') }} <br>
                            <span style="color: #ffcc00;">{{ __('Physiotherapy Community') }}</span>
                        </h1>
                        <p class="lead mb-5" style="opacity: 0.9; font-size: 1.25rem;">
                            {{ __('From certified home visits and clinic management to continuous learning and career growth. Phyzioline is your complete professional ecosystem.') }}
                        </p>
                        <div class="btn-wrap">
                            <a href="{{ route('view_register.' . app()->getLocale()) }}" class="btn btn-light btn-lg rounded-pill px-5 font-weight-bold text-primary mr-3 shadow-lg" style="color: #02767F !important;">
                                {{ __('Join Now') }}
                            </a>
                            <a href="#services-section" class="btn btn-outline-light btn-lg rounded-pill px-5 font-weight-bold">
                                {{ __('Explore Services') }}
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Removed the decoration PNG as requested -->
            </div>
        </div>
        
        <!-- Background shapes for modern look -->
        <div class="shape-1" style="position: absolute; top: -100px; right: -100px; width: 500px; height: 500px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div class="shape-2" style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    </section>
    <!-- hero-section - end
    ================================================== -->

    <!-- service-section - start
    ================================================== -->
    <section id="services-section" class="service-section sec-ptb-100 clearfix">
        <div class="container">
            <div class="section-title text-center mb-5">
                <span class="text-uppercase font-weight-bold" style="color: #02767F; letter-spacing: 1px;">{{ __('Our Ecosystem') }}</span>
                <h2 class="display-4 font-weight-bold" style="color: #36415a">{{ __('Comprehensive solutions for the physiotherapy community') }}</h2>
            </div>
        
            <!-- Home Visits -->
            <div class="service-wrapper mb-5" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-last">
                        <div class="service-image shadow-lg rounded overflow-hidden">
                            <img src="{{ asset('web/assets/images/home_visit_illustration.webp') }}" onerror="this.src='{{ asset('web/assets/images/home_visit_illustration.png') }}'" alt="Home Visits" class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service-content p-4">
                            <div class="icon-wrapper mb-3">
                                <i class="las la-user-nurse" style="font-size: 40px; color: #02767F; background: #e0f7fa; padding: 15px; border-radius: 50%;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: #36415a">{{ __('For Patients') }}: {{ __('Home Visits') }}</h3>
                            <h4 class="mb-3" style="color: #02767F;">{{ __('Expert Care,') }} <br> {{ __('At Your Doorstep') }}</h4>
                            <p class="lead text-muted mb-4">
                                {{ __('Find specialized physiotherapists for private home sessions. We verify every practitioner to ensure you receive the safest and most effective care.') }}
                            </p>
                            <ul class="list-unstyled mb-4 text-muted">
                                <li class="mb-2"><i class="las la-check-circle text-success mr-2"></i> {{ __('Licensed & Verified Therapists') }}</li>
                                <li class="mb-2"><i class="las la-check-circle text-success mr-2"></i> {{ __('Personalized Treatment Plans') }}</li>
                                <li class="mb-2"><i class="las la-check-circle text-success mr-2"></i> {{ __('Convenient Scheduling') }}</li>
                            </ul>
                            <a href="{{ route('web.home_visits.index') }}" class="btn btn-primary rounded-pill px-4 shadow py-2" style="background: #02767F; border-color: #02767F;">
                                {{ __('Book a Visit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Courses Section (with new illustration) -->
            <div class="service-wrapper mb-5" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="service-image shadow-lg rounded overflow-hidden">
                            <img src="{{ asset('web/assets/images/courses_illustration.webp') }}" onerror="this.src='{{ asset('web/assets/images/courses_illustration.png') }}'" alt="Learning Hub" class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service-content p-4">
                            <div class="icon-wrapper mb-3">
                                <i class="las la-graduation-cap" style="font-size: 40px; color: #02767F; background: #e0f7fa; padding: 15px; border-radius: 50%;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: #36415a">{{ __('For Course Providers') }}: {{ __('Manage Your Academy') }}</h3>
                            <h4 class="mb-3" style="color: #02767F;">{{ __('Empower Your') }} <br> {{ __('Educational Institution') }}</h4>
                            <p class="lead text-muted mb-4">
                                {{ __('Leverage our comprehensive Learning Management System to create courses, manage students, track progress, and issue certifications—all in one place.') }}
                            </p>
                            <a href="{{ route('web.courses.index') }}" class="btn btn-primary rounded-pill px-4 shadow py-2" style="background: #02767F; border-color: #02767F;">
                                {{ __('Partner With Us') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jobs Section (Restored with new illustration as requested) -->
            <div class="service-wrapper mb-5" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-last">
                        <div class="service-image shadow-lg rounded overflow-hidden">
                             <img src="{{ asset('web/assets/images/jobs_illustration.webp') }}" onerror="this.src='{{ asset('web/assets/images/jobs_illustration.png') }}'" alt="Phyzioline Jobs" class="img-fluid w-100">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service-content p-4">
                            <div class="icon-wrapper mb-3">
                                <i class="las la-briefcase" style="font-size: 40px; color: #02767F; background: #e0f7fa; padding: 15px; border-radius: 50%;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: #36415a">{{ __('Careers') }}</h3>
                            <h4 class="mb-3" style="color: #02767F;">{{ __('Find Your Dream') }} <br> {{ __('Job Opportunity') }}</h4>
                            <p class="lead text-muted mb-4">
                                {{ __('Connect with top clinics, hospitals, and recruitment agencies. Whether you are looking for a full-time position or freelance opportunities, Phyzioline Jobs is your gateway.') }}
                            </p>
                            <a href="{{ route('web.jobs.index') }}" class="btn btn-primary rounded-pill px-4 shadow py-2" style="background: #02767F; border-color: #02767F;">
                                {{ __('Browse Jobs') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <!-- service-section - end
    ================================================== -->


        <!-- shop-section - start
        ================================================== -->
        <section id="shop-section" class="shop-section sec-ptb-100 decoration-wrap clearfix">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <span class="text-uppercase font-weight-bold" style="color: #02767F; letter-spacing: 2px;">{{ __('Online Store') }}</span>
                    <!-- Updated Shop Title as requested -->
                    <h2 class="display-4 font-weight-bold mb-3" style="color: #36415a">{{ __('All Physical Therapy Devices') }}</h2>
                    <p class="lead text-muted">{{ __('Browse our top-rated physiotherapy equipment and supplies.') }}</p>
                </div>
                
                <!-- Shop Illustration if needed/requested -->
                 <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                         <img src="{{ asset('web/assets/images/shop_illustration.webp') }}" onerror="this.src='{{ asset('web/assets/images/shop_illustration.png') }}'" alt="Shop Illustration" class="img-fluid rounded shadow-sm w-100 mb-4">
                    </div>
                </div>

                <!-- Fix for Search Bar (RTL support) -->
                <div class="row justify-content-center mb-5">
                     <div class="col-md-8">
                        <div class="input-group search-group shadow-sm" style="border-radius: 50px; overflow: hidden; {{ app()->getLocale() == 'ar' ? 'direction: rtl;' : '' }}">
                             <input type="text" id="searchInput" class="form-control border-0 py-4 px-4 searchInput" 
                                    placeholder="{{ __('Search your Product') }}" 
                                    style="font-size: 1.1rem; background: #f8f9fa; {{ app()->getLocale() == 'ar' ? 'text-align: right;' : '' }}">
                             <div class="input-group-append" style="{{ app()->getLocale() == 'ar' ? 'margin-left: 0; margin-right: -1px;' : '' }}">
                                 <button class="btn btn-primary px-5 font-weight-bold" type="button" style="background: #02767F; border-color: #02767F;">
                                     <i class="las la-search mr-2"></i> {{ __('Search') }}
                                 </button>
                             </div>
                        </div>
                     </div>
                </div>

                <div class="row justify-content-center mb-5">
                    @foreach ($products->take(8) as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 product-item mb-4">
                            <div class="product-grid text-center clearfix physio-product-card h-100">
                                <div class="item-image physio-image-container">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach ($product->productImages as $img)
                                                <div class="swiper-slide">
                                                    <a href="{{ route('product.show.' . app()->getLocale(), $product->id) }}"
                                                        class="image-wrap">
                                                        <img src="{{ asset($img->image) }}"
                                                            alt="{{ $product->{'product_name_' . app()->getLocale()} }}"
                                                            class="physio-product-img"
                                                            loading="lazy"
                                                            style="width: 100%; height: 100%; object-fit: cover;" />
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- Add pagination/navigation if needed, or remove for cleaner look -->
                                    </div>

                                    <div class="btns-group ul-li-center clearfix">
                                        <ul class="clearfix">
                                            <li>
                                                <button type="button" class="add-to-cart" data-product-id="{{ $product->id }}"
                                                    data-toggle="tooltip" data-placement="top" title="{{ __('Add To Cart') }}">
                                                    <i class="las la-shopping-basket" style="font-size:18px"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="add-to-favorite border-0 bg-transparent" data-product-id="{{ $product->id }}"
                                                    data-toggle="tooltip" data-placement="top" title="{{ __('Add To Favorite') }}">
                                                    <i class="fa-heart favorite-icon {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"
                                                    style="font-size:18px; color: {{ $product->favorite && $product->favorite->favorite_type ? 'red' : 'inherit' }};"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="item-content physio-content-wrapper p-3">
                                    <h3 class="item-title physio-product-title mb-2" style="min-height: 50px;">
                                        <a href="{{ route('product.show.' . app()->getLocale(), $product->id) }}" class="text-dark">
                                            {{Str::limit($product->{'product_name_' . app()->getLocale()}, 40)}}
                                        </a>
                                    </h3>
                                    
                                    <div class="vendor-badge mb-2">
                                        <span class="badge badge-light text-muted border">{{ $product->sold_by_name }}</span>
                                    </div>

                                    <div class="price-order-wrapper d-flex justify-content-between align-items-center">
                                        <span class="item-price physio-product-price font-weight-bold" style="color: #02767F; font-size: 1.1rem;">{{ $product->product_price }} {{ __('EGP') }}</span>
                                        <button type="button" class="buy-now-btn btn btn-sm rounded-pill text-white" 
                                                data-product-id="{{ $product->id }}"
                                                style="background: linear-gradient(135deg, #02767F, #04b8c4);">
                                            {{ __('Buy Now') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="btn-wrap text-center clearfix">
                    <a href="{{ route('show') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5 font-weight-bold" style="border-width: 2px;">
                        {{ __('View All Products') }}
                    </a>
                </div>
            </div>

            <span class="decoration-image pill-image-1">
                <img src="{{ asset('web/assets/images/decoration/pill_2.png') }}" alt="decoration" />
            </span>
        </section>
      


        <!-- JavaScript للبحث داخل المنتجات -->
        <!--<script>-->
        <!--    document.addEventListener('DOMContentLoaded', function() {-->
        <!--        const searchInput = document.getElementById('searchInput');-->
        <!--        searchInput.addEventListener('keyup', function() {-->
        <!--            const value = this.value.toLowerCase();-->
        <!--            const activeTab = document.querySelector('.tab-pane.active');-->
        <!--            const products = activeTab.querySelectorAll('.product-item');-->

        <!--            products.forEach(product => {-->
        <!--                const name = product.getAttribute('data-name');-->
        <!--                product.style.display = name.includes(value) ? '' : 'none';-->
        <!--            });-->
        <!--        });-->
        <!--    });-->
        <!--</script>-->
          <script>
    // Wait for jQuery to be available
    (function() {
        function initCartButtons() {
            if (typeof jQuery === 'undefined') {
                setTimeout(initCartButtons, 100);
                return;
            }
            
            $(document).ready(function () {
     
        $(document).off('click', '.add-to-cart').on('click', '.add-to-cart', function (e) {
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
                success: function (response) {
                    toastr.success('تم إضافة المنتج إلى السلة');
                   
                },
                error: function () {
                    toastr.error('حدث خطأ أثناء الإضافة للسلة');
                }
            });
        });

        $(document).off('click', '.add-to-favorite').on('click', '.add-to-favorite', function (e) {
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
                success: function (response) {
                  
                    var icon = button.find('.favorite-icon');
                    if (icon.hasClass('far')) {
                        icon.removeClass('far').addClass('fas').css('color', 'red');
                                                toastr.success('تمت الإضافة إلى المفضلة');

                    } else {
                        icon.removeClass('fas').addClass('far').css('color', 'inherit');
                                                toastr.info('تمت الإزالة من المفضلة');

                    }
                },
                error: function () {
                   
                                        toastr.error('حدث خطأ أثناء الإضافة للمفضلة');

                }
            });
        });

        // Buy Now button handler
        $(document).off('click', '.buy-now-btn').on('click', '.buy-now-btn', function (e) {
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
                success: function (response) {
                    toastr.success('تمت إضافة المنتج إلى السلة');
                    // Redirect to cart or checkout page
                    window.location.href = '{{ route('carts.index') }}';
                },
                error: function () {
                    toastr.error('حدث خطأ أثناء الإضافة للسلة');
                }
            });
        });

        // Order Now button handler (اطلب الآن)
        $(document).off('click', '.btn-order-now').on('click', '.btn-order-now', function (e) {
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
                success: function (response) {
                    toastr.success('تمت إضافة المنتج إلى السلة');
                    // Redirect to cart page
                    window.location.href = '{{ route('carts.index') }}';
                },
                error: function () {
                    toastr.error('حدث خطأ أثناء الإضافة للسلة');
                }
            });
        });

            });
        }
        
        // Start initialization
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCartButtons);
        } else {
            initCartButtons();
        }
    })();
</script>

        <!-- JavaScript للبحث داخل المنتجات -->
        <!--<script>-->
        <!--    document.addEventListener('DOMContentLoaded', function() {-->
        <!--        const searchInput = document.getElementById('searchInput');-->
        <!--        searchInput.addEventListener('keyup', function() {-->
        <!--            const value = this.value.toLowerCase();-->
        <!--            const activeTab = document.querySelector('.tab-pane.active');-->
        <!--            const products = activeTab.querySelectorAll('.product-item');-->

        <!--            products.forEach(product => {-->
        <!--                const name = product.getAttribute('data-name');-->
        <!--                product.style.display = name.includes(value) ? '' : 'none';-->
        <!--            });-->
        <!--        });-->
        <!--    });-->
        <!--</script>-->

        <!-- Custom CSS for Physio Product Cards -->
        <style>
            /* Page Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slideInFromTop {
                from {
                    opacity: 0;
                    transform: translateY(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
                100% {
                    transform: scale(1);
                }
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-10px);
                }
            }

            /* Apply animations to sections */
            .slider-section {
                animation: fadeInUp 1s ease-out;
            }

            .section-title {
                animation: slideInFromTop 1s ease-out;
            }

            .tabs-nav {
                animation: fadeInUp 1.2s ease-out;
            }

            .product-item {
                animation: fadeInUp 0.8s ease-out;
                animation-fill-mode: both;
            }

            .product-item:nth-child(1) { animation-delay: 0.1s; }
            .product-item:nth-child(2) { animation-delay: 0.2s; }
            .product-item:nth-child(3) { animation-delay: 0.3s; }
            .product-item:nth-child(4) { animation-delay: 0.4s; }

            .about-section {
                animation: fadeInUp 1s ease-out;
            }

            .mission, .vision {
                animation: fadeInLeft 1s ease-out;
                animation-fill-mode: both;
            }

            .vision {
                animation-delay: 0.3s;
            }

            .section-image {
                animation: fadeInRight 1s ease-out;
            }

            /* Hover animations */
            .hero-text {
                animation: float 3s ease-in-out infinite;
            }

            .hero-praph {
                animation: fadeInUp 1.5s ease-out;
            }

            .physio-view-all-btn {
                animation: pulse 2s ease-in-out infinite;
            }

            .physio-view-all-btn:hover {
                animation: none;
            }

            /* Enhanced hover effects */
            .physio-product-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            }

            .mission:hover, .vision:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            }

            .mission:hover .mission-icon,
            .vision:hover .vision-icon {
                transform: scale(1.2) rotate(10deg);
                animation: pulse 1s ease-in-out infinite;
            }

            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
            }

            /* Loading animation for images */
            .physio-product-img {
                opacity: 0;
                animation: fadeInUp 0.5s ease-out forwards;
                animation-delay: 0.2s;
            }

            /* Enhanced button animations */
            .add-to-cart, .add-to-favorite {
                animation: fadeInUp 0.6s ease-out;
                animation-fill-mode: both;
            }

            .add-to-cart:nth-child(1) { animation-delay: 0.3s; }
            .add-to-favorite:nth-child(2) { animation-delay: 0.4s; }

            /* Text reveal animation */
            .text-about {
                opacity: 0;
                animation: fadeInUp 1s ease-out forwards;
                animation-delay: 0.5s;
            }

            /* Responsive animations */
            @media (max-width: 768px) {
                .product-item {
                    animation-delay: 0.1s !important;
                }
                
                .mission, .vision {
                    animation-delay: 0.1s !important;
                }
            }

            .physio-product-card {
                height: 100%;
                display: flex;
                flex-direction: column;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            .physio-product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }

            .physio-image-container {
                position: relative;
                height: 250px;
                overflow: hidden;
                background: #f8f9fa;
            }

            .physio-product-img {
                width: 100%;
                height: 120%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .physio-product-card:hover .physio-product-img {
                transform: scale(1.05);
            }

            .physio-content-wrapper {
                padding: 20px;
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                background: white;
            }

            .physio-product-title {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 10px;
                line-height: 1.4;
                min-height: 44px;
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
                color: #02767F;
            }

            .physio-product-price {
                font-size: 18px;
                font-weight: 700;
                color: #02767F;
                margin-bottom: 15px;
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
                width: 35px;
                height: 35px;
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
                background: #02767F;
                color: white;
                transform: scale(1.1);
                box-shadow: 0 4px 12px rgba(2, 118, 127, 0.4);
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

            /* Enhanced Product Image Container with Border and Shadow */
            .physio-image-container {
                position: relative;
                height: 250px;
                overflow: hidden;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 12px 12px 0 0;
                border: 2px solid #e0e0e0;
                border-bottom: none;
            }

            .physio-product-img {
                width: 100%;
                height: 120%;
                object-fit: cover;
                transition: all 0.4s ease;
                filter: brightness(1) contrast(1);
            }

            .physio-product-card:hover .physio-product-img {
                transform: scale(1.08) rotate(1deg);
                filter: brightness(1.05) contrast(1.05);
            }

            /* Image Overlay Effect on Hover */
            .physio-image-container::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.1) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }

            .physio-product-card:hover .physio-image-container::after {
                opacity: 1;
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

            /* View All Button Styling - Similar to SOON THE OPENING */
            .physio-view-all-btn {
                background: #02767F;
                color: white;
                border: 2px solid #02767F;
                padding: 12px 30px;
                font-size: 14px;
                font-weight: 600;
                border-radius: 5px;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(2, 118, 127, 0.3);
                position: relative;
                overflow: hidden;
                cursor: pointer;
                display: inline-block;
                text-decoration: none;
            }

            .physio-view-all-btn:hover {
                background: #02767F;
                color: white !important;
                border-color: #02767F;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(2, 118, 127, 0.4);
                text-decoration: none;
            }

            .physio-view-all-btn:hover * {
                color: white !important;
            }

            .physio-view-all-btn:active {
                transform: translateY(0);
                box-shadow: 0 2px 10px rgba(2, 118, 127, 0.3);
            }

            /* Who We Are Section Styling */
            .about-section {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 80px 0;
                position: relative;
            }

            .about-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 5px;
                background: linear-gradient(90deg, #02767F, #36415a, #02767F);
            }

            .about-title .title-text {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 20px;
                position: relative;
            }

            .about-title .title-text::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: linear-gradient(90deg, #02767F, #36415a);
                border-radius: 2px;
            }

            .text-light-green {
                color: #02767F !important;
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 30px;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }

            .section-title ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .section-title ul li {
                background: white;
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                border-left: 5px solid #02767F;
                transition: all 0.3s ease;
                position: relative;
            }

            .section-title ul li:hover {
                transform: translateX(10px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }

            .section-title ul li::before {
                content: '✓';
                position: absolute;
                left: -15px;
                top: 50%;
                transform: translateY(-50%);
                background: #02767F;
                color: white;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 14px;
            }

            .section-image img {
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
            }

            .section-image img:hover {
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            }

            .section-image h5 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-top: 20px;
            }

            .section-image p {
                color: #02767F;
                font-weight: 600;
                font-size: 1.1rem;
            }

            .mission, .vision {
                background: white;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                margin-bottom: 30px;
                transition: all 0.3s ease;
                border-top: 4px solid #02767F;
                position: relative;
                overflow: hidden;
            }

            .mission:hover, .vision:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            }

            .mission-icon, .vision-icon {
                display: inline-block;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #02767F, #36415a);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                vertical-align: middle;
                box-shadow: 0 4px 15px rgba(2, 118, 127, 0.3);
                transition: all 0.3s ease;
                float: left;
            }

            .mission:hover .mission-icon,
            .vision:hover .vision-icon {
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 8px 25px rgba(2, 118, 127, 0.4);
            }

            .mission-icon i, .vision-icon i {
                font-size: 24px;
                color: white;
                font-weight: bold;
            }

            .mission h3, .vision h3 {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 20px;
                display: inline-block;
                vertical-align: middle;
            }

            .text-about {
                font-size: 1.1rem;
                line-height: 1.8;
                color: #555;
                margin: 0;
                text-align: justify;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .physio-image-container {
                    height: 200px;
                }
                
                .physio-content-wrapper {
                    padding: 15px;
                }
                
                .physio-product-title {
                    font-size: 14px;
                    min-height: 40px;
                }

                .about-title .title-text {
                    font-size: 2rem;
                }

                .text-light-green {
                    font-size: 1.8rem;
                }

                .section-title ul li {
                    padding: 15px;
                    margin-bottom: 15px;
                }

                .mission, .vision {
                    padding: 20px;
                }
            }
        </style>





        <div class="quickview-modal modal fade" id="quickview-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="item-image">
                        <img src="{{ asset('web/assets/images/product/img_12.jpg') }}" alt="image_not_found" />
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
                            Best Electronic Digital Thermometer adipiscing elit, sed do
                            eiusmod teincididunt ut labore et dolore magna aliqua. Quis
                            ipsum suspendisse us ultrices gravidaes. Risus commodo viverra
                            maecenas accumsan lacus vel facilisis.
                        </p>
                        <div class="quantity-form mb-30 clearfix">
                            <strong class="list-title">Quantity:</strong>
                            <div class="quantity-input">
                                <form action="#">
                                    <span class="input-number-decrement">–</span>
                                    <input class="input-number-1" type="text" value="1" />
                                    <span class="input-number-increment">+</span>
                                </form>
                            </div>
                        </div>
                        <div class="btns-group ul-li mb-30">
                            <ul class="clearfix">
                                <li>
                                    <a href="#!" class="btn bg-royal-blue">Add to Cart</a>
                                </li>
                                <li>
                                    <a href="#!" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Compare Product"><i class="las la-sync"></i></a>
                                </li>
                                <li>
                                    <a href="#!" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Add To Wishlist"><i class="lar la-heart"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="info-list ul-li-block">
                            <ul class="clearfix">
                                <li>
                                    <strong class="list-title">Category:</strong>
                                    <a href="#!">Medical Equipment</a>
                                </li>
                                <li class="social-icon ul-li">
                                    <strong class="list-title">Share:</strong>
                                    <ul class="clearfix">
                                        <li>
                                            <a href="#!"><i class="lab la-facebook"></i></a>
                                        </li>
                                        <li>
                                            <a href="#!"><i class="lab la-twitter"></i></a>
                                        </li>
                                        <li>
                                            <a href="#!"><i class="lab la-instagram"></i></a>
                                        </li>
                                        <li>
                                            <a href="#!"><i class="lab la-pinterest-p"></i></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- shop-section - end
       ================================================== -->

        <!-- Services Sections -->
        
        <!-- Private Cases (Home Visits) -->
        <section id="privateCases" class="py-5" style="background: white;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="position-relative">
                            <img src="{{ asset('web/assets/images/37452044_8502937 1.svg') }}" class="img-fluid" alt="Home Visits">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="pl-lg-5">
                            <span class="text-uppercase font-weight-bold" style="color: #02767F; letter-spacing: 2px;">{{ __('For Patients') }}</span>
                            <h2 class="display-4 font-weight-bold mb-4" style="color: #36415a;">
                                {{ __('Expert Care,') }} <br>
                                {{ __('At Your Doorstep') }}
                            </h2>
                            <p class="lead text-muted mb-4">
                                {{ __('Find specialized physiotherapists for private home sessions. We verify every practitioner to ensure you receive the safest and most effective care.') }}
                            </p>
                            <ul class="list-unstyled mb-5 text-muted">
                                <li class="mb-2"><i class="las la-check-circle mr-2" style="color: #02767F;"></i> {{ __('Licensed & Verified Therapists') }}</li>
                                <li class="mb-2"><i class="las la-check-circle mr-2" style="color: #02767F;"></i> {{ __('Personalized Treatment Plans') }}</li>
                                <li class="mb-2"><i class="las la-check-circle mr-2" style="color: #02767F;"></i> {{ __('Convenient Scheduling') }}</li>
                            </ul>
                            <a href="{{ route('web.home_visits.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" style="background-color: #02767F; border-color: #02767F;">
                                {{ __('Book a Visit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Clinic ERP -->
        <section id="System" class="py-5" style="background: linear-gradient(135deg, #02767F 0%, #035e66 100%); color: white;">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                         <img src="{{ asset('web/assets/images/Untitled-1سسس_3-removebg-preview.png') }}" class="img-fluid" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);" alt="Clinic Application">
                    </div>
                    <div class="col-lg-6">
                        <span class="text-uppercase font-weight-bold" style="color: #4bd1db; letter-spacing: 2px;">{{ __('For Clinics') }}</span>
                        <h2 class="display-4 font-weight-bold mb-4">
                            {{ __('The Future of') }} <br>
                            {{ __('Clinic Management') }}
                        </h2>
                        <p class="lead mb-4" style="opacity: 0.9;">
                            {{ __('Streamline your practice with our Clinical ERP. From patient scheduling and electronic medical records to billing and analytics - all in one secure platform.') }}
                        </p>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-2"><i class="las la-check-circle mr-2"></i> {{ __('Smart Scheduling System') }}</li>
                            <li class="mb-2"><i class="las la-check-circle mr-2"></i> {{ __('Electronic Health Records (EHR)') }}</li>
                            <li class="mb-2"><i class="las la-check-circle mr-2"></i> {{ __('Financial Management & Billing') }}</li>
                        </ul>
                        <a href="{{ route('web.erp.index') }}" class="btn btn-light btn-lg rounded-pill px-5 shadow-lg" style="color: #02767F; font-weight: bold;">
                            {{ __('Manage Your Clinic') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Jobs Section -->
        <section id="jobs" class="py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <img src="{{ asset('web/assets/images/37452044_8502937 1.svg') }}" class="img-fluid" alt="Jobs">
                    </div>
                    <div class="col-lg-6">
                        <div class="pl-lg-5">
                            <span class="text-uppercase font-weight-bold" style="color: #02767F; letter-spacing: 2px;">{{ __('Careers') }}</span>
                            <h2 class="display-4 font-weight-bold mb-4" style="color: #36415a;">
                                {{ __('Find Your Dream') }} <br>
                                {{ __('Job Opportunity') }}
                            </h2>
                            <p class="lead text-muted mb-4">
                                {{ __('Connect with top clinics, hospitals, and recruitment agencies. Whether you are looking for a full-time position or freelance opportunities, Phyzioline Jobs is your gateway.') }}
                            </p>
                            <a href="{{ route('web.jobs.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                                {{ __('Browse Jobs') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- start Physiotherapy courses -->

        <!-- Courses Section -->
        <section id="courses" class="py-5" style="background: white;">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                         <img src="{{ asset('web/assets/images/8ce3145515c33b8e9c71fb534838b36c.png') }}" class="img-fluid" alt="Courses">
                    </div>
                    <div class="col-lg-6">
                        <span class="text-uppercase font-weight-bold" style="color: #02767F; letter-spacing: 2px;">{{ __('Education') }}</span>
                        <h2 class="display-4 font-weight-bold mb-4" style="color: #36415a;">
                            {{ __('Continuous') }} <br>
                            {{ __('Learning Hub') }}
                        </h2>
                        <p class="lead text-muted mb-4">
                            {{ __('Stay ahead in your career with accredited physiotherapy courses. Learn from global experts and gain new certifications directly through our platform.') }}
                        </p>
                        <a href="{{ route('web.courses.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" style="background-color: #02767F; border-color: #02767F;">
                            {{ __('Start Learning') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- end Physiotherapy courses -->
        <!-- about-section - start
      ================================================== -->
        <section id="about" class="about-section clearfix my-5">
            <div class="container">
                <div class="about-title text-center mt-5 mb-2">
                    <h1 class="title-text" style="color: #36415a">Who We Are</h1>
                </div>
                <div class="row justify-content-center d-flex align-items-center">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <div class="section-title mb-60">
                            <ul>
                                <h1 class="text-light-green">
                                    Phyzioline
                                </h1>
                                <li style="font-size: 18px" class="mt-1">
                                    Phyzioline is a forward-thinking software company dedicated
                                    to transforming the physical therapy and rehabilitation
                                    industry
                                </li>
                                <li style="font-size: 18px" class="mt-1">
                                    We offer a diverse range of digital solutions tailored to
                                    meet the evolving needs of therapists, students, and
                                    healthcare institutions.
                                </li>
                                <li style="font-size: 18px" class="mt-1">
                                    Our platform encompasses services such as virtual
                                    counseling, case management tools, educational courses, job
                                    matching, device retail and rentals, data analytics, and
                                    targeted advertising.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                        <div class="section-image text-center mb-60">
                            <img src="{{ asset('web/assets/images/WhatsApp Image 2025-02-07 at 15.13.29_0eded989 2.svg') }}"
                                alt="image_not_found" width="75%" />
                            <h5 style="color: #36415A;" class="mt-3">Dr Mahmoud Mosbah</h5>
                            <p>CEO</p>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="mission">
                        <div class="mission-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 style="color: #36415A; display: inline;" class="mx-2">{{ __('Mission') }}</h3>
                        <p class="text-about">
                            <strong>{{ __('All Physical Therapist Needs is Our Mission From PT to PT') }}</strong>
                        </p>
                        <p class="text-about">
                            {{ __('Phyzioline is dedicated to revolutionizing the physical therapy landscape by providing cutting-edge digital solutions that empower clinics, therapists, and patients. We strive to enhance care delivery through seamless case management, data-driven insights, comprehensive educational resources, and innovative service offerings that promote wellness and rehabilitation.') }}
                        </p>
                        <p class="text-about">
                            {{ __('Our comprehensive platform includes:') }}
                        </p>
                        <ul class="text-about" style="list-style: disc; margin-left: 20px;">
                            <li>{{ __('Shop: Professional physical therapy products and medical equipment') }}</li>
                            <li>{{ __('Home Visits: Expert therapists available for home-based care') }}</li>
                            <li>{{ __('Clinic ERP: Complete clinic management system with EMR, scheduling, and billing') }}</li>
                            <li>{{ __('Courses: Specialized training and continuing education for PT professionals') }}</li>
                            <li>{{ __('Jobs: Career opportunities and job matching in physical therapy') }}</li>
                            <li>{{ __('Feed: Latest news, articles, and community updates') }}</li>
                            <li>{{ __('Data Hub: Global PT statistics, licensing requirements, and professional insights') }}</li>
                        </ul>
                    </div>
                    <div class="vision">
                        <div class="vision-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 style="color: #36415A; display: inline;" class="mx-2">Vision</h3>
                        <p class="text-about">
                            Our vision is to be the leading global platform for physical
                            therapy management, education, and analytics. We aim to create a
                            connected ecosystem where technology and healthcare unite to
                            improve patient outcomes, optimize clinic operations, and foster
                            continuous learning for professionals in the field.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- about-section - end
       ================================================== -->
    </main>
    <script>
    $(document).ready(function () {
        $('#searchInput').on('input', function () {
            let query = $(this).val().toLowerCase();

            // لو المستخدم كتب حاجة، فلتر المنتجات
            if (query.length > 0) {
                $('.product-item').each(function () {
                    let name = $(this).data('name');
                    if (name.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                // لو خلى البحث فاضي، رجّع كل المنتجات
                $('.product-item').show();
            }
        });
    });
</script>
@endsection



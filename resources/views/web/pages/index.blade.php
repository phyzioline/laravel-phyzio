@extends('web.layouts.app')

@push('meta')
    <meta name="description" content="{{ __('Phyzioline is the leading medical platform for physical therapy products, home visits, clinic management, and education.') }}">
    <meta name="keywords" content="Phyzioline, Who we are, Medical Platform, من نحن, فيزيولاين, Physical Therapy, علاج طبيعي, Medical Equipment, Clinic ERP">
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
</style>
@endpush

@section('content')
    <main>
        <!-- slider-section - start
       ================================================== -->
        <section id="slider-section" class="slider-section breadCumbNewPh clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 35vh">
                <div class="container">

                    <div class="d-flex align-items-center">
                        <h1 class="hero-text text-white mb-2" style="font-size : 40px; text-align : left">Physicaltherapy Software Solutions</h1>

                    </div>
                    <div class="hero-size">
                        <h5 class="hero-praph text-white">All Physical Therapist Needs is Our Mission
                            From PT to PT </h5>
                    </div>


                </div>


            </div>
        </section>
        <!-- slider-section - end
       ================================================== -->

        <!-- Ecosystem Section - Start -->
        <section class="ecosystem-section py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="font-weight-bold" style="color: #04b8c4;">Our Ecosystem</h2>
                    <p class="text-muted">Comprehensive solutions for the physiotherapy community</p>
                </div>
                <div class="row">
                    <!-- Appointments -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                            <div class="icon-box mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background-color: rgba(8, 204, 219, 0.1);">
                                <i class="las la-user-md" style="font-size: 32px; color: #04b8c4;"></i>
                            </div>
                            <h5 class="font-weight-bold mb-3">Home Visits</h5>
                            <p class="text-muted small mb-4">Book certified physiotherapists for home sessions.</p>
                            <a href="{{ route('web.appointments.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Book Now</a>
                        </div>
                    </div>

                    <!-- Clinic ERP -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                            <div class="icon-box mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background-color: rgba(4, 184, 196, 0.1);">
                                <i class="las la-clinic-medical" style="font-size: 32px; color: #04b8c4;"></i>
                            </div>
                            <h5 class="font-weight-bold mb-3">Clinic ERP</h5>
                            <p class="text-muted small mb-4">Manage your clinic with our complete software solution.</p>
                            <a href="{{ route('web.erp.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Manage Clinic</a>
                        </div>
                    </div>

                    <!-- Learning -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                            <div class="icon-box mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background-color: rgba(4, 184, 196, 0.1);">
                                <i class="las la-graduation-cap" style="font-size: 32px; color: #04b8c4;"></i>
                            </div>
                            <h5 class="font-weight-bold mb-3">Learning Hub</h5>
                            <p class="text-muted small mb-4">Advance your career with specialized courses.</p>
                            <a href="{{ route('web.courses.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Start Learning</a>
                        </div>
                    </div>

                    <!-- Data Hub -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                            <div class="icon-box mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background-color: rgba(20, 179, 191, 0.1);">
                                <i class="las la-globe" style="font-size: 32px; color: #04b8c4;"></i>
                            </div>
                            <h5 class="font-weight-bold mb-3">Data Hub</h5>
                            <p class="text-muted small mb-4">Global insights and licensing requirements.</p>
                            <a href="{{ route('web.datahub.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Explore Data</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Ecosystem Section - End -->

        <!-- search-section - start
       ================================================== -->
        <section class="mt-5">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-10">
                        <form action="{{ route('web.shop.search') }}" method="GET" class="search-bar-form">
                            <div class="search-bar-wrapper d-flex align-items-center">
                                <input type="search" 
                                       name="search" 
                                       value="{{ old('search') }}"
                                       placeholder="Search for products, categories..." 
                                       class="form-control search-input-main" 
                                       style="flex: 1; padding: 15px 20px; font-size: 16px; border: 2px solid #02767F; border-radius: 50px 0 0 50px; border-right: none;" />
                                <button type="submit" class="search-submit-btn-main" 
                                        style="padding: 15px 30px; background: #02767F; color: white; border: 2px solid #02767F; border-radius: 0 50px 50px 0; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="las la-search" style="font-size: 20px;"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- search-section - end
       ================================================== -->

        <!-- shop-section - start
       ================================================== -->
        <section class="shop-section sec-ptb-100 decoration-wrap clearfix">
            <div class="container">
                <div class="section-title text-center">
                    <h2 class="mb-3" style="color: #36415a">Product By Category</h2>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="text" id="searchInput" name="search" placeholder="Search" class="form-control searchInput mb-30">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mx-auto">
                        <label for="categoryFilter" class="form-label fw-semibold" style="font-size: 18px; color: #36415a; display: block; margin-bottom: 10px;">
                            <i class="las la-filter"></i> Filter by Category
                        </label>
                        <select id="categoryFilter" class="form-select category-dropdown" style="padding: 12px; font-size: 16px; border: 2px solid #02767F; border-radius: 8px; cursor: pointer;">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="tab-{{ $category->id }}">
                                    {{ $category->{'name_' . app()->getLocale()} }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tab-content">
                    @foreach ($categories as $index => $category)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="tab-{{ $category->id }}"
                            role="tabpanel">
                            <div class="row justify-content-center mb-70">
                                @foreach ($products->where('category_id', $category->id) as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 product-item"
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
                                                                        alt="image_not_found" class="physio-product-img" />
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
        <li>
            <button type="button" class="buy-now-btn" data-product-id="{{ $product->id }}"
                data-toggle="tooltip" data-placement="top" title="Buy Now">
                <i class="las la-bolt" style="font-size:18px"></i>
            </button>
        </li>
    </ul>
</div>
<script>
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
</script>

                                            </div>

                                            <div class="item-content physio-content-wrapper">
                                                <h3 class="item-title physio-product-title">
                                                    <a href="{{ route('product.show', $product->id) }}">
                                                        {{ $product->{'product_name_' . app()->getLocale()} }}
                                                    </a>
                                                </h3>
                                                
                                                {{-- Vendor Badge --}}
                                                <div class="vendor-badge mb-2" style="font-size: 11px; color: #666;">
                                                    <i class="fa fa-store" style="color: #02767F; font-size: 10px; margin-right: 4px;"></i>
                                                    <span style="color: #02767F; font-weight: 600;">{{ $product->sold_by_name }}</span>
                                                </div>

                                                <div class="price-order-wrapper d-flex justify-content-between align-items-center mb-3">
                                                    <span class="item-price physio-product-price">{{ $product->product_price }} EGP</span>
                                                    <button type="button" class="btn btn-order-now" data-product-id="{{ $product->id }}" 
                                                            style="background: linear-gradient(135deg, #02767F, #04b8c4); color: white; border: none; padding: 8px 15px; border-radius: 20px; font-weight: 600; font-size: 13px; white-space: nowrap;">
                                                        <i class="las la-shopping-cart"></i> اطلب الآن
                                                    </button>
                                                </div>
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
                        </div>
                    @endforeach

                    <div class="btn-wrap text-center clearfix">
                        <a href="{{ route('show') }}" class="btn physio-view-all-btn">VIEW ALL</a>
                    </div>
                </div>
            </div>

            <span class="decoration-image pill-image-1">
                <img src="{{ asset('web/assets/images/decoration/pill_2.png') }}" alt="pill_image_not_found" />
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
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector(".searchInput");
        const productItems = document.querySelectorAll(".product-item");
        const tabPanes = document.querySelectorAll(".tab-pane");
        const tabsNav = document.querySelector(".tabs-nav");

        searchInput.addEventListener("keyup", function () {
            const query = this.value.toLowerCase().trim();

            // console.log("قيمة البحث:", query);

           if (query.length > 0) {
                // إظهار كل التبويبات
                tabPanes.forEach((pane) => {
                    pane.classList.add('show', 'active');
                    pane.style.display = 'block'; // لو في CSS بيخفيهم
                });

                // إخفاء شريط التبويبات
                if (tabsNav) {
                    tabsNav.style.display = 'none';
                }

                // فلترة المنتجات
                productItems.forEach((item) => {
                    const name = item.dataset.name || "";
                    if (name.includes(query)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            } else {
                // رجوع للوضع الطبيعي
                tabPanes.forEach((pane, index) => {
                    if (index === 0) {
                        pane.classList.add('show', 'active');
                        pane.style.display = 'block';
                    } else {
                        pane.classList.remove('show', 'active');
                        pane.style.display = 'none';
                    }
                });

                productItems.forEach((item) => {
                    item.style.display = 'block';
                });

                if (tabsNav) {
                    tabsNav.style.display = 'flex';
                }
            }
        });
    });

    // Category Dropdown Handler
    document.addEventListener("DOMContentLoaded", function() {
        const categoryFilter = document.getElementById('categoryFilter');
        
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const selectedTab = this.value;
                const tabPanes = document.querySelectorAll('.tab-pane');
                
                // Hide all tab panes
                tabPanes.forEach(function(pane) {
                    pane.classList.remove('show', 'active');
                });
                
                if (selectedTab === '') {
                    // Show first tab if "All Categories" selected
                    if (tabPanes.length > 0) {
                        tabPanes[0].classList.add('show', 'active');
                    }
                } else {
                    // Show selected tab
                    const selectedPane = document.getElementById(selectedTab);
                    if (selectedPane) {
                        selectedPane.classList.add('show', 'active');
                    }
                }
            });
        }
    });
</script>

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

        <section id="privateCases" class="my-5 section-PrivateCases">
            <div class="container bg-cases">
                <div class="row justify-content-center d-flex align-items-center  ">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 img-offers">
                        <img style="max-width: 100%;" src="{{ asset('web/assets/images/37452044_8502937 1.svg') }}"
                            width="90%" alt="">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-offers">
                        <h1 class="text-PrivateCases">Find a <br> Private Cases</h1>
                    </div>

                </div>
                <div class="d-flex justify-content-start title-offers mt-5 ">
                    <span class="phrgraph-job">We offer specialized treatment for a variety of conditions. Our expert team
                        is
                        dedicated to providing the best therapies to improve your quality of life and restore your movement
                        naturally. Get ready to return to activity with comfort and safety.</span>
                </div>

            </div>
            <section data-aos="fade-up" class="agency-list-banner" data-aos-duration="3000">
                <div class="agency-list">
                    <div class="slider">
                        <div class="slide-track">
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>


                        </div>
                    </div>
                </div>
            </section>
            <section data-aos="fade-up" class="agency-list-banner2" data-aos-duration="3000">
                <div class="agency-list">
                    <div class="slider">
                        <div class="slide-track">
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>


                        </div>
                    </div>
                </div>
            </section>

        </section>

        <section id="System" class="section-container">
            <div class="container bg-case">
                <div class="row justify-content-center d-flex align-items-center p-2">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-offers">
                        <h1 class="element-offers">CLINIC MANAGEMENT SYSTEMS</h1>
                        <h4 class="element-offer">by Phyzioline</h4>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 img-offers">
                        <img style="max-width: 100%;"
                            src="{{ asset('web/assets/images/Untitled-1سسس_3-removebg-preview.png') }}" width="90%"
                            alt="">
                    </div>
                </div>
                <div style="width: 800px; max-width: 100%;" class="d-flex justify-content-start title-offers m-0">
                    <span class="span-offers">
                        Empowering Physical Therapy Through Innovation Where Healing Meets Technology, and Possibilities are
                        Redefined
                    </span>
                </div>

            </div>
            <button class="custom-button">SOON THE OPENING</button>

        </section>

        <section id="jobs" class="section-PrivateCases">
            <div class="container bg-cases">
                <div class="row justify-content-center d-flex align-items-center  ">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 img-offers">
                        <img style="max-width: 100%;" src="{{ asset('web/assets/images/37452044_8502937 1.svg') }}"
                            width="90%" alt="">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-offers">
                        <h1 class="text-jobs">Physiotherapy JObS</h1>
                        <h4 style="color: #02767F ;">by Phyzioline</h4>
                    </div>

                </div>
                <div class="d-flex justify-content-start title-offers mt-5 ">
                    <span class="phrgraph-job">Soon, job opportunities in the field of physiotherapy will be open! If
                        you're
                        interested, you'll have the chance to work with specialists to provide treatments that help improve
                        patients' movement and physical functions. Stay tuned for more details coming soon!</span>
                </div>

            </div>
            <section data-aos="fade-up" class="agency-list-banner" data-aos-duration="3000">
                <div class="agency-list">
                    <div class="slider">
                        <div class="slide-track">
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>


                        </div>
                    </div>
                </div>
            </section>
            <section data-aos="fade-up" class="agency-list-banner2" data-aos-duration="3000">
                <div class="agency-list">
                    <div class="slider">
                        <div class="slide-track">
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>
                            <div class="slide">
                                <h3><i class="bi bi-asterisk"></i> Coming Soon | </h3>
                            </div>


                        </div>
                    </div>
                </div>
            </section>

        </section>

        <!-- start Physiotherapy courses -->

        <section id="courses" class="section-Physiotherapy">
            <div class="container div-Physiotherapy">
                <div class="element-img">
                    <img style="max-width: 100%;"
                        src="{{ asset('web/assets/images/8ce3145515c33b8e9c71fb534838b36c.png') }}" width="100%"
                        alt="">
                </div>
                <div class="text-Physiotherapy">
                    <h4 class="element-Physiotherapy">Physiotherapy<br>courses</h4>
                    <h6 class="element-coures">by Phyzioline</h6>
                    <div class="phrgraph-Physiotherapy">
                        <p>Soon, we will be offering free and subsidized Physiotherapy courses!</p>
                    </div>
                </div>
                <div class="img-Physiotherapy">
                    <img src="{{ asset('web/assets/images/Soon.svg') }}" alt="">
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
                        <h3 style="color: #36415A; display: inline;" class="mx-2">Mission</h3>
                        <p class="text-about">
                            *Phyzioline* is to revolutionize the physical therapy landscape by
                            providing cutting-edge digital solutions that empower clinics,
                            therapists, and patients. We strive to enhance care delivery
                            through seamless case management, data-driven insights,
                            comprehensive educational resources, and innovative service
                            offerings that promote wellness and rehabilitation.
                        </p>
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



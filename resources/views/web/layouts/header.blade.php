<style>
/* أيقونة السلة وعدد المنتجات */
.btn-cart {
    position: relative;
    background: transparent;
    border: none;
    font-size: 1.6rem;
    color: #fff;
    cursor: pointer;
}
  
.header-section .btns-group > ul > li > button.btn-cart .cart-counter, .header-section .btns-group > ul > li > button.mobile-btn-cart .cart-counter {
    top: -5px;
    right: -10px;
    height: 24px;
    color: #ffffff;
    font-size: 10px;
    min-width: 24px;
    line-height: 20px;
    position: absolute;
    border-radius: 45px;
    display: inline-block;
    background: #28a745;
    padding: 2px 6px;
  }

.cart-counter {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #28a745;
    color: #fff;
    font-size: 0.75rem;
    font-weight: bold;
    padding: 3px 6px;
    border-radius: 50%;
    min-width: 20px;
    text-align: center;
}

/* Admin Dashboard Button Styling */
.admin-dashboard-btn {
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    padding: 6px 14px !important;
    transition: all 0.3s ease;
}

.admin-dashboard-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* الصندوق المنسدل */
.cart-dropdown {
    width: 320px;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
    overflow: hidden;
}

/* عنوان القائمة */
.cart-dropdown .title-text {
    font-weight: bold;
    font-size: 1rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 8px;
    margin-bottom: 10px;
    display: block;
}

/* العناصر داخل السلة */
.cart-items-list ul {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 250px;
    overflow-y: auto;
}

.cart-items-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    border-bottom: 1px solid #f3f3f3;
    padding-bottom: 8px;
}

.item-image img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.item-content {
    flex: 1;
}

.item-title {
    font-size: 0.9rem;
    font-weight: 600;
    display: block;
    color: #333;
}

.item-price {
    font-size: 0.85rem;
    color: #28a745;
}

/* زر الحذف */
.remove-btn {
    background: transparent;
    border: none;
    color: #dc3545;
    font-size: 1.2rem;
    cursor: pointer;
}

.remove-btn:hover {
    color: #a71d2a;
}

/* زر عرض السلة */
.btn.bg-default-black {
    background: #222;
    color: #fff;
    font-weight: bold;
    border-radius: 6px;
    padding: 8px 15px;
    text-decoration: none;
}

.btn.bg-default-black:hover {
    background: #000;
}

/* Hero Background for Header - All Pages */
#header-section {
    background-image: url('{{ asset('web/assets/images/hero-bg.png') }}');
    background-size: cover;
    background-position: center top;
    background-repeat: no-repeat;
    padding: 0px 0; /* Minimized padding for reduced height */
}

#header-section .content-wrap {
    padding: 0px 0; /* Reduced padding */
}

/* Admin/Dashboard Button Enhancement */
.admin-dashboard-btn {
    font-size: 14px !important;
    font-weight: 700 !important;
    padding: 6px 18px !important;
}

#header-section.stuck {
    background-image: url('{{ asset('web/assets/images/hero-bg.png') }}');
    background-position: center top;
}

/* Make header and hero section blend seamlessly */
#slider-section,
.slider-section {
    margin-top: 3 !important;
}

#slider-section .item,
.slider-section .item {
    background-position: center bottom !important;
}

/* =========================
 CLEAN HEADER UI IMPROVEMENTS
========================= */

/* Make Header Transparent on Home */
body.has-hero #header-section {
    background: transparent !important;
    box-shadow: none !important;
}

/* Global Header Style */
#header-section {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10000;
    padding: 10px 0 !important;
    transition: 0.3s ease-in-out;
}

/* Sticky Effect on Scroll */
#header-section.stuck {
    background: #02767F !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

/* Make Layout Always in One Row */
#header-section .row {
    width: 100%;
    align-items: center !important;
    display: flex !important;
    flex-wrap: nowrap !important;
}

/* Bigger Logo */
.brand-logo img {
    width: 80% !important;
    max-height: 70px !important;
    object-fit: contain;
}

/* Menu Centered */
.main-menu ul {
    display: flex !important;
    gap: 28px;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap !important;
}

/* Move Icons to The Right */
#header-section .col-lg-3 {
    display: flex !important;
    justify-content: flex-end !important;
}

/* Fix Buttons Alignment - optimized for clean layout */
#header-section .btns-group {
    display: flex !important;
    align-items: center !important;
    height: 100% !important;
}

#header-section .btns-group ul {
    display: flex !important;
    flex-wrap: nowrap !important;
    gap: 10px !important;
    align-items: center !important;
    justify-content: flex-end !important;
    margin: 0 !important;
    padding: 0 !important;
    height: auto !important;
    list-style: none !important;
}

/* Ensure all button items are aligned on same line */
#header-section .btns-group ul li {
    display: flex !important;
    align-items: center !important;
    vertical-align: middle !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Align buttons vertically with menu */
#header-section .btns-group ul li button,
#header-section .btns-group ul li .btn-cart {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    vertical-align: middle !important;
    line-height: 1 !important;
    padding: 4px 6px !important;
}

/* Header Icons - Always white */
.btn-cart,
.main-menu i,
.btn-login {
    color: #fff !important;
}

/* Styling Menu Links - Always white */
#header-section .main-menu ul li a {
    position: relative;
    font-size: 15px;
    color: #fff !important;
    padding: 6px 8px;
    font-weight: 600;
}

/* Underline Animation */
#header-section .main-menu ul li a::after {
    content:"";
    position:absolute;
    left:0;
    bottom:-3px;
    width:0%;
    height:3px;
    background:#FFD700;
    transition: .3s ease;
}

#header-section .main-menu ul li a:hover,
#header-section .main-menu ul li a.active {
    color:#FFD700 !important;
}

#header-section .main-menu ul li a:hover::after,
#header-section .main-menu ul li a.active::after {
    width:100%;
}

/* Fix Hero Gap – Remove White Space */
body.has-hero .hero-banner,
body.has-hero .slider-section,
body.has-hero .shop-hero-banner {
    margin-top: -120px !important;
    padding-top: 220px !important;
    background-position: top center !important;
}
/* =========================
   DESKTOP ONLY ALIGNMENT RULES
   ========================= */
@media (min-width: 992px) {

    /* NEW HEADER FIX - Alignment Improved */

    /* جعل كل الـ ROW Flex مضبوط */
    #header-section .row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
    }

    /* توزيع الأعمدة: 
       اللوجو صغير – الروابط أكبر – الأيقونات آخر اليمين */
    #header-section .col-lg-2 {
        flex: 0 0 auto !important;
    }
    #header-section .col-lg-7 {
        flex: 1 1 auto !important;
        display: flex !important;
        justify-content: center !important;
    }
    #header-section .col-lg-3 {
        flex: 0 0 auto !important;
        display: flex !important;
        justify-content: flex-end !important;
    }

    /* تصغير المسافة بين عناصر المنيو */
    .main-menu ul {
        gap: 20px !important;
    }

    /* الأيقونات تلزق أقصى اليمين */
    .btns-group ul {
        gap: 18px !important;
        justify-content: flex-end !important;
    }

    /* شفاف للهوم فقط */
    body.has-hero #header-section {
        background: transparent !important;
    }

    /* لون عند السّكرول فقط */
    #header-section.stuck {
        background-color: #02767F !important;
    }
}

/* ======================
 FINAL HEADER LAYOUT FIX (DESKTOP ONLY)
====================== */
@media (min-width: 992px) {

    /* Force full-width alignment */
    #header-section .container {
        max-width: 100% !important;
        padding: 0 30px !important;
        overflow: visible !important;
    }

    /* Ensure columns don't shrink or wrap */
    #header-section .col-lg-2,
    #header-section .col-lg-7,
    #header-section .col-lg-3 {
        flex-shrink: 0 !important;
        overflow: visible !important;
        white-space: nowrap !important;
    }

    /* LOGO: stick fully left */
    #header-section .col-lg-2 {
        display: flex !important;
        justify-content: flex-start !important;
        align-items: center !important;
    }

    /* MENU CENTERED - optimized alignment */
    #header-section .col-lg-7 {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        padding: 0 !important;
    }

    /* Ensure menu container is properly aligned */
    #header-section .main-menu {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* ICONS: stick fully right - optimized alignment */
    #header-section .col-lg-3 {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        height: auto !important;
        padding: 0 !important;
    }

    /* reduce spacing between menu items - optimized for clean layout */
    .main-menu ul {
        gap: 8px !important;
    }

    /* Hero goes under header — remove big gap */
    body.has-hero .hero-banner,
    body.has-hero .slider-section,
    body.has-hero .shop-hero-banner {
        margin-top: -140px !important;
        padding-top: 260px !important;
    }


    /* Increase Hero Section Height */
    #slider-section .item,
    .slider-section .item,
    .hero-banner {
        min-height: 480px !important; /* زوّد الرقم لو عايز أكبر */
        padding-top: 180px !important; /* يرفع الكلام لتحت شوية */
    }

    /* Improve text visibility spacing */
    #slider-section .item h1,
    .hero-banner h1 {
        font-size: 42px !important;
        line-height: 1.25 !important;
    }

    #slider-section .item p,
    .hero-banner p {
        font-size: 18px !important;
        margin-top: 10px !important;
    }
    /* Make menu text smaller & cleaner - optimized for clean layout */
    #header-section .main-menu ul li {
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
    }

    #header-section .main-menu ul li a {
        font-size: 13px !important;
        padding: 4px 8px !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        display: inline-block !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
        margin: 0 !important;
    }

    /* Fix spacing between items after text shrink - optimized spacing */
    .main-menu ul {
        gap: 6px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-wrap: nowrap !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
    }

    /* Prevent text wrapping in header menu items - Fix for laptop 100% scale */
    #header-section .main-menu ul li {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        height: auto !important;
        margin: 0 !important;
    }

    #header-section .main-menu ul li a {
        white-space: nowrap !important;
        display: inline-block !important;
        word-break: keep-all !important;
        overflow: visible !important;
        text-overflow: clip !important;
        vertical-align: middle !important;
        line-height: 1.2 !important;
    }

    /* Ensure menu stays in one row */
    #header-section .main-menu ul {
        flex-wrap: nowrap !important;
        overflow: visible !important;
        white-space: nowrap !important;
    }

    /* Prevent header row from wrapping */
    #header-section .row {
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
        align-items: center !important;
        margin: 0 !important;
        height: auto !important;
    }

    /* Ensure menu column is properly sized */
    #header-section .col-lg-7 {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: auto !important;
        overflow: visible !important;
    }

    /* Ensure buttons don't wrap */
    #header-section .btns-group ul {
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
    }

    #header-section .btns-group ul li {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        height: auto !important;
    }
}

/* FORCE MENU, LOGIN, LOGO IN SAME ROW (RIGHT → LEFT) */
.header-top-controls {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 10px !important;
    width: 100%;
}

/* Ensure logo link is clickable and works properly */
.header-top-controls .brand-link {
    display: block !important;
    flex-shrink: 0 !important;
    z-index: 10 !important;
    position: relative !important;
    text-decoration: none !important;
    pointer-events: auto !important;
    cursor: pointer !important;
}

.header-top-controls .brand-link:hover {
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.header-top-controls .brand-link img {
    pointer-events: auto !important;
    cursor: pointer !important;
}

/* Make menu icon visible always - reduced size for better spacing */
.mobile-menu-btn {
    display: block !important;
    font-size: 20px !important;
    color: #fff !important;
    background: none;
    border: none;
    padding: 4px 8px !important;
    cursor: pointer;
    flex-shrink: 0 !important;
    z-index: 5 !important;
    position: relative !important;
}

/* Fix Login Button Visibility */
.btn-login {
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 6px 16px !important;
    background: #ffffff20;
    color: #fff !important;
    border-radius: 6px;
    font-weight: 700 !important;
    border: 1px solid #ffffff55;
}

.btn-login:hover {
    background: #fff !important;
    color: #02767F !important;
}

/* Hide old mobile login rules */
@media(max-width: 992px) {
    .btn-login {
        display: flex !important;
    }
}

/* Move logo to the far left */
.brand-logo {
    margin-left: auto !important;
}

/* Track Your Order Button (Mobile) - reduced size for better spacing */
.btn-track-order-mobile {
    display: flex !important;
    align-items: center;
    justify-content: center;
    padding: 6px 12px !important;
    background: rgba(255, 255, 255, 0.25) !important;
    border: 2px solid rgba(255, 255, 255, 0.4) !important;
    border-radius: 20px !important;
    color: #fff !important;
    text-decoration: none;
    font-size: 11px !important;
    font-weight: 700;
    margin-left: 5px !important;
    transition: all 0.3s ease;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    flex-shrink: 0 !important;
}

.btn-track-order-mobile:hover {
    background: rgba(255, 255, 255, 0.35) !important;
    border-color: rgba(255, 255, 255, 0.6) !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-track-order-mobile:active {
    transform: translateY(0);
}

.btn-track-order-mobile i {
    font-size: 18px;
    margin-right: 6px;
}

.track-order-text-mobile {
    display: inline !important;
    font-weight: 700;
}

/* Show icon and text on all mobile screens */
@media (max-width: 991px) {
    .btn-track-order-mobile {
        display: flex !important;
    }
}

/* Hide on desktop - only show in mobile header controls */
@media (min-width: 992px) {
    .btn-track-order-mobile {
        display: none !important;
    }
}

/* Fix header text alignment for laptop screens at 100% zoom */
@media (min-width: 992px) and (max-width: 1920px) {
    /* Ensure menu items stay in place */
    #header-section .main-menu ul li a {
        white-space: nowrap !important;
        display: inline-block !important;
        word-break: keep-all !important;
        overflow: visible !important;
        line-height: 1.4 !important;
        padding: 6px 10px !important;
    }
    
    /* Prevent any wrapping in header */
    #header-section .row {
        flex-wrap: nowrap !important;
        overflow: visible !important;
        align-items: center !important;
    }
    
    /* Ensure proper spacing without breaking - optimized for clean layout */
    #header-section .main-menu ul {
        gap: 8px !important;
        flex-wrap: nowrap !important;
        justify-content: center !important;
        align-items: center !important;
        display: flex !important;
    }
    
    /* Make sure buttons stay aligned with consistent spacing - single line */
    #header-section .btns-group ul {
        gap: 8px !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        display: flex !important;
        height: auto !important;
    }
    
    /* Ensure all icons/buttons are on same vertical line */
    #header-section .btns-group ul li {
        display: flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        height: auto !important;
    }
    
    #header-section .btns-group ul li button,
    #header-section .btns-group ul li .btn-cart {
        display: flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        line-height: 1 !important;
        padding: 3px 6px !important;
    }
    
    /* Ensure all header elements are vertically centered */
    #header-section .content-wrap {
        align-items: center !important;
        padding: 8px 0 !important;
    }
    
    /* Better vertical alignment for menu items */
    #header-section .main-menu ul li {
        display: flex !important;
        align-items: center !important;
        vertical-align: middle !important;
    }
    
    #header-section .main-menu ul li a {
        vertical-align: middle !important;
        line-height: 1.2 !important;
        padding: 3px 8px !important;
    }
    
    /* Better column distribution */
    #header-section .col-lg-2 {
        flex: 0 0 15% !important;
        max-width: 15% !important;
    }
    
    #header-section .col-lg-7 {
        flex: 0 0 55% !important;
        max-width: 55% !important;
    }
    
    #header-section .col-lg-3 {
        flex: 0 0 30% !important;
        max-width: 30% !important;
    }
}

</style>

<header id="header-section" class="header-section header-primary sticky-header clearfix">
    <div class="content-wrap d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">

                {{-- Logo --}}
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <div class="brand-logo clearfix">
    <div class="header-top-controls">

        <!-- MENU BUTTON -->
        <button type="button" class="mobile-menu-btn">
            <i class="las la-bars"></i>
        </button>

        <!-- TRACK YOUR ORDER BUTTON (Mobile) - Always visible for all users -->
        @php
            $trackUrl = route('history_order.index.' . app()->getLocale());
            // If guest just placed an order, add order number and email to URL
            if (!Auth::check() && (session('success') || session('guest_order'))) {
                $guestOrder = session('guest_order');
                if ($guestOrder && isset($guestOrder->order_number)) {
                    $trackUrl = route('history_order.index.' . app()->getLocale()) . '?order_number=' . $guestOrder->order_number . '&email=' . ($guestOrder->email ?? '');
                }
            }
        @endphp
        <a href="{{ $trackUrl }}" 
           class="btn-track-order-mobile" 
           title="{{ __('Track Your Order') }}">
            <i class="las la-shipping-fast"></i>
            <span class="track-order-text-mobile">{{ __('Track Order') }}</span>
        </a>

        <!-- LOGIN BUTTON REMOVED (Duplicate) -->
        
        <!-- LOGO -->
        <a href="{{ '/' . app()->getLocale() }}" class="brand-link" style="margin-left: auto; display: block; text-decoration: none;">
            <img src="{{ asset('web/assets/images/main_logo_white.png') }}"
                 alt="Phyzioline Logo"
                 width="150"
                 height="45"
                 loading="eager"
                 style="max-height: 45px; object-fit: contain; display: block;">
        </a>

    </div>
</div>



                </div>

                {{-- Main Menu --}}
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <nav class="main-menu ul-li-center clearfix">
                        <ul class="clearfix">
                            <li><a href="{{ route('show') }}" class="text-decoration-none">{{ __('Shop') }}</a></li>
                            <li><a href="{{ route('web.home_visits.index') }}" class="text-decoration-none">{{ __('Home Visits') }}</a></li>
                            <li><a href="{{ route('web.erp.index') }}" class="text-decoration-none">{{ __('Clinic ERP') }}</a></li>
                            <li><a href="{{ route('web.courses.index') }}" class="text-decoration-none">{{ __('Courses') }}</a></li>
                            <li><a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="text-decoration-none">{{ __('Jobs') }}</a></li>
                            <li><a href="{{ route('feed.index') }}" class="text-decoration-none">{{ __('Feed') }}</a></li>
                            <li><a href="{{ route('web.datahub.index.' . app()->getLocale()) }}" class="text-decoration-none">{{ __('Data Hub') }}</a></li>
                            <li>
                                <a href="{{ route('history_order.index.' . app()->getLocale()) }}" class="text-decoration-none">{{ __('Track Your Order') }}</a>
                            </li>
                           	 @if (Auth::check())
                                  <li>
                                      <a href="{{ route('history_order.index.' . app()->getLocale()) }}" class="text-decoration-none">{{ __('Order History') }}</a>
                                  </li>
                            @endif
                            <li><a href="{{ '/' . app()->getLocale() }}#about" class="text-decoration-none">{{ __('About Us') }}</a></li>
                        </ul>
                    </nav>
                </div>

                {{-- Right Buttons --}}
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="btns-group ul-li-right clearfix">
                        <ul class="clearfix">

                            {{-- Currency Switcher --}}
                            <li class="dropdown">
                                <button class="btn-cart" id="currency-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; font-weight: bold;">
                                    {{ session('currency', config('currency.default', 'EGP')) }} <i class="las la-angle-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="currency-dropdown">
                                    <form id="currency-form" action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" id="currency-input">
                                        @foreach(config('currency.currencies') as $code => $currency)
                                            <a class="dropdown-item {{ session('currency') == $code ? 'active' : '' }}" 
                                               href="#" onclick="document.getElementById('currency-input').value='{{ $code }}'; document.getElementById('currency-form').submit(); return false;">
                                                <strong>{{ $code }}</strong> - {{ $currency['symbol'] }}
                                            </a>
                                        @endforeach
                                    </form>
                                </div>
                            </li>

                            {{-- Language Switcher --}}
                            <li class="dropdown">
                                <button class="btn-cart" id="lang-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-globe"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="lang-dropdown">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            {{ $properties['native'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>

                            {{-- Wishlist --}}
                            <li>
                                <button type="button">
                                    <a class="wishlist-link" href="{{ route('favorites.index.' . app()->getLocale()) }}" data-placement="top" title="{{ __('To Wishlist') }}" aria-label="{{ __('View Wishlist') }}">
                                        <i class="la la-heart" aria-hidden="true"></i>
                                    </a>
                                </button>
                            </li>

                            {{-- Search --}}
                            <li>
                                <button type="button" class="main-search-btn" data-toggle="collapse"
                                    data-target="#main-search-collapse" aria-expanded="false"
                                    aria-controls="main-search-collapse">
                                    <i class="la la-search"></i>
                                </button>
                            </li>

                            {{-- Cart Dropdown --}}
                            <li class="dropdown">
                                <button class="btn-cart" id="cart-dropdown" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-shopping-bag"></i>
                                    @if(Auth::check())
                                        <small class="cart-counter bg-light-green">{{ App\Models\Cart::where('user_id', auth()->user()->id)->count() ?? 0 }}</small>
                                    @else
                                        <small class="cart-counter bg-light-green">0</small>
                                    @endif
                                </button>

                                <div class="cart-dropdown dropdown-menu py-3" aria-labelledby="cart-dropdown">
                                    @if(Auth::check())
                                        <span class="title-text">{{ __('Cart Items') }}:- {{ App\Models\Cart::where('user_id', auth()->user()->id)->count() ?? 0 }}</span>
                                    @else
                                        <span class="title-text">{{ __('Cart Items') }}:- 0</span>
                                    @endif

                                    <div class="cart-items-list ul-li-block clearfix">
                                        <ul class="clearfix">
                                            @if(Auth::check())
                                                @foreach (App\Models\Cart::where('user_id', Auth::id())->with('product.productImages')->get() as $cart)
                                                    <li>
                                                        <div class="item-image">
                                                            <img src="{{ asset($cart->product->productImages->first()->image ?? 'default.png') }}" 
                                                                 alt="{{ $cart->product->{'product_name_' . app()->getLocale()} ?? 'Product' }}"
                                                                 loading="lazy"
                                                                 width="80"
                                                                 height="80"
                                                                 style="aspect-ratio: 1/1; object-fit: cover;" />
                                                        </div>
                                                        <div class="item-content">
                                                            <span class="item-title">{{ $cart->product->{'product_name_' . app()->getLocale()} }}</span>
                                                            <span class="item-price">{{ number_format($cart->product->product_price, 2) }} EGP</span>
                                                        </div>
                                                        <form action="{{ route('carts.destroy.' . app()->getLocale(), $cart->id) }}" method="POST" class="remove-cart-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="remove-btn">
                                                                <i class="las la-times"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="btns-group ul-li clearfix">
                                        <ul class="clearfix">
                                            <li>
                                                <a href="{{ route('carts.index.' . app()->getLocale()) }}" class="btn bg-default-black w-100">{{ __('View Cart') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            {{-- Help Center Icon --}}
                            <li>
                                <a href="{{ route('help.index') }}" class="btn-cart" title="{{ __('Help Center') }}">
                                    <i class="las la-question-circle"></i>
                                </a>
                            </li>

                            {{-- Login/Logout --}}
                            <li class="dropdown">
                                @if (Auth::check())
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        @if(Auth::user()->hasRole('vendor') || Auth::user()->hasRole('admin') || Auth::user()->type === 'therapist')
                                            <a class="dropdown-item text-primary font-weight-bold" href="{{ Auth::user()->type === 'therapist' ? route('therapist.dashboard') : route('dashboard.home') }}">
                                                <i class="las la-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('history_order.index.' . app()->getLocale()) }}">
                                            <i class="las la-history mr-2"></i> {{ __('Order History') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('history_order.index.' . app()->getLocale()) }}">
                                            <i class="las la-shipping-fast mr-2"></i> {{ __('Track Your Order') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('favorites.index.' . app()->getLocale()) }}">
                                            <i class="la la-heart mr-2"></i> {{ __('My Wishlist') }}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="{{ route('logout.' . app()->getLocale()) }}">
                                            <i class="las la-sign-out-alt mr-2"></i> {{ __('Logout') }}
                                        </a>
                                    </div>
                                    <button class="btn-cart btn-login px-4 py-1" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px; font-weight: 700;">
                                        {{ Auth::user()->name }} <i class="las la-angle-down"></i>
                                    </button>
                                @else
                                    <a class="login-link" href="{{ route('view_login.' . app()->getLocale()) }}" aria-label="{{ __('Log In') }}">
                                        <button class="btn-cart btn-login px-4 py-1" aria-label="{{ __('Log In') }}">{{ __('Log In') }}</button>
                                    </a>
                                @endif
                            </li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Search Collapse --}}
    <div class="main-search-collapse collapse" id="main-search-collapse">
        <div class="main-search-form card">
            <div class="container">
                <form action="{{ route('web.shop.search.' . app()->getLocale()) }}" method="GET">
                    <div class="form-item">
                        <input type="search" id="searchInput" value="{{ request('search') }}" name="search" placeholder="{{ __('Search your Product') }}" />
                        <button type="submit" class="submit-btn">
                            <i class="la la-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

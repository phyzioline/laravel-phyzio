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
    padding: 2px 0; /* Reduced from default */
}

#header-section .content-wrap {
    padding: 0px 0; /* Reduced padding */
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
</style>

<header id="header-section" class="header-section header-primary sticky-header clearfix">
    <div class="content-wrap d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">

                {{-- Logo --}}
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <div class="brand-logo clearfix">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <a href="{{ route('home') }}" class="brand-link">
                                <img src="{{ asset('web/assets/images/main_logo_white.png') }}" width="60%" alt="logo_not_found" />
                            </a>

                            @if (Auth::check() && (Auth::user()->hasRole('vendor') || Auth::user()->hasRole('admin') || Auth::user()->type === 'therapist'))
                                <a href="{{ Auth::user()->type === 'therapist' ? route('therapist.dashboard') : route('dashboard.home') }}" class="btn btn-sm btn-primary text-white" style="margin-left: 10px; border-radius: 20px; padding: 5px 15px;">
                                    <i class="las la-tachometer-alt"></i>
                                    Dashboard
                                </a>
                            @endif
                        </div>

                        <div class="btns-group ul-li-right clearfix">
                            <ul class="clearfix">
                                <li>
                                    <button type="button" class="mobile-menu-btn">
                                        <i class="las la-bars"></i>
                                    </button>
                                </li>
                                <li>
                                    <button class="mobile-btn-cart">
                                        <i class="las la-shopping-bag"></i>
                                        <small class="cart-counter bg-light-green">03</small>
                                    </button>
                                </li>
                                <li>
                                    <button class="mobile-btn-cart">
                                        <i class="las la-shopping-bag"></i>
                                        <small class="cart-counter bg-light-green">03</small>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Main Menu --}}
                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                    <nav class="main-menu ul-li-center clearfix">
                        <ul class="clearfix">
                            <li><a href="{{ route('show') }}" class="text-decoration-none">Shop</a></li>
                            <li><a href="{{ route('web.appointments.index') }}" class="text-decoration-none">Appointments</a></li>
                            <li><a href="{{ route('web.erp.index') }}" class="text-decoration-none">Clinic ERP</a></li>
                            <li><a href="{{ route('web.courses.index') }}" class="text-decoration-none">Courses</a></li>
                            <li><a href="{{ route('web.datahub.index') }}" class="text-decoration-none">Data Hub</a></li>
                           	 @if (Auth::check())
                                  <li>
                                      <a href="{{ route('history_order.index') }}" class="text-decoration-none">History
                                          Order</a>
                                  </li>
                              @endif
                            <li><a href="{{ route('home') }}#about" class="text-decoration-none">About Us</a></li>
                        </ul>
                    </nav>
                </div>

                {{-- Right Buttons --}}
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <div class="btns-group ul-li-right clearfix">
                        <ul class="clearfix">

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
                                    <a style="color: #fff;" href="{{ route('favorites.index') }}" data-placement="top" title="To Wishlist">
                                        <i class="la la-heart"></i>
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
                                        <span class="title-text">Cart Items:- {{ App\Models\Cart::where('user_id', auth()->user()->id)->count() ?? 0 }}</span>
                                    @else
                                        <span class="title-text">Cart Items:- 0</span>
                                    @endif

                                    <div class="cart-items-list ul-li-block clearfix">
                                        <ul class="clearfix">
                                            @if(Auth::check())
                                                @foreach (App\Models\Cart::where('user_id', Auth::id())->with('product.productImages')->get() as $cart)
                                                    <li>
                                                        <div class="item-image">
                                                            <img src="{{ asset($cart->product->productImages->first()->image ?? 'default.png') }}" alt="image_not_found" />
                                                        </div>
                                                        <div class="item-content">
                                                            <span class="item-title">{{ $cart->product->{'product_name_' . app()->getLocale()} }}</span>
                                                            <span class="item-price">{{ number_format($cart->product->product_price, 2) }} EGP</span>
                                                        </div>
                                                        <form action="{{ route('carts.destroy', $cart->id) }}" method="POST" class="remove-cart-form">
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
                                                <a href="{{ route('carts.index') }}" class="btn bg-default-black w-100">View Cart</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            {{-- Login/Logout --}}
                            <li class="dropdown">
                                @if (Auth::check())
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        @if(Auth::user()->hasRole('vendor') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('therapist'))
                                            <a class="dropdown-item" href="{{ route('dashboard.home') }}">
                                                <i class="las la-tachometer-alt mr-2"></i> Dashboard
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                            <i class="las la-sign-out-alt mr-2"></i> Logout
                                        </a>
                                    </div>
                                    <button class="btn-cart btn-login px-4 py-1" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }} <i class="las la-angle-down"></i>
                                    </button>
                                @else
                                    <a style="color: #fff;" href="{{ route('view_login') }}">
                                        <button class="btn-cart btn-login px-4 py-1">Log In</button>
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
                <form action="{{ route('web.shop.search') }}" method="GET">
                    <div class="form-item">
                        <input type="search" id="searchInput" value="{{ old('search') }}" name="search" placeholder="Search your Product" />
                        <button type="submit" class="submit-btn">
                            <i class="la la-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="sidebar-menu-wrapper">
    <div id="sidebar-menu" class="sidebar-menu">
        <span class="close-btn">
            <i class="las la-times"></i>
        </span>

        <div class="brand-logo text-center clearfix">
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ asset('web/assets/images/Frame 131.svg') }}" alt="logo_not_found" />
            </a>
            @if (Auth::check() && Auth::user()->hasRole('vendor'))
                <a href="{{ route('dashboard.home') }}" class="btn btn">
                    <i class="las la-tachometer-alt"></i>
                    Dash
                </a>
            @endif
        </div>

        <div class="search-wrap">
            <form action="{{ route('web.shop.search') }}" method="GET">
                <div class="form-item mb-0">
                    <input type="search" id="searchInput" value="{{ old('search') }}" name="search"
                        placeholder="Search your Product" />
                    <button type="submit">
                        <i class="la la-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div id="mobile-accordion" class="mobile-accordion">
            <div class="card">
                <div class="card-header" id="heading-one">
                    <button data-toggle="collapse" data-target="#collapse-one" aria-expanded="false"
                        aria-controls="collapse-one">
                        <i class="las la-shopping-bag"></i>
                        Cart Item
                        @if (Auth::check())
                            <small>{{ App\Models\Cart::where('user_id', auth()->user()->id)->count() ?? 0 }}</small>
                        @else
                            <small>{{ 0 }}</small>
                        @endif
                    </button>
                </div>
                <div id="collapse-one" class="collapse" aria-labelledby="heading-one" data-parent="#mobile-accordion">
                    <div class="card-body">
                        <div class="cart-items-list ul-li-block clearfix">
                            <ul class="clearfix">
                                @if (Auth::check())
                                    @foreach (App\Models\Cart::where('user_id', Auth::id())->with('product.productImages')->get() as $cart)
                                        <li>
                                            <div class="item-image">
                                                <img src="{{ asset($cart->product->productImages->first()->image ?? 'default.png') }}"
                                                    alt="image_not_found" />
                                            </div>
                                            <div class="item-content">
                                                <h4 class="item-title">
                                                    {{ $cart->product->{'product_name_' . app()->getLocale()} }}
                                                </h4>
                                                <span class="item-price">${{ $cart->product->product_price }}</span>
                                            </div>
                                            <form action="{{ route('carts.destroy', $cart->id) }}" method="POST"
                                                class="remove-cart-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-btn">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </form>
                                            {{-- <button type="button" class="remove-btn">
                                                      <i class="las la-times"></i>
                                                  </button> --}}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="btns-group ul-li clearfix">
                            <ul class="clearfix">
                                <li>
                                    <a href="{{ route('carts.index') }}" class="btn bg-default-black">View Cart</a>
                                </li>
                                {{-- <li>
                                    <a href="checkout.html" class="btn bg-royal-blue">Checkout</a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="heading-two">
                    <button class="collapsed" data-toggle="collapse" data-target="#collapse-two" aria-expanded="false" aria-controls="collapse-two">
                        <i class="las la-th-large"></i>
                        Categories
                    </button>
                </div>
                <div id="collapse-two" class="collapse" aria-labelledby="heading-two" data-parent="#mobile-accordion">
                    <div class="card-body">
                        <div class="card-list-widget">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('show') }}">All Products</a></li>
                                @foreach (App\Models\Category::where('status', 'active')->get() as $category)
                                    <li>
                                        <a href="#category-{{ $category->id }}" data-toggle="collapse" aria-expanded="false" class="d-flex justify-content-between align-items-center">
                                            {{ $category->{'name_' . app()->getLocale()} }}
                                            <i class="las la-angle-down"></i>
                                        </a>
                                        <ul class="collapse list-unstyled pl-3" id="category-{{ $category->id }}">
                                            @foreach ($category->subcategories as $subcategory)
                                                <li><a href="{{ route('web.shop.category', ['id' => $subcategory->id]) }}" class="text-muted" style="font-size: 13px;">- {{ $subcategory->{'name_' . app()->getLocale()} }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="menu_list ul-li-block clearfix">
            <h3 class="widget-title">Menu List</h3>

            <ul style="height: 100vh;" class="clearfix">
                <li class="{{ Route::is('home') ? 'active' : '' }}"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="{{ Route::is('show') ? 'active' : '' }}"><a href="{{ route('show') }}">{{ __('Shop') }}</a></li>
                <li class="{{ Route::is('web.appointments.index') ? 'active' : '' }}"><a href="{{ route('web.appointments.index') }}">{{ __('Appointments') }}</a></li>
                <li class="{{ Route::is('web.erp.index') ? 'active' : '' }}"><a href="{{ route('web.erp.index') }}">{{ __('Clinic ERP') }}</a></li>
                <li class="{{ Route::is('web.courses.index') ? 'active' : '' }}"><a href="{{ route('web.courses.index') }}">{{ __('Courses') }}</a></li>
                <li class="{{ Route::is('web.datahub.index') ? 'active' : '' }}"><a href="{{ route('web.datahub.index') }}">{{ __('Data Hub') }}</a></li>
                
                @if (Auth::check())
                    <li class="{{ Route::is('history_order.index') ? 'active' : '' }}">
                        <a href="{{ route('history_order.index') }}">{{ __('Order History') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"><i class="las la-sign-out-alt"></i> {{ __('Logout') }}</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('view_login') }}"><i class="las la-sign-in-alt"></i> {{ __('Log In') }}</a>
                    </li>
                @endif
                <li><a href="{{ route('home') }}#about">{{ __('About Us') }}</a></li>
            </ul>
        </div>


    </div>
    <div class="overlay"></div>
</div>

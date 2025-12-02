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


        </div>

        <div class="menu_list ul-li-block clearfix">
            <h3 class="widget-title">Menu List</h3>

            <ul style="height: 100vh;" class="clearfix">
                <li class="active"><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('show') }}">Shop</a></li>
                <li><a href="{{ route('home') }}#privateCases">Private Cases</a></li>
                <li><a href="{{ route('home') }}#System">Our System</a></li>
                <li><a href="{{ route('home') }}#jobs">Jobs</a></li>
                <li><a href="{{ route('home') }}#courses">Courses</a></li>
                <li><a href="{{ route('home') }}#about">About Us</a></li>
                <li>
                    <a href="{{ route('logout') }}"><i class="las la-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>


    </div>
    <div class="overlay"></div>
</div>

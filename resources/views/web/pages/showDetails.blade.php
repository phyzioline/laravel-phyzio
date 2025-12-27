@extends('web.layouts.app')
@section('content')
    <main>

        <!-- breadcrumb-section - start
       ================================================== -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 35vh">
                <div class="container">
                    <div class="text-center mt-5 mb-5">
                        <h1 class="text-white hero-title-animated" style="font-size : 40px;">Product Details</h1>
                        <div class="hero-subtitle ">
                            <p class="text-white-50 hero-subtitle-animated">Discover amazing products with world-class quality</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb-section - end
       ================================================== -->

        <!-- details-section - start
       ================================================== -->
        <section class="details-section sec-ptb-100 pb-0 clearfix">
            <div class="container-fluid">

                <div class="physio-product-details-row row mb-50 justify-content-lg-between justify-content-md-between justify-content-sm-center" style="margin-bottom : 50px">
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                        <div class="physio-details-image animated-image-container">
                            <div class="details-image-carousel owl-carousel owl-theme" data-slider-id="thumbnail-carousel">

                                @foreach ($product->productImages as $img)
                                    <div class="item">
                                        <img src="{{ asset($img->image) }}" 
                                             alt="{{ $product->{'product_name_' . app()->getLocale()} }}" 
                                             class="product-main-image"
                                             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                             width="600"
                                             height="600"
                                             style="aspect-ratio: 1/1; object-fit: contain;" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="owl-thumbs clearfix" data-slider-id="thumbnail-carousel">
                                @foreach ($product->productImages as $img)
                                    <button class="item thumb-item">
                                        <span>
                                            <img src="{{ asset($img->image) }}" 
                                                 alt="{{ $product->{'product_name_' . app()->getLocale()} }} - {{ __('Thumbnail') }}"
                                                 class="thumb-image"
                                                 loading="lazy"
                                                 width="100"
                                                 height="100"
                                                 style="aspect-ratio: 1/1; object-fit: cover;" />
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                        <div class="physio-details-content pl-20 animated-content">
                            <span class="post-type mb-15 category-badge">{{ $product->category->{'name_' . app()->getLocale()} }}</span>
                            <h4 class="item-title second-color mb-15 product-title-animated" style="font-size : 27px">.{{ $product->{'product_name_' . app()->getLocale()} }}
                            </h4>
                            {{-- Review Count (Emphasized) - Amazon Style --}}
                            <div class="rating-star ul-li mb-20 clearfix">
                                <div class="d-flex align-items-center gap-2">
                                    <ul class="float-left mr-2 mb-0" style="font-size: 14px;">
                                        @php $avgRating = $product->average_rating; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                            <li class="active"><i class="las la-star"></i></li>
                                            @else
                                            <li><i class="las la-star"></i></li>
                                            @endif
                                        @endfor
                                    </ul>
                                    <span class="review-text fw-bold" style="font-size: 16px; color: #02767F;">{{ $product->review_count }} Reviews</span>
                                </div>
                            </div>
                            
                            {{-- Price + FREE Delivery + Returns Policy - Amazon Style --}}
                            <div class="price-delivery-returns mb-30">
                                <div class="d-flex align-items-baseline gap-3 mb-2">
                                    <span class="physio-item-price price-animated" style="font-size: 28px; font-weight: 700; color: #B12704;">{{ number_format($product->product_price, 2) }} EGP</span>
                                </div>
                                
                                {{-- FREE Delivery Message --}}
                                <div class="delivery-info mb-2">
                                    <span class="badge bg-success" style="font-size: 13px; padding: 6px 12px;">
                                        <i class="las la-shipping-fast me-1"></i>FREE Delivery by Phyzioline
                                    </span>
                                </div>
                                
                                {{-- Returns Policy Near Price - Amazon Style --}}
                                <div class="returns-policy mb-2">
                                    <span style="font-size: 14px; color: #007185;">
                                        <i class="las la-undo me-1"></i>
                                        <strong>30-day return</strong>, no questions asked
                                    </span>
                                </div>
                                
                                {{-- Stock Urgency - Amazon Style --}}
                                @if($product->getStockUrgencyMessage())
                                <div class="stock-urgency mb-2">
                                    <span class="badge bg-danger" style="font-size: 13px; padding: 6px 12px;">
                                        <i class="las la-exclamation-triangle me-1"></i>{{ $product->getStockUrgencyMessage() }}
                                    </span>
                                </div>
                                @endif
                                
                                {{-- Delivery Date Prediction - Amazon Style --}}
                                @if(isset($deliveryInfo))
                                <div class="delivery-date mb-2">
                                    <span style="font-size: 14px; color: #007185;">
                                        <i class="las la-calendar-check me-1"></i>
                                        <strong>{{ $deliveryInfo['message'] }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            {{-- Fulfilled by Phyzioline Badge - Amazon Style --}}
                            <div class="fulfillment-badge mb-20" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #E8F5E9; border-radius: 8px; border: 1px solid #4CAF50;">
                                <i class="las la-check-circle" style="color: #4CAF50; font-size: 18px;"></i>
                                <span style="font-size: 14px; color: #2E7D32; font-weight: 600;">
                                    Fulfilled by Phyzioline
                                </span>
                            </div>
                            
                            {{-- Vendor Information --}}
                            <div class="vendor-info-badge mb-20" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #f8f9fa; border-radius: 20px; border: 1px solid #e0e0e0;">
                                <i class="fa fa-store" style="color: #02767F; font-size: 14px;"></i>
                                <span style="font-size: 13px; color: #666;">
                                    <strong style="color: #333;">Sold by:</strong> 
                                    <span style="color: #02767F; font-weight: 600;">{{ $product->sold_by_name }}</span>
                                </span>
                            </div>

                            <p class="mb-40 description-text">
                                {{ $product->{'short_description_' . app()->getLocale()} }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                        <div class="physio-details-content pl-20 animated-content">
                            <span class="post-type mb-15 category-badge">{{ $product->category->{'name_' . app()->getLocale()} }}</span>

              <div class="physio-quantity-form mb-30 clearfix">
    <strong class="physio-list-title">Quantity:</strong>
    <div class="physio-quantity-input">
        <span class="physio-quantity-btn physio-decrement">–</span>
        <input class="physio-quantity-field" type="number" id="mainQuantity" value="1" min="1" max="{{ $product->amount }}">
        <span class="physio-quantity-btn physio-increment">+</span>
    </div>
</div>

                        {{-- Medical Engineer Service Option --}}
                        @if($product->has_engineer_option)
                        <div class="medical-engineer-option mb-30" style="padding: 20px; background: #f8f9fa; border-radius: 8px; border: 2px solid {{ $product->engineer_required ? '#dc3545' : '#02767F' }};">
                            <div class="form-check" style="margin-bottom: 10px;">
                                <input class="form-check-input" type="checkbox" name="engineer_selected" id="engineer_selected" 
                                       value="1" {{ $product->engineer_required ? 'checked disabled' : '' }} 
                                       data-price="{{ $product->engineer_price }}">
                                <label class="form-check-label" for="engineer_selected" style="font-weight: 600; cursor: pointer;">
                                    <i class="fa fa-user-md me-2" style="color: #02767F;"></i>
                                    {{ __('Medical Engineer Installation') }}
                                    @if($product->engineer_required)
                                        <span class="badge bg-danger ms-2">{{ __('Required for this product') }}</span>
                                    @endif
                                </label>
                            </div>
                            <p style="font-size: 13px; color: #666; margin: 8px 0 0 25px;">
                                {{ __('Professional installation and setup service') }}
                            </p>
                            <div style="margin-top: 10px; margin-left: 25px;">
                                <strong style="color: #02767F; font-size: 16px;">
                                    +{{ number_format($product->engineer_price, 2) }} {{ config('currency.default_symbol', 'EGP') }}
                                </strong>
                            </div>
                        </div>
                        @endif

                        <div class="physio-btns-group ul-li mb-30">
    <ul class="clearfix">
        <li>
            <form action="{{ route('carts.store') }}" method="post" id="addToCartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="cartQuantity" value="1">
                <input type="hidden" name="engineer_selected" id="engineer_selected_hidden" value="0">
                <button type="submit" class="physio-action-btn physio-add-to-cart-btn">Add to Cart</button>
            </form>
        </li>
        <li>
            <form action="{{ route('carts.store') }}" method="post" id="orderNowForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="orderNowQuantity" value="1">
                <input type="hidden" name="engineer_selected" id="engineer_selected_order" value="0">
                <button type="submit" class="physio-action-btn physio-order-now-btn">Order Now</button>
            </form>
        </li>
        <li>
           <button type="button"
                                                                    class="physio-add-to-favorite border-0 bg-transparent"
                                                                    data-product-id="{{ $product->id }}"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Add To Favorite">
                                                                    <i class="fa-heart physio-favorite-icon {{ $product->favorite && $product->favorite->favorite_type ? 'fas' : 'far' }}"
                                                                        style="font-size:18px; color: {{ $product->favorite && $product->favorite->favorite_type ? 'red' : 'inherit' }};"></i>
                                                                </button>

        </li>
    </ul>
</div>

<script>
    const quantityInput = document.getElementById('mainQuantity');
    const cartQuantity = document.getElementById('cartQuantity');
    const orderNowQuantity = document.getElementById('orderNowQuantity');
    
    // Medical Engineer Service Handler
    @if($product->has_engineer_option)
    const engineerCheckbox = document.getElementById('engineer_selected');
    const engineerHiddenCart = document.getElementById('engineer_selected_hidden');
    const engineerHiddenOrder = document.getElementById('engineer_selected_order');
    
    if (engineerCheckbox) {
        // Set initial value if required
        @if($product->engineer_required)
        engineerCheckbox.checked = true;
        engineerHiddenCart.value = '1';
        engineerHiddenOrder.value = '1';
        @endif
        
        engineerCheckbox.addEventListener('change', function() {
            const value = this.checked ? '1' : '0';
            engineerHiddenCart.value = value;
            engineerHiddenOrder.value = value;
        });
    }
    @endif

    function updateQuantityValues() {
        const value = quantityInput.value && parseInt(quantityInput.value) > 0 ? quantityInput.value : 1;
        cartQuantity.value = value;
        orderNowQuantity.value = value;
    }

    // تحديث القيم في الوقت الحقيقي
    quantityInput.addEventListener('input', updateQuantityValues);

    // تأكيد التحديث قبل الإرسال
    document.getElementById('addToCartForm').addEventListener('submit', updateQuantityValues);
    document.getElementById('orderNowForm').addEventListener('submit', updateQuantityValues);

    // إضافة وظائف الأزرار
    document.querySelector('.physio-decrement').addEventListener('click', function() {
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
            updateQuantityValues();
        }
    });

    document.querySelector('.physio-increment').addEventListener('click', function() {
        if (quantityInput.value < parseInt(quantityInput.value.max)) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            updateQuantityValues();
        }
    });
</script>

                            <div class="physio-info-list ul-li-block physio-info-container">
                                <ul class="clearfix">
                                    <li class="physio-info-item"><strong class="physio-list-title">SKU:</strong> {{ $product->sku }}</li>
                                    <li class="physio-info-item"><strong class="physio-list-title">Category:</strong> <a
                                            href="#!">{{ $product->category->{'name_' . app()->getLocale()} }}</a></li>
                                    <li class="physio-tag-list ul-li physio-info-item">
                                        <strong class="physio-list-title">Tags:</strong>
                                        <ul class="clearfix">

                                            @foreach ($product->tags as $tag)
                                                <li><a href="#!" class="physio-tag-link">{{ $tag->{'name_' . app()->getLocale()} }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="physio-social-icon ul-li physio-info-item">
                                        <strong class="physio-list-title">Share:</strong>
                                        <ul class="clearfix">
                                            <li>
                                                <a href="#!" class="physio-social-link">
                                                    <i class="lab la-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#!" class="physio-social-link">
                                                    <i class="lab la-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#!" class="physio-social-link">
                                                    <i class="lab la-instagram"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#!" class="physio-social-link">
                                                    <i class="lab la-pinterest-p"></i>
                                                </a>
                                            </li>
                                        </ul>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="information-area">
                    <div class="physio-tabs-nav ul-li mb-40">
                        <ul class="nav" role="tablist">
                            <li>
                                <a class="active physio-tab-link text-white" style="padding : 10px 20px;" data-toggle="tab" href="#description-tab">Description</a>
                            </li>
                            <li>
                                <a class="physio-tab-link text-white" style="padding : 10px 20px;" data-toggle="tab" href="#reviews-tab">Reviews ({{ $product->review_count }})</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div id="description-tab" class="tab-pane active">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                    <div class="physio-description-content">
                                        {{ $product->{'long_description_' . app()->getLocale()} }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Reviews Tab --}}
                        <div id="reviews-tab" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="mb-4">Customer Reviews</h4>
                                    
                                    @forelse($product->productReviews as $review)
                                    <div class="card mb-3 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                    @if($review->isVerifiedPurchase())
                                                    <span class="badge bg-success" style="font-size: 11px;">
                                                        <i class="las la-check-circle me-1"></i>Verified Purchase
                                                    </span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="mb-2 text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                    <i class="las la-star"></i>
                                                    @else
                                                    <i class="lar la-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alert alert-info">No reviews yet. Be the first to review this product!</div>
                                    @endforelse
                                    
                                    <hr class="my-5">
                                    
                                    <h4 class="mb-3">Write a Review</h4>
                                    @auth
                                        <form action="{{ route('web.products.reviews.store', $product->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Rating</label>
                                                <div class="rating-input">
                                                    <div class="rate">
                                                        <input type="radio" id="star5" name="rating" value="5" />
                                                        <label for="star5" title="5 stars">5 stars</label>
                                                        <input type="radio" id="star4" name="rating" value="4" />
                                                        <label for="star4" title="4 stars">4 stars</label>
                                                        <input type="radio" id="star3" name="rating" value="3" />
                                                        <label for="star3" title="3 stars">3 stars</label>
                                                        <input type="radio" id="star2" name="rating" value="2" />
                                                        <label for="star2" title="2 stars">2 stars</label>
                                                        <input type="radio" id="star1" name="rating" value="1" />
                                                        <label for="star1" title="1 star">1 star</label>
                                                    </div>
                                                </div>
                                                <style>
                                                    .rate { float: left; height: 46px; padding: 0 10px; }
                                                    .rate:not(:checked) > input { position:absolute; top:-9999px; }
                                                    .rate:not(:checked) > label { float:right; width:1em; overflow:hidden; white-space:nowrap; cursor:pointer; font-size:30px; color:#ccc; }
                                                    .rate:not(:checked) > label:before { content: '★ '; }
                                                    .rate > input:checked ~ label { color: #ffc700; }
                                                    .rate:not(:checked) > label:hover,
                                                    .rate:not(:checked) > label:hover ~ label { color: #deb217; }
                                                    .rate > input:checked + label:hover,
                                                    .rate > input:checked + label:hover ~ label,
                                                    .rate > input:checked ~ label:hover,
                                                    .rate > input:checked ~ label:hover ~ label,
                                                    .rate > label:hover ~ input:checked ~ label { color: #c59b08; }
                                                </style>
                                            </div>
                                            <div class="mb-3 clearfix col-12">
                                                <label class="form-label">Review</label>
                                                <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning">
                                            Please <a href="{{ route('view_login.' . app()->getLocale()) }}">login</a> to leave a review.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- details-section - end
       ================================================== -->

        <!-- Frequently Bought Together - Amazon Style -->
        @if(isset($frequentlyBoughtTogether) && $frequentlyBoughtTogether->count() > 0)
        <section id="frequently-bought-together" class="shop-section sec-ptb-50 clearfix" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="physio-section-title mb-4 text-center">
                    <h2 class="title-text mb-3">Frequently Bought Together</h2>
                    <p class="mb-0 text-muted">Customers who bought this item also bought</p>
                </div>
                
                <div class="row justify-content-center">
                    @foreach ($frequentlyBoughtTogether as $relatedProduct)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="position-relative">
                                    <a href="{{ route('product.show.' . (app()->getLocale() ?: 'en'), $relatedProduct->id) }}">
                                        <img src="{{ asset($relatedProduct->productImages->first()?->image ?? 'web/assets/images/default-product.png') }}" 
                                             class="card-img-top" 
                                             alt="{{ $relatedProduct->{'product_name_' . app()->getLocale()} }}"
                                             style="height: 200px; object-fit: cover;">
                                    </a>
                                    @if($relatedProduct->primaryBadge)
                                    <span class="badge position-absolute top-0 start-0 m-2" 
                                          style="background: {{ $relatedProduct->primaryBadge->badge_type === 'best_seller' ? '#FF6F00' : '#02767F' }};">
                                        {{ $relatedProduct->primaryBadge->label }}
                                    </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('product.show.' . (app()->getLocale() ?: 'en'), $relatedProduct->id) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ Str::limit($relatedProduct->{'product_name_' . app()->getLocale()}, 50) }}
                                        </a>
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary">{{ number_format($relatedProduct->product_price, 2) }} EGP</span>
                                        <form action="{{ route('carts.store') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="las la-cart-plus"></i> Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- shop-section - start
       ================================================== -->
        <section id="shop-section" class="shop-section sec-ptb-100 decoration-wrap clearfix">
            <div class="container">

                <div class="physio-section-title mb-0 text-center" style="margin-bottom : 0">
                    <h2 class="title-text mb-3 physio-section-title-animated">Related Products</h2>
                    <p class="mb-0 physio-section-subtitle-animated">Shopping Over $59 or first purchase you will get 100% free shipping</p>
                </div>

                <div class="row justify-content-center">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 product-item"
                            data-name="{{ Str::lower($product->{'product_name_' . app()->getLocale()}) }}">
                            <div class="product-grid text-center clearfix physio-product-card">
                                <div class="item-image physio-image-container">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach ($product->productImages as $img)
                                                <div class="swiper-slide">
                                                    <a href="{{ route('product.show.' . app()->getLocale(), $product->id) }}"
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
                                </div>

                                <div class="item-content physio-content-wrapper">
                                    <h3 class="item-title physio-product-title">
                                        <a href="{{ route('product.show.' . app()->getLocale(), $product->id) }}">
                                            {{ $product->{'product_name_' . app()->getLocale()} }}
                                        </a>
                                    </h3>
                                    <span class="physio-item-price physio-product-price">{{ $product->product_price }} EGP</span>
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
                        <span class="physio-item-price mb-15">$49.50</span>
                        <p class="mb-30">
                            Best Electronic Digital Thermometer adipiscing elit, sed do eiusmod teincididunt ut labore et
                            dolore magna aliqua. Quis ipsum suspendisse us ultrices gravidaes. Risus commodo viverra
                            maecenas accumsan lacus vel facilisis.
                        </p>
                      <div class="quantity-form mb-30 clearfix">
    <strong class="list-title">Quantity:</strong>
    <div class="quantity-input" style="display:flex;align-items:center;gap:5px;">
        <span class="input-number-decrement" 
              style="display:flex;align-items:center;justify-content:center;width:35px;height:35px;border:1px solid #ccc;cursor:pointer;font-size:20px;">–</span>
        <input class="input-number-1" type="text" value="1" 
               style="width:50px;text-align:center;border:1px solid #ccc;height:35px;font-size:16px;">
        <span class="input-number-increment" 
              style="display:flex;align-items:center;justify-content:center;width:35px;height:35px;border:1px solid #ccc;cursor:pointer;font-size:20px;">+</span>
    </div>
</div>

                        <div class="btns-group ul-li mb-30">
                            <ul class="clearfix">
                                <form action="{{ route('carts.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <li>
                                        <button class="addtocart-btn">
                                            <i data-feather="shopping-cart"></i>
                                        </button>
                                        <li><button  class="btn bg-royal-blue">Add to Cart</button></li>
                                    </li>
                                </form>
                                {{-- <li><a href="#!" class="btn bg-royal-blue">Add to Cart</a></li> --}}
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

                    // Buy Now button handler
                    $(document).off('click', '.buy-now-btn').on('click', '.buy-now-btn', function(e) {
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
                                toastr.success('تمت إضافة المنتج إلى السلة');
                                // Redirect to cart or checkout page
                                window.location.href = '{{ route('carts.index') }}';
                            },
                            error: function() {
                                toastr.error('حدث خطأ أثناء الإضافة للسلة');
                            }
                        });
                    });

                });
            </script>

        <!-- Advanced CSS Animations and Styling -->
        <style>
            /* Page Loading Animation */
            .page-loader { 
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #04b8c4, #039ba6);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeOut 1s ease-in-out 2s forwards;
            }

            .loader-content {
                text-align: center;
                color: white;
            }

            .loader-spinner {
                width: 60px;
                height: 60px;
                border: 4px solid rgba(255,255,255,0.3);
                border-top: 4px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            }

            .loader-text {
                font-size: 18px;
                font-weight: 600;
                animation: pulse 1.5s ease-in-out infinite;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }

            .loader-content {
                animation: zoomIn 0.8s ease-out;
            }

            @keyframes fadeOut {
                to {
                    opacity: 0;
                    visibility: hidden;
                }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Keyframe Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slideInFromTop {
                from {
                    opacity: 0;
                    transform: translateY(-100px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.5);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
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

            @keyframes shimmer {
                0% {
                    background-position: -200px 0;
                }
                100% {
                    background-position: calc(200px + 100%) 0;
                }
            }

            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-10px);
                }
                60% {
                    transform: translateY(-5px);
                }
            }

            @keyframes slideInFromBottom {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideInFromLeft {
                from {
                    opacity: 0;
                    transform: translateX(-100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes slideInFromRight {
                from {
                    opacity: 0;
                    transform: translateX(100px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes zoomIn {
                from {
                    opacity: 0;
                    transform: scale(0.3);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            @keyframes flipInX {
                from {
                    opacity: 0;
                    transform: perspective(400px) rotateX(90deg);
                }
                to {
                    opacity: 1;
                    transform: perspective(400px) rotateX(0deg);
                }
            }

            @keyframes flipInY {
                from {
                    opacity: 0;
                    transform: perspective(400px) rotateY(90deg);
                }
                to {
                    opacity: 1;
                    transform: perspective(400px) rotateY(0deg);
                }
            }

            /* Hero Section Animations */
            .hero-title-animated {
                animation: slideInFromTop 1.5s ease-out, float 3s ease-in-out infinite 1.5s;
                font-size: 3.5rem;
                font-weight: 700;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                letter-spacing: 3px;
                position: relative;
            }

            .hero-title-animated::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 0;
                height: 0;
                background: radial-gradient(circle, rgba(4, 184, 196, 0.2), transparent);
                border-radius: 50%;
                animation: zoomIn 2s ease-out 2s both;
            }

            .hero-subtitle-animated {
                animation: slideInFromBottom 1s ease-out 1s both;
                font-size: 1.2rem;
                font-weight: 400;
                opacity: 0.9;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            }

            /* Image Container Animations */
            .animated-image-container {
                animation: fadeInLeft 1s ease-out;
                position: relative;
                overflow: hidden;
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            }

            .product-main-image {
                transition: all 0.5s ease;
                border-radius: 15px;
            }

            .animated-image-container:hover .product-main-image {
                transform: scale(1.1);
            }

            .thumb-item {
                transition: all 0.3s ease;
                border-radius: 8px;
                overflow: hidden;
                border: 2px solid transparent;
                width: 80px !important;
                height: 60px !important;
                margin: 5px !important;
                display: inline-block !important;
            }

            .thumb-item:hover {
                border-color: #04b8c4;
                transform: scale(1.05);
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
            }

            .thumb-image {
                transition: all 0.3s ease;
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }

            .thumb-item:hover .thumb-image {
                transform: scale(1.05);
            }

            /* Thumbnails Container Styling */
            .owl-thumbs {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                flex-wrap: wrap !important;
                gap: 10px !important;
                margin-top: 20px !important;
                padding: 10px !important;
            }

            .owl-thumbs .thumb-item {
                flex-shrink: 0 !important;
            }

            /* Active Thumbnail State */
            .thumb-item.active {
                border-color: #04b8c4 !important;
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.4) !important;
                transform: scale(1.05) !important;
            }

            .thumb-item.active .thumb-image {
                transform: scale(1.05) !important;
            }

            /* Content Animations */
            .animated-content {
                animation: fadeInRight 1s ease-out;
                animation-fill-mode: both;
            }

            .animated-content:nth-child(2) {
                animation-delay: 0.3s;
            }

            .animated-content:nth-child(3) {
                animation-delay: 0.6s;
            }

            /* Category Badge */
            .category-badge {
                background: #04b8c4;
                color: white;
                padding: 8px 20px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                display: inline-block;
                animation: zoomIn 0.8s ease-out;
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
            }

            /* Product Title */
            .product-title-animated {
                animation: slideInFromLeft 1s ease-out 0.5s both;
                font-size: 2.5rem;
                font-weight: 700;
                line-height: 1.3;
                color: #04b8c4;
                position: relative;
            }

            .product-title-animated::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 0;
                width: 100%;
                height: 3px;
                background: linear-gradient(90deg, #04b8c4, #039ba6);
                border-radius: 2px;
                animation: scaleIn 1s ease-out 1s both;
            }

            /* Price Animation */
            .price-animated {
                animation: slideInFromRight 1s ease-out 1s both;
                font-size: 2rem;
                font-weight: 700;
                color: #04b8c4;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
                text-decoration: none !important;
                border-bottom: none !important;
                border: none !important;
                outline: none !important;
            }

            /* Physio Item Price - Unique CSS */
            .physio-item-price {
                text-decoration: none !important;
                border-bottom: none !important;
                border: none !important;
                outline: none !important;
                background: none !important;
                box-shadow: none !important;
            }

            /* Description Text */
            .description-text {
                animation: fadeInUp 1s ease-out 1.2s both;
                font-size: 1.1rem;
                line-height: 1.8;
                color: #555;
                position: relative;
            }

            .description-text::before {
                content: '';
                position: absolute;
                top: -15px;
                left: 0;
                width: 60px;
                height: 2px;
                background: #04b8c4;
                border-radius: 1px;
            }

            /* Physio Quantity Form - Unique CSS */
            .physio-quantity-form {
                animation: fadeInUp 1s ease-out 1.4s both;
                background: #f8f9fa;
                padding: 20px;
                border-radius: 15px;
                border: 2px solid #04b8c4;
                margin-bottom: 20px;
            }

            .physio-list-title {
                display: block;
                font-size: 16px;
                font-weight: 600;
                color: #333;
                margin-bottom: 15px;
            }

            .physio-quantity-input {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
            }

            .physio-quantity-field {
                width: 80px;
                height: 45px;
                text-align: center;
                border: 2px solid #04b8c4;
                border-radius: 8px;
                padding: 8px;
                font-size: 16px;
                font-weight: 600;
                color: #04b8c4;
                background: white;
                outline: none;
                transition: all 0.3s ease;
            }

            .physio-quantity-field:focus {
                border-color: #039ba6;
                box-shadow: 0 0 0 3px rgba(4, 184, 196, 0.1);
                transform: scale(1.02);
            }

            .physio-quantity-btn {
                background: #04b8c4;
                color: white;
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
                font-weight: bold;
                font-size: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                user-select: none;
            }

            .physio-quantity-btn:hover {
                background: #039ba6;
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 6px 20px rgba(4, 184, 196, 0.4);
            }

            .physio-quantity-btn:active {
                transform: scale(0.95) rotate(0deg);
            }

            .physio-quantity-btn:disabled {
                background: #ccc;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            /* Physio Action Buttons - Unique CSS */
            .physio-action-btn {
                background: #04b8c4;
                border: none;
                padding: 12px 25px;
                border-radius: 25px;
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                position: relative;
                overflow: hidden;
                text-decoration: none;
                display: inline-block;
                text-align: center;
                min-width: 120px;
            }

            .physio-action-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s;
            }

            .physio-action-btn:hover::before {
                left: 100%;
            }

            .physio-action-btn:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 25px rgba(4, 184, 196, 0.4);
                background: #039ba6;
                color: white;
                text-decoration: none;
            }

            .physio-action-btn:active {
                transform: translateY(-1px) scale(0.98);
            }

            .physio-add-to-cart-btn {
                animation: fadeInUp 1s ease-out 1.6s both;
            }

            .physio-order-now-btn {
                animation: fadeInUp 1s ease-out 1.8s both;
            }

            /* Physio Buttons Group - Unique CSS */
            .physio-btns-group {
                margin-bottom: 30px;
            }

            .physio-btns-group ul {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                align-items: center;
            }

            .physio-btns-group li {
                margin: 0;
            }

            .physio-btns-group form {
                margin: 0;
            }

            /* Physio Favorite Button - Unique CSS */
            .physio-add-to-favorite {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px solid #04b8c4;
            }

            .physio-add-to-favorite:hover {
                transform: scale(1.2) rotate(5deg);
                background: #ff6b6b;
                border-color: #ff6b6b;
                box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
            }

            .physio-add-to-favorite:hover .physio-favorite-icon {
                color: white !important;
            }

            /* Fix favorite icon color on hover */
            .physio-add-to-favorite:hover .physio-favorite-icon.far,
            .physio-add-to-favorite:hover .physio-favorite-icon.fas {
                color: white !important;
            }

            /* Ensure white color on hover for all favorite icons */
            .physio-add-to-favorite:hover i.fa-heart {
                color: white !important;
            }

            .physio-favorite-icon {
                transition: all 0.3s ease;
            }

            /* Force white color on favorite button hover */
            .physio-add-to-favorite:hover .physio-favorite-icon,
            .physio-add-to-favorite:hover .fa-heart,
            .physio-add-to-favorite:hover i,
            .physio-add-to-favorite:hover .physio-favorite-icon.far,
            .physio-add-to-favorite:hover .physio-favorite-icon.fas {
                color: white !important;
                fill: white !important;
            }

            /* Override any existing color styles on hover */
            .physio-add-to-favorite:hover * {
                color: white !important;
            }

            /* Physio Tabs Nav - Unique CSS */
            .physio-tabs-nav {
                margin-bottom: 40px;
            }

            .physio-tabs-nav ul {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
            }

            .physio-tabs-nav li {
                margin: 0 10px;
            }

            /* Physio Section Title - Unique CSS */
            .physio-section-title {
                margin-bottom: 70px;
            }

            /* Physio Product Details Grid - Unique CSS */
            .physio-product-details-row {
                margin-bottom: 50px;
            }

            .physio-details-content {
                padding-left: 20px;
            }

            .physio-details-image {
                position: relative;
                overflow: hidden;
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            }

            @media (max-width: 991px) {
                .physio-details-content {
                    padding-left: 0;
                    padding-top: 30px;
                }
            }

            @media (max-width: 767px) {
                .physio-product-details-row .col-sm-8 {
                    margin-bottom: 30px;
                }

                .physio-details-content {
                    padding-top: 20px;
                    text-align: center;
                }

                .physio-details-image {
                    margin-bottom: 20px;
                }

                /* Tabs Responsive */
                .physio-tabs-nav ul {
                    justify-content: center;
                }

                .physio-tab-link {
                    padding: 12px 25px;
                    font-size: 14px;
                }

                /* Description Content Responsive */
                .physio-description-content {
                    padding: 20px;
                    font-size: 1rem;
                }
            }



            /* Physio Info Container - Unique CSS */
            .physio-info-container {
                animation: fadeInUp 1s ease-out 2.2s both;
                background: white;
                padding: 25px;
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
                border-left: 5px solid #04b8c4;
            }

            .physio-info-item {
                padding: 10px 0;
                border-bottom: 1px solid #eee;
                transition: all 0.3s ease;
            }

            .physio-info-item:hover {
                transform: translateX(10px) scale(1.02);
                color: #04b8c4;
                background: rgba(4, 184, 196, 0.05);
                border-radius: 8px;
                padding-left: 15px;
            }

            .physio-tag-link {
                background: #f8f9fa;
                color: #04b8c4;
                padding: 5px 12px;
                border-radius: 15px;
                text-decoration: none;
                transition: all 0.3s ease;
                display: inline-block;
                margin: 2px;
            }

            .physio-tag-link:hover {
                background: #04b8c4;
                color: white;
                transform: translateY(-2px) scale(1.05);
                text-decoration: none;
                box-shadow: 0 5px 15px rgba(4, 184, 196, 0.3);
            }

            .physio-tag-link:active {
                transform: translateY(0) scale(0.98);
            }

            /* Physio Social Links - Perfect Centering */
            .physio-social-icon {
                margin-top: 20px;
            }

            .physio-social-icon ul {
                display: flex;
                justify-content: flex-start;
                align-items: center;
                gap: 20px;
                margin: 0;
                padding: 0;
                list-style: none;
                flex-wrap: wrap;
            }

            .physio-social-link {
                width: 50px;
                height: 50px;
                background: #04b8c4;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.3s ease;
                margin: 0;
                font-size: 20px;
                box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                position: relative;
                overflow: hidden;
            }

            .physio-social-link i {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: inherit;
                color: inherit;
            }

            .physio-social-link:hover {
                background: #039ba6;
                transform: scale(1.1) rotate(5deg);
                text-decoration: none;
                box-shadow: 0 8px 25px rgba(4, 184, 196, 0.5);
            }

            .physio-social-link:hover i {
                animation: bounce 0.6s ease-in-out;
            }

            /* Physio Section Title Animations - Unique CSS */
            .physio-section-title-animated {
                animation: slideInFromTop 1s ease-out;
                font-size: 2.5rem;
                font-weight: 700;
                color: #36415a;
                position: relative;
            }

            .physio-section-title-animated::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: #04b8c4;
                border-radius: 2px;
                animation: scaleIn 1s ease-out 0.5s both;
                transition: all 0.3s ease;
            }

            .physio-section-title-animated:hover::after {
                width: 120px;
                background: linear-gradient(90deg, #04b8c4, #039ba6);
            }

            .physio-section-subtitle-animated {
                animation: fadeInUp 1s ease-out 0.8s both;
                font-size: 1.1rem;
                color: #666;
            }

            /* Physio Tab Navigation - Unique CSS */
            .physio-tab-link {
                background: #f8f9fa;
                color: #333;
                padding: 15px 30px;
                border-radius: 25px;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .physio-tab-link:hover,
            .physio-tab-link.active {
                background: #04b8c4;
                color: white;
                border-color: #04b8c4;
                transform: translateY(-2px) scale(1.05);
                text-decoration: none;
            }

            .physio-tab-link:active {
                transform: translateY(0) scale(0.98);
            }

            /* Physio Description Content - Unique CSS */
            .physio-description-content {
                animation: fadeInUp 1s ease-out 1s both;
                padding: 30px;
                border-radius: 15px;
                border-left: 5px solid #04b8c4;
                line-height: 1.8;
                font-size: 1.1rem;
                position: relative;
                transition: all 0.3s ease;
            }

            .physio-description-content:hover {
                transform: translateX(5px);
                box-shadow: 0 5px 15px rgba(4, 184, 196, 0.2);
            }

            .physio-description-content::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 0;
                width: 100%;
                height: 3px;
                background: linear-gradient(90deg, #04b8c4, #039ba6);
                border-radius: 2px;
            }

            /* Physio Product Cards - Same as index.html */
            .physio-product-card {
                height: 100%;
                display: flex;
                flex-direction: column;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                animation: slideInFromBottom 0.8s ease-out;
                animation-fill-mode: both;
            }

            .physio-product-card:nth-child(1) { animation-delay: 0.1s; }
            .physio-product-card:nth-child(2) { animation-delay: 0.2s; }
            .physio-product-card:nth-child(3) { animation-delay: 0.3s; }
            .physio-product-card:nth-child(4) { animation-delay: 0.4s; }

            .physio-product-card:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }

            .physio-product-card:hover .physio-product-img {
                transform: scale(1.1);
            }

            .physio-product-card:hover .physio-product-title a {
                color: #04b8c4;
            }

            .physio-image-container {
                position: relative;
                height: 250px;
                overflow: hidden;
                background: #f8f9fa;
            }

            .physio-product-img {
                width: 100%;
                height: 100%;
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
                color: #04b8c4;
            }

            .physio-product-price {
                font-size: 18px;
                font-weight: 700;
                color: #04b8c4;
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
            .physio-product-card .add-to-favorite {
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
            }

            .physio-product-card .add-to-cart:hover,
            .physio-product-card .add-to-favorite:hover {
                background: #04b8c4;
                color: white;
                transform: scale(1.1);
            }

            .physio-product-card .add-to-favorite:hover .favorite-icon {
                color: black !important;
            }

            /* Fix favorite icon color in related products */
            .physio-product-card .add-to-favorite:hover .favorite-icon.far,
            .physio-product-card .add-to-favorite:hover .favorite-icon.fas,
            .physio-product-card .add-to-favorite:hover .fa-heart,
            .physio-product-card .add-to-favorite:hover i {
                color: white !important;
                fill: white !important;
            }

            /* Override any existing color styles in related products */
            .physio-product-card .add-to-favorite:hover * {
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
            .toast-error{
                color : white !important;
            }
             .toast-message{
                color : white !important;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .hero-title-animated {
                    font-size: 2.5rem;
                }

                .product-title-animated {
                    font-size: 1.8rem;
                }

                .price-animated {
                    font-size: 1.5rem;
                }

                .physio-section-title-animated {
                    font-size: 2rem;
                }

                .physio-section-subtitle-animated {
                    font-size: 1rem;
                }

                .physio-section-title {
                    margin-bottom: 50px;
                }

                .animated-content {
                    animation-delay: 0.1s !important;
                }

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

                /* Physio Quantity Form Responsive */
                .physio-quantity-form {
                    padding: 15px;
                    margin-bottom: 15px;
                }

                .physio-quantity-input {
                    gap: 8px;
                    margin-top: 8px;
                }

                .physio-quantity-field {
                    width: 70px;
                    height: 40px;
                    font-size: 14px;
                }

                .physio-quantity-btn {
                    width: 40px;
                    height: 40px;
                    font-size: 18px;
                }

                .physio-list-title {
                    font-size: 14px;
                    margin-bottom: 12px;
                }
            }

            @media (max-width: 576px) {
                .physio-quantity-form {
                    padding: 12px;
                }

                .physio-quantity-input {
                    gap: 5px;
                }

                .physio-quantity-field {
                    width: 60px;
                    height: 35px;
                    font-size: 13px;
                }

                .physio-quantity-btn {
                    width: 35px;
                    height: 35px;
                    font-size: 16px;
                }

                .physio-list-title {
                    font-size: 13px;
                    margin-bottom: 10px;
                }

                /* Buttons Group Responsive */
                .physio-btns-group ul {
                    gap: 10px;
                    justify-content: center;
                }

                .physio-action-btn {
                    padding: 10px 20px;
                    font-size: 14px;
                    min-width: 100px;
                }

                .physio-add-to-favorite {
                    width: 40px;
                    height: 40px;
                }

                /* Info Container Responsive */
                .physio-info-container {
                    padding: 20px;
                }

                .physio-info-item {
                    padding: 8px 0;
                }

                .physio-social-icon ul {
                    gap: 15px;
                    justify-content: center;
                }

                .physio-social-link {
                    width: 45px;
                    height: 45px;
                    font-size: 18px;
                }
            }

            @media (max-width: 480px) {
                .physio-btns-group ul {
                    flex-direction: column;
                    gap: 15px;
                }

                .physio-action-btn {
                    width: 100%;
                    max-width: 200px;
                }

                /* Extra Small Screen Responsive */
                .physio-info-container {
                    padding: 15px;
                }

                .physio-social-icon ul {
                    gap: 10px;
                }

                .physio-social-link {
                    width: 40px;
                    height: 40px;
                    font-size: 16px;
                }

                .physio-tag-link {
                    padding: 4px 10px;
                    font-size: 12px;
                }

                /* Description Content Extra Small */
                .physio-description-content {
                    padding: 15px;
                    font-size: 0.9rem;
                }

                /* Section Title Extra Small */
                .physio-section-title-animated {
                    font-size: 1.8rem;
                }

                .physio-section-subtitle-animated {
                    font-size: 0.9rem;
                }

                .physio-section-title {
                    margin-bottom: 40px;
                }

                /* Thumbnails Responsive for Small Screens */
                .thumb-item {
                    width: 60px !important;
                    height: 45px !important;
                    margin: 3px !important;
                }

                .owl-thumbs {
                    gap: 8px !important;
                    margin-top: 15px !important;
                    padding: 8px !important;
                }
            }

            /* Medium Screen Responsive for Thumbnails */
            @media (max-width: 768px) {
                .thumb-item {
                    width: 70px !important;
                    height: 52px !important;
                    margin: 4px !important;
                }

                .owl-thumbs {
                    gap: 8px !important;
                    margin-top: 18px !important;
                }
            }

            /* Large Screen Responsive for Thumbnails */
            @media (min-width: 769px) {
                .thumb-item {
                    width: 80px !important;
                    height: 60px !important;
                    margin: 5px !important;
                }

                .owl-thumbs {
                    gap: 10px !important;
                    margin-top: 20px !important;
                }
            }

            /* Smooth Scrolling */
            html {
                scroll-behavior: smooth;
            }
          .physio-product-card .add-to-favorite:hover .favorite-icon {
            color : black !important;
          }

            /* Loading States */
            .loading {
                position: relative;
                overflow: hidden;
            }

            .loading::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                animation: shimmer 1.5s infinite;
            }
        </style>

        <!-- Page Loader -->
        <div class="page-loader">
            <div class="loader-content">
                <div class="loader-spinner"></div>
                <div class="loader-text">Loading...</div>
            </div>
        </div>

    </main>
@endsection

@extends('web.layouts.app')
@section('content')

    <!-- main body - start
      ================================================== -->
    <main>

        @push('css')
            <style>
                /* Breadcrumb Styles */
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

                .breadcrumb-nav ul {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 10px;
                }

                .breadcrumb-nav li a {
                    color: #fff;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .breadcrumb-nav li a:hover {
                    color: #04b8c4;
                }

                /* Wishlist Section Styling */
                .wishlist-section {
                    background: #f8f9fa;
                    padding: 60px 0;
                }

                .table-wrap {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
                    overflow: hidden;
                    margin-top: 30px;
                }

                .table {
                    margin: 0;
                    border: none;
                }

                .table thead {
                    background: linear-gradient(135deg, #04b8c4, #039ba6);
                    color: white;
                }

                .table thead th {
                    border: none;
                    padding: 20px 15px;
                    font-weight: 600;
                    font-size: 16px;
                    text-align: center;
                    vertical-align: middle;
                }

                .table thead th:first-child {
                    text-align: left;
                    padding-left: 25px;
                }

                .table tbody tr {
                    border-bottom: 1px solid #f0f0f0;
                    transition: all 0.3s ease;
                }

                .table tbody tr:hover {
                    background: #f8f9fa;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }

                .table tbody tr:last-child {
                    border-bottom: none;
                }

                .table tbody td {
                    border: none;
                    padding: 20px 15px;
                    vertical-align: middle;
                    text-align: center;
                }

                .table tbody td:first-child {
                    text-align: left;
                    padding-left: 25px;
                }

                /* Product Info Styling */
                .product-info ul {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }

                .product-info li {
                    margin: 0;
                }

                /* Remove Button */
                .remove-btn {
                    background: #dc3545;
                    color: white;
                    border: none;
                    width: 35px;
                    height: 35px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    cursor: pointer;
                }

                .remove-btn:hover {
                    background: #c82333;
                    transform: scale(1.1);
                    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
                }

                .remove-btn i {
                    font-size: 16px;
                }

                /* Product Image */
                .product-image {
                    width: 80px;
                    height: 80px;
                    border-radius: 10px;
                    overflow: hidden;
                    border: 2px solid #e0e0e0;
                    transition: all 0.3s ease;
                }

                .product-image:hover {
                    border-color: #04b8c4;
                    transform: scale(1.05);
                }

                .product-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .product-image:hover img {
                    transform: scale(1.1);
                }

                /* Product Title */
                .item-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                    line-height: 1.4;
                    max-width: 200px;
                }

                /* Product Price */
                .item-price {
                    font-size: 20px;
                    font-weight: 700;
                    color: #04b8c4;
                    margin: 0;
                }

                /* Add to Cart Button */
                .btn.bg-royal-blue {
                    background: #04b8c4 !important;
                    color: white !important;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 25px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    text-decoration: none;
                    display: inline-block;
                }

                .btn.bg-royal-blue:hover {
                    background: #039ba6 !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                    color: white !important;
                    text-decoration: none;
                }

                /* Empty State */
                .empty-wishlist {
                    text-align: center;
                    padding: 60px 20px;
                    color: #666;
                }

                .empty-wishlist i {
                    font-size: 4rem;
                    color: #ddd;
                    margin-bottom: 20px;
                }

                .empty-wishlist h3 {
                    color: #333;
                    margin-bottom: 15px;
                }

                .empty-wishlist p {
                    margin-bottom: 25px;
                    line-height: 1.6;
                }

                .empty-wishlist .btn {
                    background: #04b8c4;
                    color: white;
                    padding: 12px 30px;
                    border-radius: 25px;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .empty-wishlist .btn:hover {
                    background: #039ba6;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(4, 184, 196, 0.3);
                    color: white;
                    text-decoration: none;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    #slider-section h1 {
                        font-size: 2.5rem;
                    }

                    .wishlist-section {
                        padding: 40px 0;
                    }

                    .table-wrap {
                        margin-top: 20px;
                        border-radius: 10px;
                    }

                    .table thead th,
                    .table tbody td {
                        padding: 15px 10px;
                        font-size: 14px;
                    }

                    .product-info ul {
                        flex-direction: column;
                        gap: 10px;
                        align-items: flex-start;
                    }

                    .product-image {
                        width: 60px;
                        height: 60px;
                    }

                    .item-title {
                        font-size: 14px;
                        max-width: 150px;
                    }

                    .item-price {
                        font-size: 18px;
                    }

                    .btn.bg-royal-blue {
                        padding: 10px 20px;
                        font-size: 14px;
                    }
                }

                @media (max-width: 576px) {
                    #slider-section h1 {
                        font-size: 2rem;
                    }

                    .table thead th,
                    .table tbody td {
                        padding: 12px 8px;
                        font-size: 13px;
                    }

                    .product-info ul {
                        gap: 8px;
                    }

                    .product-image {
                        width: 50px;
                        height: 50px;
                    }

                    .item-title {
                        font-size: 13px;
                        max-width: 120px;
                    }

                    .item-price {
                        font-size: 16px;
                    }

                    .btn.bg-royal-blue {
                        padding: 8px 16px;
                        font-size: 13px;
                    }

                    .remove-btn {
                        width: 30px;
                        height: 30px;
                    }

                    .remove-btn i {
                        font-size: 14px;
                    }
                }

                /* Animation */
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

                .table tbody tr {
                    animation: fadeInUp 0.6s ease-out;
                    animation-fill-mode: both;
                }

                .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
                .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
                .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
                .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
                .table tbody tr:nth-child(5) { animation-delay: 0.5s; }
            </style>
        @endpush

        <!-- breadcrumb-section - start
       ================================================== -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 70vh">
                <div class="container">
                    <div class="text-center mt-5 mb-5">
                        <h1>Wishlist</h1>
                    </div>
                    <div class="breadcrumb-nav ul-li-center clearfix">
                        <ul class="clearfix">
                            <li style="font-size: 18px;color: #fff; "><a href="{{ '/' . app()->getLocale() }}">Home |</a></li>
                            <li style="font-size: 18px;" class="active mx-2"><a href="{{ route('show') }}">Shop |</a></li>
                            <li style="font-size: 18px; color: #fff; " class="active">Wishlist</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb-section - end
       ================================================== -->

        <!-- wishlist-section - start
       ================================================== -->
        <section id="wishlist-section" class="wishlist-section sec-ptb-100 clearfix">
            <div class="container">
                @if(count($data) > 0)
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($data as $favorit)
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="product-info ul-li">
                                                <ul class="clearfix">
                                                    <li>
                                                        <form action="{{ route('favorites.delete.' . app()->getLocale(), $favorit->id) }}"
                                                            method="POST" class="remove-favorite-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="remove-btn" title="Remove from Wishlist">
                                                                <i class="las la-times"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <div class="product-image">
                                                            <img src="{{ asset($favorit->product->productImages->first()->image ?? 'default.png') }}"
                                                                alt="Product Image">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="item-title">
                                                            {{ $favorit->product->{'product_name_' . app()->getLocale()} }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><span class="item-price">{{ $favorit->product->product_price }}</span></td>
                                        <td>
                                            <form action="{{ route('carts.store.' . app()->getLocale()) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $favorit->product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn bg-royal-blue">Add To Cart</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                @else
                    <div class="empty-wishlist">
                        <i class="lar la-heart"></i>
                        <h3>Your Wishlist is Empty</h3>
                        <p>You haven't added any products to your wishlist yet. Start exploring our products and add your favorites!</p>
                        <a href="{{ route('show') }}" class="btn">Browse Products</a>
                    </div>
                @endif
            </div>
        </section>
        <!-- wishlist-section - end
       ================================================== -->

    </main>
    <!-- main body - end
      ================================================== -->
@endsection

@extends('web.layouts.app')

@section('title', 'Compare Products | PhyzioLine')

@section('content')
<main>
    @push('css')
        <style>
            .compare-page-header {
                background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%);
                padding: 40px 0;
                margin-bottom: 30px;
                color: white;
                text-align: center;
            }
            
            .compare-page-header h1 {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 10px;
            }
            
            .compare-table-wrapper {
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                overflow-x: auto;
                margin-bottom: 30px;
            }
            
            .compare-table {
                width: 100%;
                border-collapse: collapse;
                min-width: 800px;
            }
            
            .compare-table th {
                background: #f8f9fa;
                padding: 15px;
                text-align: left;
                font-weight: 600;
                color: #36415a;
                border-bottom: 2px solid #e7e7e7;
            }
            
            .compare-table td {
                padding: 20px 15px;
                border-bottom: 1px solid #f0f0f0;
                vertical-align: top;
            }
            
            .compare-table tr:hover td {
                background: #f8f9fa;
            }
            
            .compare-product-image {
                width: 120px;
                height: 120px;
                object-fit: cover;
                border-radius: 8px;
                border: 2px solid #e7e7e7;
            }
            
            .compare-product-title {
                font-size: 16px;
                font-weight: 600;
                color: #333;
                margin-bottom: 10px;
                line-height: 1.4;
            }
            
            .compare-product-title a {
                color: #333;
                text-decoration: none;
            }
            
            .compare-product-title a:hover {
                color: #02767F;
            }
            
            .compare-product-price {
                font-size: 20px;
                font-weight: 700;
                color: #02767F;
                margin: 10px 0;
            }
            
            .compare-product-rating {
                display: flex;
                gap: 3px;
                margin: 10px 0;
            }
            
            .compare-product-rating i {
                color: #ffc107;
                font-size: 16px;
            }
            
            .compare-product-rating i.inactive {
                color: #e0e0e0;
            }
            
            .compare-actions {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
            }
            
            .compare-btn {
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            
            .compare-btn-cart {
                background: #02767F;
                color: white;
            }
            
            .compare-btn-cart:hover {
                background: #04b8c4;
                transform: translateY(-2px);
            }
            
            .compare-btn-remove {
                background: #dc3545;
                color: white;
            }
            
            .compare-btn-remove:hover {
                background: #c82333;
            }
            
            .compare-empty {
                text-align: center;
                padding: 60px 20px;
            }
            
            .compare-empty-icon {
                font-size: 80px;
                color: #ccc;
                margin-bottom: 20px;
            }
            
            .compare-empty h2 {
                color: #36415a;
                margin-bottom: 10px;
            }
            
            .compare-empty p {
                color: #666;
                margin-bottom: 30px;
            }
            
            .compare-empty-btn {
                display: inline-block;
                padding: 12px 30px;
                background: #02767F;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
            }
            
            .compare-empty-btn:hover {
                background: #04b8c4;
                color: white;
            }
        </style>
    @endpush

    <!-- Header -->
    <section class="compare-page-header">
        <div class="container">
            <h1>Compare Products</h1>
            <p>Compare up to 4 products side by side</p>
        </div>
    </section>

    <!-- Compare Table -->
    <section class="compare-section">
        <div class="container">
            @if($compareItems->count() > 0)
                <div class="compare-table-wrapper">
                    <table class="compare-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                @foreach($compareItems as $item)
                                    <th style="text-align: center;">
                                        <form action="{{ route('compare.delete', $item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="float: right; margin-bottom: 10px;">
                                                <i class="las la-times"></i>
                                            </button>
                                        </form>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Image</strong></td>
                                @foreach($compareItems as $item)
                                    <td style="text-align: center;">
                                        <img src="{{ asset($item->product->productImages->first()?->image ?? 'web/assets/images/default-product.png') }}" 
                                             alt="{{ $item->product->{'product_name_' . app()->getLocale()} }}" 
                                             class="compare-product-image" />
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Product Name</strong></td>
                                @foreach($compareItems as $item)
                                    <td>
                                        <div class="compare-product-title">
                                            <a href="{{ route('product.show', $item->product->id) }}">
                                                {{ $item->product->{'product_name_' . app()->getLocale()} }}
                                            </a>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Price</strong></td>
                                @foreach($compareItems as $item)
                                    <td>
                                        <div class="compare-product-price">{{ number_format($item->product->product_price, 2) }} EGP</div>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Category</strong></td>
                                @foreach($compareItems as $item)
                                    <td>{{ $item->product->category->{'name_' . app()->getLocale()} }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Sub Category</strong></td>
                                @foreach($compareItems as $item)
                                    <td>{{ $item->product->sub_category->{'name_' . app()->getLocale()} }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>SKU</strong></td>
                                @foreach($compareItems as $item)
                                    <td>{{ $item->product->sku }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Rating</strong></td>
                                @foreach($compareItems as $item)
                                    <td>
                                        <div class="compare-product-rating">
                                            <i class="las la-star active"></i>
                                            <i class="las la-star active"></i>
                                            <i class="las la-star active"></i>
                                            <i class="las la-star active"></i>
                                            <i class="las la-star inactive"></i>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><strong>Actions</strong></td>
                                @foreach($compareItems as $item)
                                    <td>
                                        <div class="compare-actions">
                                            <form action="{{ route('carts.store') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="compare-btn compare-btn-cart">
                                                    <i class="las la-shopping-basket"></i> Add to Cart
                                                </button>
                                            </form>
                                            <a href="{{ route('product.show', $item->product->id) }}" class="compare-btn" style="background: #04b8c4; color: white;">
                                                View Details
                                            </a>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="compare-empty">
                    <div class="compare-empty-icon">
                        <i class="las la-balance-scale"></i>
                    </div>
                    <h2>No Products to Compare</h2>
                    <p>Add products to compare by clicking the compare button on product cards</p>
                    <a href="{{ route('show') }}" class="compare-empty-btn">
                        <i class="las la-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </section>
</main>
@endsection


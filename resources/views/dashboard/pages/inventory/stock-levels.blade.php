@extends('dashboard.layouts.app')
@section('title', __('Stock Levels'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inventory</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock Levels</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-lg-3">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Products</p>
                                <h4 class="my-1 text-success">{{ $products->total() }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Low Stock</p>
                                <h4 class="my-1 text-warning">{{ $lowStockProducts }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Out of Stock</p>
                                <h4 class="my-1 text-danger">{{ $outOfStockProducts }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-box border">
                                                <img src="{{ asset($product->productImages->first()?->image ?? 'default.png') }}" alt="" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-name mb-1">{{ $product->product_name_en }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->category->name_en ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('dashboard.inventory.update-stock') }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="number" name="amount" value="{{ $product->amount }}" class="form-control form-control-sm" style="width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        @if($product->amount == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->amount <= 10)
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No products found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

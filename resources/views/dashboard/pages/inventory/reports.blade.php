@extends('dashboard.layouts.app')
@section('title', __('Inventory Reports'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inventory</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Inventory Value</p>
                                <h4 class="my-1">{{ number_format($totalValue, 2) }} EGP</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Stock Count</p>
                                <h4 class="my-1">{{ number_format($totalStock) }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class="bi bi-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Products</p>
                                <h4 class="my-1">{{ $totalProducts }}</h4>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class="bi bi-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Stock by Category</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Count</th>
                                <th>Total Stock</th>
                                <th>Stock %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryBreakdown as $category)
                                <tr>
                                    <td>{{ $category->category->name_en ?? 'Unknown' }}</td>
                                    <td>{{ $category->product_count }}</td>
                                    <td>{{ $category->total_stock }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-gradient-quepal" role="progressbar" style="width: {{ $totalStock > 0 ? ($category->total_stock / $totalStock) * 100 : 0 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

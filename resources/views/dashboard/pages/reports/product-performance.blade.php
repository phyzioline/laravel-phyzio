@extends('dashboard.layouts.app')
@section('title', __('Product Performance'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <h4 class="mb-4">{{ __('Product Performance Report') }}</h4>

        <!-- Date Range -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Top Selling Products') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Units Sold') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->productImages->first())
                                            <img src="{{ asset($item->product->productImages->first()->image) }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:12px;" alt="">
                                        @endif
                                        <span>{{ $item->product?->{'product_name_' . app()->getLocale()} ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td><strong>{{ $item->total_sold }}</strong> {{ __('units') }}</td>
                                <td><strong>{{ number_format($item->total_revenue, 2) }}</strong> {{ __('EGP') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('No data available') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($lowStockBestsellers->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-warning">
                <h5>{{ __('Low Stock Bestsellers - Restock Needed!') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Current Stock') }}</th>
                                <th>{{ __('Sold (Period)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockBestsellers as $item)
                            <tr>
                                <td>{{ $item->product->{'product_name_' . app()->getLocale()} }}</td>
                                <td><span class="badge bg-danger">{{ $item->product->amount }}</span></td>
                                <td>{{ $item->total_sold }} {{ __('units') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection

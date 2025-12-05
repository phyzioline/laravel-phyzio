@extends('dashboard.layouts.app')
@section('title', __('Sales Dashboard'))

@push('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .kpi-card {
        background: white;
        padding: 24px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .kpi-card h6 {
        color: #666;
        font-size: 13px;
        margin-bottom:12px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .kpi-card .value {
        font-size: 32px;
        font-weight: 700;
        color: #04b8c4;
        margin-bottom: 4px;
    }
    .kpi-card .label {
        font-size: 12px;
        color: #999;
    }
    .comparison-card {
        background: white;
        padding: 16px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        margin-bottom: 12px;
    }
    .comparison-card .period {
        font-size: 12px;
        color: #666;
        font-weight: 600;
    }
    .comparison-card .metric {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .date-range-selector {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{ __('Sales Dashboard') }}</h4>
            <div>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> {{ __('Download Report') }}
                </button>
            </div>
        </div>

        <!-- Date Range Selector -->
        <div class="date-range-selector">
            <form method="GET" action="{{ route('dashboard.reports.sales-dashboard') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('End Date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                </div>
            </form>
        </div>

        <!-- Sales Snapshot KPIs -->
        <h5 class="mb-3">{{ __('Sales Snapshot') }}</h5>
        <div class="row mb-4">
            <div class="col-md-2dot4 col-lg-2dot4 mb-3">
                <div class="kpi-card">
                    <h6>{{ __('Total Order Items') }}</h6>
                    <div class="value">{{ $stats['total_order_items'] }}</div>
                    <div class="label">{{ __('Items') }}</div>
                </div>
            </div>
            <div class="col-md-2dot4 col-lg-2dot4 mb-3">
                <div class="kpi-card">
                    <h6>{{ __('Units Ordered') }}</h6>
                    <div class="value">{{ $stats['units_ordered'] }}</div>
                    <div class="label">{{ __('Units') }}</div>
                </div>
            </div>
            <div class="col-md-2dot4 col-lg-2dot4 mb-3">
                <div class="kpi-card">
                    <h6>{{ __('Ordered Product Sales') }}</h6>
                    <div class="value">{{ number_format($stats['ordered_product_sales'], 2) }}</div>
                    <div class="label">{{ __('EGP') }}</div>
                </div>
            </div>
            <div class="col-md-2dot4 col-lg-2dot4 mb-3">
                <div class="kpi-card">
                    <h6>{{ __('Avg Units/Order') }}</h6>
                    <div class="value">{{ $stats['avg_units_per_order'] }}</div>
                    <div class="label">{{ __('Units') }}</div>
                </div>
            </div>
            <div class="col-md-2dot4 col-lg-2dot4 mb-3">
                <div class="kpi-card">
                    <h6>{{ __('Avg Sales/Order') }}</h6>
                    <div class="value">{{ number_format($stats['avg_sales_per_order'], 2) }}</div>
                    <div class="label">{{ __('EGP') }}</div>
                </div>
            </div>
        </div>

        <!-- Compare Sales -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Compare Sales') }}</h5>
                        <canvas id="compareSalesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3">{{ __('Period Comparison') }}</h6>
                <!-- Today -->
                <div class="comparison-card">
                    <div class="period">{{ __('Today') }}</div>
                    <div class="metric">{{ number_format($compareSales['today']['revenue'], 2) }} {{ __('EGP') }}</div>
                    <small class="text-muted">{{ $compareSales['today']['orders'] }} {{ __('orders') }} | {{ $compareSales['today']['units'] }} {{ __('units') }}</small>
                </div>
                <!-- Yesterday -->
                <div class="comparison-card">
                    <div class="period">{{ __('Yesterday') }}</div>
                    <div class="metric">{{ number_format($compareSales['yesterday']['revenue'], 2) }} {{ __('EGP') }}</div>
                    <small class="text-muted">{{ $compareSales['yesterday']['orders'] }} {{ __('orders') }} | {{ $compareSales['yesterday']['units'] }} {{ __('units') }}</small>
                </div>
                <!-- Same day last week -->
                <div class="comparison-card">
                    <div class="period">{{ __('Same day last week') }}</div>
                    <div class="metric">{{ number_format($compareSales['same_day_last_week']['revenue'], 2) }} {{ __('EGP') }}</div>
                    <small class="text-muted">{{ $compareSales['same_day_last_week']['orders'] }} {{ __('orders') }} | {{ $compareSales['same_day_last_week']['units'] }} {{ __('units') }}</small>
                </div>
            </div>
        </div>

        <!-- Sales Trend -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Sales Trend (Last 30 Days)') }}</h5>
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Compare Sales Chart
    const compareSalesCtx = document.getElementById('compareSalesChart').getContext('2d');
    new  Chart(compareSalesCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('Today') }}', '{{ __('Yesterday') }}', '{{ __('Same day last week') }}'],
            datasets: [{
                label: '{{ __('Revenue (EGP)') }}',
                data: [
                    {{ $compareSales['today']['revenue'] }},
                    {{ $compareSales['yesterday']['revenue'] }},
                    {{ $compareSales['same_day_last_week']['revenue'] }}
                ],
                backgroundColor: 'rgba(4, 184, 196, 0.7)',
                borderColor: 'rgba(4, 184, 196, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($salesTrend as $data)
                    '{{ \Carbon\Carbon::parse($data->date)->format('M d') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ __('Revenue (EGP)') }}',
                data: [
                    @foreach($salesTrend as $data)
                        {{ $data->revenue }},
                    @endforeach
                ],
                fill: true,
                backgroundColor: 'rgba(4, 184, 196, 0.1)',
                borderColor: 'rgba(4, 184, 196, 1)',
                borderWidth: 2,
                tension: 0.4
            }, {
                label: '{{ __('Orders') }}',
                data: [
                    @foreach($salesTrend as $data)
                        {{ $data->orders }},
                    @endforeach
                ],
                fill: false,
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<style>
    /* Custom 5-column grid */
    .col-md-2dot4 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }
    @media (min-width: 768px) {
        .col-md-2dot4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
    .col-lg-2dot4 {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }
    @media (min-width: 992px) {
        .col-lg-2dot4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
</style>
@endpush

@extends('web.layouts.dashboard_master')

@section('title', __('Financial Reports'))
@section('header_title', __('Financial Reports & Analytics'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Month/Year Selector -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <form method="GET" action="{{ route('clinic.reports.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Month') }}</label>
                        <select name="month" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Year') }}</label>
                        <select name="year" class="form-select">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-search"></i> {{ __('Load Report') }}
                        </button>
                        <div class="btn-group mt-2 mt-md-0">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <i class="las la-download"></i> {{ __('Export') }}
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('clinic.reports.export', ['month' => $month, 'year' => $year, 'format' => 'csv']) }}">
                                    <i class="las la-file-csv"></i> {{ __('Export CSV') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('clinic.reports.export', ['month' => $month, 'year' => $year, 'format' => 'pdf']) }}" target="_blank">
                                    <i class="las la-file-pdf"></i> {{ __('Export PDF') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Financial Report -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Monthly Financial Report') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-primary text-white rounded h-100">
                            <h6>{{ __('Total Revenue') }}</h6>
                            <h3>{{ number_format($monthlyReport['total_revenue'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                            @if(isset($monthlyReport['changes']['revenue']))
                                @php $revChange = $monthlyReport['changes']['revenue']; @endphp
                                <small class="d-block mt-2">
                                    <i class="las la-arrow-{{ $revChange >= 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($revChange), 1) }}% 
                                    {{ __('vs last month') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-danger text-white rounded h-100">
                            <h6>{{ __('Total Expenses') }}</h6>
                            <h3>{{ number_format($monthlyReport['total_expenses'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                            @if(isset($monthlyReport['changes']['expenses']))
                                @php $expChange = $monthlyReport['changes']['expenses']; @endphp
                                <small class="d-block mt-2">
                                    <i class="las la-arrow-{{ $expChange >= 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($expChange), 1) }}% 
                                    {{ __('vs last month') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 {{ ($monthlyReport['net_profit'] ?? 0) >= 0 ? 'bg-success' : 'bg-danger' }} text-white rounded h-100">
                            <h6>{{ __('Net Profit / Loss') }}</h6>
                            <h3>{{ number_format($monthlyReport['net_profit'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                            @if(isset($monthlyReport['profit_margin']))
                                <small class="d-block mt-2">
                                    {{ __('Margin') }}: {{ number_format($monthlyReport['profit_margin'], 1) }}%
                                </small>
                            @endif
                            @if(isset($monthlyReport['changes']['profit']))
                                @php $profChange = $monthlyReport['changes']['profit']; @endphp
                                <small class="d-block mt-1">
                                    <i class="las la-arrow-{{ $profChange >= 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($profChange), 1) }}% 
                                    {{ __('vs last month') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-warning text-white rounded h-100">
                            <h6>{{ __('Outstanding Balances') }}</h6>
                            <h3>{{ number_format($monthlyReport['outstanding_balances'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                            <small class="d-block mt-2">
                                {{ __('Unpaid invoices') }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Comparison Chart -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-3">{{ __('Monthly Comparison') }}</h6>
                                <canvas id="monthlyComparisonChart" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>{{ __('Paid Invoices') }}:</strong> {{ $monthlyReport['paid_invoices'] ?? 0 }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Unpaid Invoices') }}:</strong> {{ $monthlyReport['unpaid_invoices'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Patient Payment Reports -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Top Paying Patients') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Patient') }}</th>
                                <th>{{ __('Total Paid') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patientReports['top_paying_patients'] ?? [] as $patient)
                            <tr>
                                <td>{{ $patient['name'] }}</td>
                                <td><strong>{{ number_format($patient['total'], 2) }} {{ __('EGP') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">{{ __('No data available') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Payment Method Distribution') }}</h5>
            </div>
            <div class="card-body px-4">
                @forelse($patientReports['payment_method_distribution'] ?? [] as $method => $data)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><strong>{{ ucfirst(str_replace('_', ' ', $method)) }}</strong></span>
                        <span>{{ number_format($data->total ?? 0, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        @php
                            $total = collect($patientReports['payment_method_distribution'] ?? [])->sum('total');
                            $percentage = $total > 0 ? (($data->total ?? 0) / $total) * 100 : 0;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">{{ __('No data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Outstanding Patients -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Patients with Outstanding Balances') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Patient') }}</th>
                                <th>{{ __('Total Invoiced') }}</th>
                                <th>{{ __('Total Paid') }}</th>
                                <th>{{ __('Remaining Balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patientReports['outstanding_patients'] ?? [] as $patient)
                            <tr>
                                <td><strong>{{ $patient['name'] }}</strong></td>
                                <td>{{ number_format($patient['total_invoiced'], 2) }} {{ __('EGP') }}</td>
                                <td class="text-success">{{ number_format($patient['total_paid'], 2) }} {{ __('EGP') }}</td>
                                <td class="text-warning"><strong>{{ number_format($patient['remaining_balance'], 2) }} {{ __('EGP') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('No outstanding balances') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expense Reports -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Expenses by Category') }}</h5>
            </div>
            <div class="card-body px-4">
                @forelse($expenseReports['by_category'] ?? [] as $category => $data)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><strong>{{ ucfirst(str_replace('_', ' ', $category)) }}</strong></span>
                        <span>{{ number_format($data->total ?? 0, 2) }} {{ __('EGP') }} ({{ $data->count ?? 0 }} {{ __('items') }})</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        @php
                            $total = collect($expenseReports['by_category'] ?? [])->sum('total');
                            $percentage = $total > 0 ? (($data->total ?? 0) / $total) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentage }}%">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">{{ __('No data available') }}</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Payment Statistics') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="mb-3">
                    <p><strong>{{ __('Partial Payments') }}:</strong> {{ $patientReports['partial_payments'] ?? 0 }}</p>
                </div>
                <div class="mb-3">
                    <p><strong>{{ __('Full Payments') }}:</strong> {{ $patientReports['full_payments'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Comparison Chart
    const ctx = document.getElementById('monthlyComparisonChart');
    if (ctx) {
        const currentMonth = {
            revenue: {{ $monthlyReport['total_revenue'] ?? 0 }},
            expenses: {{ $monthlyReport['total_expenses'] ?? 0 }},
            profit: {{ $monthlyReport['net_profit'] ?? 0 }}
        };
        
        const previousMonth = {
            revenue: {{ $monthlyReport['previous_month']['revenue'] ?? 0 }},
            expenses: {{ $monthlyReport['previous_month']['expenses'] ?? 0 }},
            profit: {{ $monthlyReport['previous_month']['profit'] ?? 0 }}
        };
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['{{ __('Revenue') }}', '{{ __('Expenses') }}', '{{ __('Net Profit') }}'],
                datasets: [
                    {
                        label: '{{ __('Current Month') }}',
                        data: [currentMonth.revenue, currentMonth.expenses, currentMonth.profit],
                        backgroundColor: ['#2196F3', '#f44336', currentMonth.profit >= 0 ? '#4caf50' : '#f44336']
                    },
                    {
                        label: '{{ __('Previous Month') }}',
                        data: [previousMonth.revenue, previousMonth.expenses, previousMonth.profit],
                        backgroundColor: ['rgba(33, 150, 243, 0.5)', 'rgba(244, 67, 54, 0.5)', previousMonth.profit >= 0 ? 'rgba(76, 175, 80, 0.5)' : 'rgba(244, 67, 54, 0.5)']
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' EGP';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' EGP';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection


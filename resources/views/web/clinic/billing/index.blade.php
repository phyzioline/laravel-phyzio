@extends('web.layouts.dashboard_master')

@section('title', 'Billing')
@section('header_title', 'Billing & Invoices')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first to view billing information.') }}
</div>
@else
<!-- Revenue Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-dollar-sign fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Total Revenue') }}</h6>
                        <h3 class="mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        @if(isset($revenueGrowth))
                        <small class="opacity-75">
                            <i class="las la-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i> 
                            {{ abs($revenueGrowth) }}% {{ __('vs last month') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-clock fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Pending Payments') }}</h6>
                        <h3 class="mb-0 text-warning">${{ number_format($pendingPayments ?? 0, 2) }}</h3>
                        <small class="text-muted">{{ __('Outstanding balance') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('This Month') }}</h6>
                        <h3 class="mb-0 text-success">${{ number_format($thisMonthRevenue ?? 0, 2) }}</h3>
                        <small class="text-muted">{{ __('Current month revenue') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-clipboard-list fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Program Revenue') }}</h6>
                        <h3 class="mb-0 text-info">${{ number_format($programRevenue ?? 0, 2) }}</h3>
                        <small class="text-muted">{{ __('From treatment programs') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Distribution -->
@if(isset($paymentMethodDistribution) && !empty($paymentMethodDistribution))
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Payment Methods') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="paymentMethodChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Revenue Breakdown') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Invoices/Payments') }}</span>
                        <strong>${{ number_format(($totalRevenue ?? 0) - ($programRevenue ?? 0), 2) }}</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($totalRevenue ?? 0) > 0 ? round((($totalRevenue ?? 0) - ($programRevenue ?? 0)) / ($totalRevenue ?? 0) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Treatment Programs') }}</span>
                        <strong>${{ number_format($programRevenue ?? 0, 2) }}</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-info" style="width: {{ ($totalRevenue ?? 0) > 0 ? round(($programRevenue ?? 0) / ($totalRevenue ?? 0) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Invoices/Payments Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('Recent Transactions') }}</h5>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary active">{{ __('All') }}</button>
            <button class="btn btn-outline-secondary">{{ __('Invoices') }}</button>
            <button class="btn btn-outline-secondary">{{ __('Programs') }}</button>
        </div>
    </div>
    <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('Transaction ID') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allInvoices ?? $invoices ?? [] as $inv)
                    <tr>
                        <td>
                            <a href="#" class="font-weight-bold text-primary">{{ $inv->id ?? 'N/A' }}</a>
                            @if(isset($inv->type) && $inv->type == 'program')
                                <span class="badge badge-info badge-sm">{{ __('Program') }}</span>
                            @endif
                        </td>
                        <td>{{ $inv->patient ?? 'Unknown' }}</td>
                        <td>
                            @if(isset($inv->type))
                                {{ ucfirst($inv->type) }}
                            @else
                                {{ __('Invoice') }}
                            @endif
                        </td>
                        <td>
                            @if($inv->date instanceof \Carbon\Carbon)
                                {{ $inv->date->format('M d, Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($inv->date)->format('M d, Y') }}
                            @endif
                        </td>
                        <td class="font-weight-bold">${{ number_format($inv->amount ?? 0, 2) }}</td>
                        <td>
                            @php
                                $status = strtolower($inv->status ?? 'pending');
                            @endphp
                            <span class="badge {{ $status == 'paid' ? 'badge-success' : ($status == 'partial' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" title="{{ __('Download') }}">
                                <i class="las la-download"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="las la-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No transactions found') }}</p>
                            <small class="text-muted">{{ __('Transactions will appear here when patients make payments.') }}</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(isset($paymentMethodDistribution) && !empty($paymentMethodDistribution))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctxPM = document.getElementById('paymentMethodChart');
    if (ctxPM) {
        var paymentData = @json($paymentMethodDistribution ?? []);
        new Chart(ctxPM.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(paymentData).map(m => m.charAt(0).toUpperCase() + m.slice(1)),
                datasets: [{
                    data: Object.values(paymentData),
                    backgroundColor: ['#00897b', '#43a047', '#1e88e5', '#fb8c00']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
</script>
@endif
@endpush

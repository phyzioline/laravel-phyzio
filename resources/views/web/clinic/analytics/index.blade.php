@extends('web.layouts.dashboard_master')

@section('title', 'Analytics')
@section('header_title', 'Performance Analytics')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first to view analytics.') }}
</div>
@else
<!-- Key Metrics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #00897b;">
                        <i class="las la-users fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0">{{ number_format($totalPatients ?? 0) }}</h3>
                        <small class="text-muted">{{ __('Total Patients') }}</small>
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
                        <h3 class="font-weight-bold mb-0">{{ number_format($totalAppointments ?? 0) }}</h3>
                        <small class="text-muted">{{ __('Total Appointments') }}</small>
                        <div class="text-success small mt-1">
                            <i class="las la-check-circle"></i> {{ $completedAppointments ?? 0 }} {{ __('Completed') }}
                        </div>
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
                        <i class="las la-dollar-sign fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        <small class="text-muted">{{ __('Total Revenue') }}</small>
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
                        <i class="las la-clipboard-list fa-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-weight-bold mb-0">{{ number_format($activePrograms ?? 0) }}</h3>
                        <small class="text-muted">{{ __('Active Programs') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Revenue Overview') }} (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="150"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Growth Stats -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Patient Growth') }}</h5>
            </div>
             <div class="card-body">
                <canvas id="growthChart" height="200"></canvas>
                <div class="mt-4 text-center">
                    <h3 class="font-weight-bold {{ ($patientGrowthPercentage ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ ($patientGrowthPercentage ?? 0) >= 0 ? '+' : '' }}{{ number_format($patientGrowthPercentage ?? 0, 1) }}%
                    </h3>
                    <p class="text-muted">{{ __('Growth in new patients this quarter') }}</p>
                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('New') }}</small>
                            <strong>{{ $newPatients ?? 0 }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('Returning') }}</small>
                            <strong>{{ $returningPatients ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Appointment Status Distribution') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Specialty Distribution') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="specialtyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Performance Metrics') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="font-weight-bold text-primary">{{ number_format($completionRate ?? 0, 1) }}%</h4>
                        <small class="text-muted">{{ __('Completion Rate') }}</small>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $completionRate ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="font-weight-bold text-success">${{ number_format($avgAppointmentValue ?? 0, 2) }}</h4>
                        <small class="text-muted">{{ __('Avg Appointment Value') }}</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="font-weight-bold text-info">{{ $completedAppointments ?? 0 }}</h4>
                        <small class="text-muted">{{ __('Completed Appointments') }}</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h4 class="font-weight-bold text-warning">{{ $cancelledAppointments ?? 0 }}</h4>
                        <small class="text-muted">{{ __('Cancelled Appointments') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart - Use real data (Line chart)
    var ctxR = document.getElementById('revenueChart');
    if (ctxR) {
        try {
            var revenueData = @json($monthlyRevenue ?? []);
            var revenueLabels = @json($monthlyLabels ?? []);
            
            // Ensure data is valid array
            if (!Array.isArray(revenueData)) revenueData = [];
            if (!Array.isArray(revenueLabels)) revenueLabels = [];
            
            // Ensure arrays have same length
            while (revenueData.length < revenueLabels.length) {
                revenueData.push(0);
            }
            while (revenueLabels.length < revenueData.length) {
                revenueLabels.push('');
            }
            
            new Chart(ctxR, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: '{{ __('Revenue (EGP)') }}',
                        data: revenueData,
                        borderColor: '#00897b',
                        backgroundColor: 'rgba(0, 137, 123, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'EGP ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error rendering revenue chart:', error);
            ctxR.parentElement.innerHTML = '<p class="text-muted text-center">Chart data unavailable</p>';
        }
    }

    // Growth Chart - Use real trend data
    var ctxG = document.getElementById('growthChart');
    if (ctxG) {
        try {
            var growthLabels = @json($monthlyLabels ?? []);
            var growthData = @json($patientGrowth ?? []);
            
            // Ensure data is valid array
            if (!Array.isArray(growthLabels)) growthLabels = [];
            if (!Array.isArray(growthData)) growthData = [];
            
            // Ensure arrays have same length
            while (growthData.length < growthLabels.length) {
                growthData.push(0);
            }
            while (growthLabels.length < growthData.length) {
                growthLabels.push('');
            }
            
            new Chart(ctxG, {
                type: 'bar',
                data: {
                    labels: growthLabels,
                    datasets: [{
                        label: '{{ __('New Patients') }}',
                        data: growthData,
                        backgroundColor: '#80cbc4'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error rendering growth chart:', error);
            ctxG.parentElement.innerHTML = '<p class="text-muted text-center">Chart data unavailable</p>';
        }
    }

    // Status Distribution Chart
    var ctxS = document.getElementById('statusChart');
    if (ctxS) {
        try {
            var statusData = @json($statusDistribution ?? []);
            if (!statusData || Object.keys(statusData).length === 0) {
                ctxS.parentElement.innerHTML = '<p class="text-muted text-center">No appointment data available</p>';
            } else {
                new Chart(ctxS, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                        datasets: [{
                            data: Object.values(statusData),
                            backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error rendering status chart:', error);
            ctxS.parentElement.innerHTML = '<p class="text-muted text-center">Chart data unavailable</p>';
        }
    }

    // Specialty Distribution Chart
    var ctxSp = document.getElementById('specialtyChart');
    if (ctxSp) {
        try {
            var specialtyData = @json($specialtyDistribution ?? []);
            if (!specialtyData || Object.keys(specialtyData).length === 0) {
                ctxSp.parentElement.innerHTML = '<p class="text-muted text-center">No specialty data available</p>';
            } else {
                new Chart(ctxSp, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(specialtyData).map(s => s.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())),
                        datasets: [{
                            data: Object.values(specialtyData),
                            backgroundColor: ['#00897b', '#43a047', '#1e88e5', '#fb8c00', '#e91e63', '#9c27b0', '#00bcd4', '#795548']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error rendering specialty chart:', error);
            ctxSp.parentElement.innerHTML = '<p class="text-muted text-center">Chart data unavailable</p>';
        }
    }
</script>
@endpush

@extends('web.layouts.dashboard_master')

@section('title', 'Clinic Dashboard')
@section('header_title', 'Clinic Overview')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #00897b; font-weight: 700;">{{ __('Welcome back,') }} {{ Auth::user()->name }}</h3>
        <p class="text-muted">{{ __('Here is your clinic performance overview.') }}</p>
    </div>
</div>

<!-- 1. Key Metrics Overview -->
<div class="row">
    <!-- Total Patients -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-teal">
                <i class="las la-users"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $totalPatients }}</h3>
                <small class="text-muted">{{ __('Total Patients') }}</small>
                <div class="text-success small mt-1"><i class="las la-arrow-up"></i> 5% {{ __('this month') }}</div>
            </div>
        </div>
    </div>

    <!-- Active Plans -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-blue">
                <i class="las la-notes-medical"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $activePlans }}</h3>
                <small class="text-muted">{{ __('Active Treatment Plans') }}</small>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-green">
                <i class="las la-calendar-day"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $todayAppointments }}</h3>
                <small class="text-muted">{{ __('Today\'s Appointments') }}</small>
                <div class="text-info small mt-1">
                    {{ $completedToday }} {{ __('Completed') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding Payments -->
    <div class="col-xl-3 col-md-6 mb-4">
         <div class="stat-card h-100">
            <div class="icon-box icon-orange">
                <i class="las la-file-invoice-dollar"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ number_format($outstandingPayments) }} EGP</h3>
                <small class="text-muted">{{ __('Outstanding Payments') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- 2. Performance Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; height: 100%;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title font-weight-bold mb-0" style="color: #333;">{{ __('Clinic Performance') }}</h5>
                    <select class="custom-select custom-select-sm w-auto border-0 bg-light">
                        <option>{{ __('Last 30 Days') }}</option>
                        <option>{{ __('This Year') }}</option>
                    </select>
                </div>
                <!-- Chart -->
                <canvas id="performanceChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- 3. Quick Actions Panel -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; height: 100%;">
            <div class="card-body">
                <h5 class="card-title font-weight-bold mb-4">{{ __('Quick Actions') }}</h5>
                
                <a href="#" class="btn btn-light btn-block text-left mb-3 p-3 shadow-sm d-flex align-items-center transition-hover">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #00897b !important;">
                        <i class="las la-user-plus"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">{{ __('Add New Patient') }}</div>
                        <small class="text-muted">{{ __('Register a new profile') }}</small>
                    </div>
                </a>

                <a href="#" class="btn btn-light btn-block text-left mb-3 p-3 shadow-sm d-flex align-items-center transition-hover">
                    <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="las la-calendar-plus"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">{{ __('Create Appointment') }}</div>
                         <small class="text-muted">{{ __('Schedule a session') }}</small>
                    </div>
                </a>

                <a href="#" class="btn btn-light btn-block text-left mb-3 p-3 shadow-sm d-flex align-items-center transition-hover">
                    <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="las la-file-invoice"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">{{ __('Register Payment') }}</div>
                         <small class="text-muted">{{ __('Record an invoice') }}</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 4. Appointments Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Upcoming Timeline') }}</h5>
            </div>
            <div class="card-body px-4">
                @if($timeline->isEmpty())
                    <p class="text-muted text-center py-4">{{ __('No upcoming appointments scheduled.') }}</p>
                @else
                    <div class="timeline-wrapper">
                         @foreach($timeline as $appt)
                            <div class="d-flex align-items-center mb-4 pb-4 border-bottom position-relative">
                                <div class="mr-3 text-center" style="min-width: 80px;">
                                    <h5 class="font-weight-bold mb-0" style="color: #00897b;">{{ \Carbon\Carbon::parse($appt->start_time)->format('H:i') }}</h5>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appt->start_time)->format('M d') }}</small>
                                </div>
                                <div class="flex-grow-1 p-3 rounded" style="background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ $appt->patient->first_name }} {{ $appt->patient->last_name }}</h6>
                                            <span class="badge badge-light text-primary border">{{ ucfirst($appt->type) }}</span>
                                            <span class="text-muted small ml-2"><i class="las la-user-nurse"></i> {{ $appt->therapist->name ?? 'Unassigned' }}</span>
                                        </div>
                                        <div>
                                            @if($appt->status == 'scheduled')
                                                <span class="badge badge-info">{{ __('Scheduled') }}</span>
                                            @elseif($appt->status == 'completed')
                                                <span class="badge badge-success">{{ __('Completed') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($appt->status) }}</span>
                                            @endif
                                            
                                            <div class="dropdown d-inline-block ml-2">
                                                <button class="btn btn-sm btn-link text-muted" data-toggle="dropdown"><i class="las la-ellipsis-v"></i></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">{{ __('View Details') }}</a>
                                                    <a class="dropdown-item" href="#">{{ __('Check In') }}</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#">{{ __('Cancel') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover:hover {
        transform: translateY(-2px);
        background-color: #f1f1f1 !important;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('performanceChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Sessions Completed',
                data: [12, 19, 15, 25, 22, 10, 5],
                backgroundColor: '#00897b',
                borderRadius: 5
            },
            {
                label: 'New Patients',
                data: [2, 1, 3, 5, 2, 0, 1],
                backgroundColor: '#fb8c00',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush

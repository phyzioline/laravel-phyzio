@extends('web.layouts.dashboard_master')

@section('title', 'Company Dashboard')
@section('header_title', 'Company Portal')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #00897b; font-weight: 700;">{{ __('Welcome back, HealthCare Plus') }}</h3>
        <p class="text-muted">{{ __('Here\'s your hospital management overview') }}</p>
    </div>
</div>

<!-- Key Metrics Overview -->
<div class="row">
    <!-- Active Doctors -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-teal">
                <i class="las la-user-nurse"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">45</h3>
                <small class="text-muted">{{ __('Active Doctors') }}</small>
                <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('3 new this month') }}</div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-green">
                <i class="las la-calendar-check"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">234</h3>
                <small class="text-muted">{{ __('Today\'s Appointments') }}</small>
                <div class="text-info small mt-1"><i class="las la-clock"></i> {{ __('18 pending') }}</div>
            </div>
        </div>
    </div>

    <!-- Total Patients -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-blue">
                <i class="las la-users"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">1,247</h3>
                <small class="text-muted">{{ __('Total Patients') }}</small>
                <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('12% from last month') }}</div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="col-xl-3 col-md-6 mb-4">
         <div class="stat-card h-100">
            <div class="icon-box icon-orange">
                <i class="las la-dollar-sign"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">$125,480</h3>
                <small class="text-muted">{{ __('Monthly Revenue') }}</small>
                <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('8% from last month') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Appointments Overview Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Appointments Overview') }}</h5>
            </div>
            <div class="card-body px-4">
                <canvas id="appointmentsChart" height="150"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Recent Activities') }}</h5>
                 <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a>
            </div>
            <div class="card-body px-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #00897b !important; flex-shrink: 0;">
                        <i class="las la-user-plus"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="font-weight-bold text-dark mb-0">{{ __('New Doctor Registration') }}</h6>
                        <small class="text-muted">{{ __('Dr. Sarah Johnson joined Cardiology department') }}</small>
                    </div>
                    <div class="text-muted small">{{ __('2 hours ago') }}</div>
                </div>

                 <div class="d-flex align-items-center mb-4">
                    <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="las la-calendar-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="font-weight-bold text-dark mb-0">{{ __('New Appointment Scheduled') }}</h6>
                        <small class="text-muted">{{ __('Patient John Doe scheduled with Dr. Smith') }}</small>
                    </div>
                    <div class="text-muted small">{{ __('4 hours ago') }}</div>
                </div>

                 <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="las la-file-alt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="font-weight-bold text-dark mb-0">{{ __('Medical Record Updated') }}</h6>
                        <small class="text-muted">{{ __('Updated records for Patient ID #892') }}</small>
                    </div>
                    <div class="text-muted small">{{ __('6 hours ago') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Lists -->
    <div class="col-lg-4 mb-4">
        <!-- Department Status -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Department Status') }}</h5>
            </div>
            <div class="card-body px-4">
                 <!-- Cardio -->
                 <div class="d-flex align-items-center mb-3">
                     <div class="bg-teal text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #00897b;">
                         <i class="las la-heartbeat"></i>
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Cardiology</h6>
                         <small class="text-muted">8 doctors, 45 patients</small>
                     </div>
                     <span class="badge badge-success badge-pill bg-light text-success border-0">{{ __('Active') }}</span>
                 </div>
                 <!-- Neuro -->
                 <div class="d-flex align-items-center mb-3">
                     <div class="bg-success text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                         <i class="las la-brain"></i>
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Neurology</h6>
                         <small class="text-muted">5 doctors, 32 patients</small>
                     </div>
                     <span class="badge badge-warning badge-pill bg-light text-warning border-0">{{ __('Busy') }}</span>
                 </div>
                 <!-- Ortho -->
                 <div class="d-flex align-items-center">
                     <div class="bg-info text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                         <i class="las la-bone"></i>
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Orthopedics</h6>
                         <small class="text-muted">6 doctors, 28 patients</small>
                     </div>
                     <span class="badge badge-success badge-pill bg-light text-success border-0">{{ __('Active') }}</span>
                 </div>
            </div>
        </div>

        <!-- Doctor Status -->
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Doctor Status') }}</h5>
                 <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a>
            </div>
            <div class="card-body px-4">
                 <!-- Doc 1 -->
                 <div class="d-flex align-items-center mb-3">
                     <div class="avatar-circle rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background-color: #00897b !important;">
                         DS
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Dr. David Smith</h6>
                         <small class="text-muted">Cardiology</small>
                     </div>
                     <span class="badge badge-success badge-pill bg-light text-success border-0">{{ __('Available') }}</span>
                 </div>
                 <!-- Doc 2 -->
                   <div class="d-flex align-items-center mb-3">
                     <div class="avatar-circle rounded-circle bg-success text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                         EW
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Dr. Emily Wilson</h6>
                         <small class="text-muted">Neurology</small>
                     </div>
                     <span class="badge badge-warning badge-pill bg-light text-warning border-0">{{ __('In Surgery') }}</span>
                 </div>
                 <!-- Doc 3 -->
                  <div class="d-flex align-items-center">
                     <div class="avatar-circle rounded-circle bg-info text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                         MJ
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">Dr. Michael Johnson</h6>
                         <small class="text-muted">Orthopedics</small>
                     </div>
                     <span class="badge badge-success badge-pill bg-light text-success border-0">{{ __('Available') }}</span>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('appointmentsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Scheduled',
                data: [42, 50, 48, 62, 58, 48, 52],
                borderColor: '#00897b',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.4
            }, {
                label: 'Completed',
                data: [35, 45, 40, 55, 52, 42, 48],
                borderColor: '#26a69a',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 5],
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush

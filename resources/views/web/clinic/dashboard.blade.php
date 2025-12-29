@extends('web.layouts.dashboard_master')

@section('title', 'Company Dashboard')
@section('header_title', 'Company Portal')

@section('content')
<!-- Specialty Selection Modal (Popup on first entry) -->
@if($clinic && !$clinic->hasSelectedSpecialty())
<div class="modal fade show" id="specialtySelectionModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="display: block;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h4 class="modal-title font-weight-bold">
                    <i class="las la-stethoscope"></i> {{ __('Select Your Physical Therapy Specialty') }}
                </h4>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-4">
                    <i class="las la-info-circle"></i> 
                    <strong>{{ __('Welcome!') }}</strong> {{ __('Please select your clinic\'s primary specialty to activate the right tools, assessment forms, and treatment templates for your practice.') }}
                </div>
                <p class="text-muted mb-4">{{ __('This selection will customize your dashboard, reservation forms, payment calculations, and weekly program templates. You can add more specialties later from settings.') }}</p>
                <div class="text-center">
                    <a href="{{ route('clinic.specialty-selection.show') }}" class="btn btn-primary btn-lg">
                        <i class="las la-arrow-right"></i> {{ __('Select Specialty Now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #00897b; font-weight: 700;">
            {{ __('Welcome back') }}, {{ $clinic->name ?? 'Clinic' }}
            @if($clinicSpecialty)
                <span class="badge badge-info ml-2">{{ $specialtyDisplayName }}</span>
            @endif
        </h3>
        <p class="text-muted">
            @if($clinicSpecialty)
                {{ __('Your') }} {{ $specialtyDisplayName }} {{ __('clinic overview') }}
            @else
                {{ __('Here\'s your hospital management overview') }}
            @endif
        </p>
    </div>
</div>

<!-- Key Metrics Overview -->
<div class="row">
    <!-- Active Programs (NEW) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card h-100">
            <div class="icon-box icon-primary">
                <i class="las la-clipboard-list"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $activePrograms ?? 0 }}</h3>
                <small class="text-muted">{{ __('Active Programs') }}</small>
                <div class="text-info small mt-1">
                    <a href="{{ route('clinic.programs.index') }}" class="text-info">
                        <i class="las la-eye"></i> {{ __('View All') }} ({{ $totalPrograms ?? 0 }})
                    </a>
                </div>
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
                <h3 class="font-weight-bold mb-0">{{ $todayAppointments ?? 0 }}</h3>
                <small class="text-muted">{{ __('Today\'s Appointments') }}</small>
                <div class="text-info small mt-1">
                    <i class="las la-check-circle"></i> {{ $completedToday ?? 0 }} {{ __('completed') }}
                </div>
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
                <h3 class="font-weight-bold mb-0">{{ number_format($totalPatients ?? 0) }}</h3>
                <small class="text-muted">{{ __('Total Patients') }}</small>
                <div class="text-success small mt-1">
                    <a href="{{ route('clinic.patients.index') }}" class="text-success">
                        <i class="las la-arrow-right"></i> {{ __('Manage') }}
                    </a>
                </div>
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
                <h3 class="font-weight-bold mb-0">${{ number_format($monthlyRevenue ?? 0, 0) }}</h3>
                <small class="text-muted">{{ __('Monthly Revenue') }}</small>
                @if($outstandingPayments > 0)
                <div class="text-warning small mt-1">
                    <i class="las la-exclamation-triangle"></i> ${{ number_format($outstandingPayments, 0) }} {{ __('pending') }}
                </div>
                @endif
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

        <!-- Recent Activities (REAL DATA) -->
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Recent Activities') }}</h5>
                 <a href="{{ route('clinic.programs.index') }}" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a>
            </div>
            <div class="card-body px-4">
                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    @foreach($recentActivities as $activity)
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-{{ $activity->color ?? 'primary' }} text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; background-color: #00897b !important; flex-shrink: 0;">
                            <i class="{{ $activity->icon ?? 'las la-info-circle' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold text-dark mb-0">
                                @if(isset($activity->link))
                                    <a href="{{ $activity->link }}" class="text-dark">{{ $activity->title }}</a>
                                @else
                                    {{ $activity->title }}
                                @endif
                            </h6>
                            <small class="text-muted">{{ $activity->description ?? '' }}</small>
                        </div>
                        <div class="text-muted small">{{ $activity->time ?? '' }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="las la-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No recent activities') }}</p>
                        <a href="{{ route('clinic.programs.create') }}" class="btn btn-sm btn-primary">
                            <i class="las la-plus"></i> {{ __('Create Your First Program') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Status Lists -->
    <div class="col-lg-4 mb-4">
        <!-- Clinic Specialty Info -->
        @if($clinicSpecialty)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Clinic Specialty') }}</h5>
            </div>
            <div class="card-body px-4">
                 <div class="d-flex align-items-center">
                     <div class="bg-primary text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #00897b;">
                         <i class="las la-stethoscope fa-2x"></i>
                     </div>
                     <div class="flex-grow-1">
                         <h5 class="mb-0 font-weight-bold">{{ $specialtyDisplayName }}</h5>
                         <small class="text-muted">{{ __('Primary Specialty') }}</small>
                     </div>
                     <a href="{{ route('clinic.specialty-selection.show') }}" class="btn btn-sm btn-outline-primary">
                         {{ __('Change') }}
                     </a>
                 </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Appointments -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Upcoming Appointments') }}</h5>
                 <a href="{{ route('clinic.appointments.index') }}" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a>
            </div>
            <div class="card-body px-4">
                @if($timeline && $timeline->count() > 0)
                    @foreach($timeline as $appointment)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            {{ substr($appointment->patient->first_name ?? 'P', 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 font-weight-bold">{{ $appointment->patient->first_name ?? 'Patient' }} {{ $appointment->patient->last_name ?? '' }}</h6>
                            <small class="text-muted">
                                {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                                @if($appointment->specialty)
                                    • {{ ucfirst(str_replace('_', ' ', $appointment->specialty)) }}
                                @endif
                            </small>
                        </div>
                        <span class="badge badge-{{ $appointment->status == 'scheduled' ? 'primary' : 'success' }} badge-pill">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="las la-calendar-times fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('No upcoming appointments') }}</p>
                        <a href="{{ route('clinic.appointments.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="las la-plus"></i> {{ __('Schedule Appointment') }}
                        </a>
                    </div>
                @endif
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
            labels: {!! json_encode($monthlyPerformance['labels'] ?? ['Week 1', 'Week 2', 'Week 3', 'Week 4']) !!},
            datasets: [{
                label: '{{ __('Appointments') }}',
                data: {!! json_encode($monthlyPerformance['data'] ?? [0, 0, 0, 0]) !!},
                borderColor: '#00897b',
                backgroundColor: 'transparent',
                borderWidth: 2,
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

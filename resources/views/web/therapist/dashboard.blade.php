@extends('therapist.layouts.app')

@push('css')
<style>
    body, .card, .btn {
        font-size: 1.05rem !important; /* Enhanced font size */
    }
    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        display: flex;
        align-items: center;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-right: 1.5rem;
        color: #fff;
    }
    .icon-teal { background: #28a745; }
    .icon-green { background: #43a047; }
    .icon-blue { background: #1e88e5; }
    .icon-orange { background: #fb8c00; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #28a745; font-weight: 700;">{{ __('Welcome back, Dr.') }} {{ Auth::user()->name }}</h3>
        <p class="text-muted">{{ __('Here\'s your Home Visits overview') }}</p>
    </div>
</div>

<!-- Account Health Widget -->
@if(in_array(Auth::user()->type, ['vendor', 'company', 'therapist']))
    <div class="row mb-4">
        <div class="col-lg-4">
            @include('web.components.account-health')
        </div>
    </div>
@endif

<!-- Key Metrics Overview - Matching Company Dashboard Style -->
<div class="row">
    <!-- Active Patients -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Active Patients') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activePatientsCount ?? 0 }}</div>
                        <small class="text-muted">{{ __('Last 6 months') }}</small>
                    </div>
                    <div class="col-auto">
                        <i class="las la-user-injured fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Visits -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Today\'s Visits') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todaysAppointmentsCount ?? 0 }}</div>
                        <small class="text-muted">{{ __('Scheduled') }}</small>
                    </div>
                    <div class="col-auto">
                        <i class="las la-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Pending Requests') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingRequestsCount ?? 0 }}</div>
                        <small class="text-muted"><a href="{{ route('therapist.schedule.index') }}" class="text-warning">{{ __('Review Now') }}</a></small>
                    </div>
                    <div class="col-auto">
                        <i class="las la-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Earnings -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Monthly Earnings') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyEarnings ?? 0) }} EGP</div>
                        @if(isset($earningsGrowth) && $earningsGrowth > 0)
                        <small class="text-success"><i class="las la-arrow-up"></i> {{ number_format($earningsGrowth, 1) }}%</small>
                        @else
                        <small class="text-muted">{{ __('vs last month') }}</small>
                        @endif
                    </div>
                    <div class="col-auto">
                        <i class="las la-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Visits Overview Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Visits Overview') }}</h5>
            </div>
            <div class="card-body px-4">
                <canvas id="visitsChart" height="150"></canvas>
            </div>
        </div>

        <!-- Recent Activities (Updates) -->
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Recent Activity') }}</h5>
                 <!-- <a href="#" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a> -->
            </div>
            <div class="card-body px-4">
                @forelse($recentActivities as $activity)
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: {{ $activity->status == 'completed' ? '#43a047' : '#28a745' }} !important; flex-shrink: 0;">
                        <i class="las {{ $activity->status == 'completed' ? 'la-check-circle' : 'la-calendar' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="font-weight-bold text-dark mb-0">
                            {{ $activity->status == 'completed' ? __('Visit Completed') : __('Appointment Updated') }}
                        </h6>
                        <small class="text-muted">
                            {{ $activity->patient->name ?? 'Patient' }} - {{ $activity->status }}
                        </small>
                    </div>
                    <small class="text-muted">{{ $activity->updated_at->diffForHumans() }}</small>
                </div>
                @empty
                <p class="text-muted text-center py-3">{{ __('No recent activity.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Schedule -->
    <div class="col-lg-4 mb-4">
        
        <!-- Today's Schedule List -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Today\'s Schedule') }}</h5>
            </div>
            <div class="card-body px-4">
                 @forelse($todaysAppointments as $appt)
                 <div class="d-flex align-items-center mb-3">
                     <div class="bg-light text-primary rounded mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px;">
                         {{ \Carbon\Carbon::parse($appt->scheduled_at ?? $appt->appointment_time ?? now())->format('H:i') }}
                     </div>
                     <div class="flex-grow-1">
                         <h6 class="mb-0 font-weight-bold">{{ $appt->patient->name ?? 'Unknown' }}</h6>
                         <small class="text-muted">{{ $appt->address ?? $appt->location_address ?? 'Home Visit' }}</small>
                     </div>
                 </div>
                 @empty
                 <p class="text-muted text-center">{{ __('No visits scheduled today.') }}</p>
                 @endforelse
            </div>
             <div class="card-footer bg-white border-0 pb-4 px-4">
                <a href="{{ route('therapist.schedule.index') }}" class="btn btn-outline-primary btn-block rounded-pill">{{ __('Manage Schedule') }}</a>
             </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 pt-4 px-4">
                 <h5 class="card-title font-weight-bold mb-0">{{ __('Quick Actions') }}</h5>
            </div>
            <div class="card-body px-4">
                <a href="{{ route('therapist.availability.edit') }}" class="btn btn-primary btn-block mb-2 shadow-sm" style="background-color: #28a745; border: none;">
                    <i class="las la-calendar-alt mr-2"></i> {{ __('Set Availability') }}
                </a>
                @if(auth()->user()->hasRole('instructor') || auth()->user()->type == 'therapist')
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-outline-secondary btn-block mb-2">
                    <i class="las la-plus-circle mr-2"></i> {{ __('Create New Course') }}
                </a>
                @endif
                 <a href="{{ route('therapist.profile.edit') }}" class="btn btn-outline-secondary btn-block">
                    <i class="las la-user-cog mr-2"></i> {{ __('Update Profile') }}
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('visitsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Visits',
                data: {!! json_encode($chartData) !!},
                backgroundColor: '#28a745',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { drawBorder: false }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush

@extends('web.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- 1. Header Bar (Partially handled by main layout, enhanced here) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Hello, Dr.') }} {{ Auth::user()->name }}</h2>
            <p class="text-muted">{{ __('Here is your daily activity overview.') }}</p>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn btn-white shadow-sm mr-3"><i class="las la-bell text-warning"></i> <span class="badge badge-danger">3</span></button>
            <a href="{{ route('therapist.profile') }}" class="btn btn-primary shadow-sm"><i class="las la-user-cog"></i> {{ __('Settings') }}</a>
        </div>
    </div>

    <!-- 2. KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Today\'s Appointments') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todaysAppointmentsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Active Patients') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activePatientsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-user-injured fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Pending Notes') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingNotes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-sticky-note fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Monthly Earnings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyEarnings, 2) }} {{ __('EGP') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2.5 Modules Access (New) -->
    @php
        $therapistProfile = Auth::user()->therapistProfile ?? null;
    @endphp
    
    @if($therapistProfile && ($therapistProfile->can_access_clinic || $therapistProfile->can_access_instructor))
    <div class="row mb-4">
        @if($therapistProfile->can_access_clinic)
        <div class="col-md-6">
            <div class="card shadow border-0 hover-lift h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper bg-teal-light text-teal rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #e0f2f1; color: #00897b;">
                        <i class="las la-hospital-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1">{{ __('Clinic ERP System') }}</h5>
                        <p class="text-muted mb-2 small">{{ __('Manage patients, appointments, and treatment plans.') }}</p>
                        <a href="{{ route('clinic.dashboard') }}" class="btn btn-sm btn-teal text-white" style="background-color: #00897b;">
                            {{ __('Access Clinic Dashboard') }} <i class="las la-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($therapistProfile->can_access_instructor)
        <div class="col-md-6">
            <div class="card shadow border-0 hover-lift h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-wrapper bg-blue-light text-blue rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #e3f2fd; color: #1e88e5;">
                        <i class="las la-chalkboard-teacher" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1">{{ __('Instructor Hub') }}</h5>
                        <p class="text-muted mb-2 small">{{ __('Create courses, manage students, and view earnings.') }}</p>
                        <a href="{{ route('instructor.dashboard') }}" class="btn btn-sm btn-info text-white">
                            {{ __('Access Instructor Dashboard') }} <i class="las la-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="row">
        <!-- 3. Today's Timeline -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Today\'s Timeline') }}</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($todaysAppointments as $appt)
                        <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                            <div class="mr-3 text-center" style="min-width: 80px;">
                                <div class="font-weight-bold text-primary">{{ Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</div>
                                <small class="text-muted">{{ $appt->duration_minutes }} min</small>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 font-weight-bold">{{ $appt->patient->name ?? 'Unknown Patient' }}</h6>
                                <span class="badge badge-pill badge-light text-uppercase">{{ $appt->type }}</span>
                                <small class="text-muted ml-2"><i class="las la-map-marker"></i> {{ $appt->location ?? 'Clinic Room 1' }}</small>
                            </div>
                            <div>
                                @if($appt->status == 'booked')
                                    <button class="btn btn-sm btn-success">{{ __('Start') }}</button>
                                @elseif($appt->status == 'in_progress')
                                    <button class="btn btn-sm btn-warning">{{ __('Finish') }}</button>
                                @else
                                    <button class="btn btn-sm btn-light" disabled>{{ ucfirst($appt->status) }}</button>
                                @endif
                                <button class="btn btn-sm btn-outline-secondary ml-1"><i class="las la-bars"></i></button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('web/assets/images/empty_calendar.svg') }}" width="150" class="mb-3 opacity-50">
                            <p class="text-muted">{{ __('No appointments scheduled for today.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 6. Quick Actions -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card shadow border-0 bg-primary text-white">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ __('New Appointment') }}</h5>
                                <p class="mb-0 small text-white-50">{{ __('Schedule a session manually') }}</p>
                            </div>
                            <button class="btn btn-light text-primary rounded-circle"><i class="las la-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow border-0 bg-success text-white">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ __('Add Session Note') }}</h5>
                                <p class="mb-0 small text-white-50">{{ __('Document a completed visit') }}</p>
                            </div>
                            <button class="btn btn-light text-success rounded-circle"><i class="las la-file-medical"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- 4. Mini Calendar & 5. Active Patients -->
        <div class="col-lg-4 mb-4">
            <!-- Mini Calendar -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Calendar') }}</h6>
                </div>
                <div class="card-body text-center">
                    <!-- Placeholder for Calendar Compontent -->
                     <img src="https://dummyimage.com/300x250/f0f0f0/cccccc&text=Interactive+Calendar+Widget" class="img-fluid rounded">
                </div>
            </div>

            <!-- Active Patients -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Active Patients') }}</h6>
                    <a href="#" class="small">{{ __('View All') }}</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($activePatients as $patient)
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar bg-light-primary rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span class="font-weight-bold text-primary">{{ substr($patient->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $patient->name }}</h6>
                                    <small class="text-muted">{{ $patient->condition ?? 'General Therapy' }}</small>
                                </div>
                                <button class="btn btn-sm btn-light"><i class="las la-comment-alt"></i></button>
                            </li>
                        @empty
                             <li class="list-group-item text-center text-muted">{{ __('No active patients.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 8. Performance & Analytics -->
    <div class="row">
         <div class="col-md-12">
             <div class="card shadow mb-4">
                 <div class="card-header py-3 bg-white">
                      <h6 class="m-0 font-weight-bold text-primary">{{ __('Performance Analytics') }}</h6>
                 </div>
                 <div class="card-body">
                      <!-- Placeholder for Charts -->
                      <div class="row">
                          <div class="col-md-8">
                               <img src="https://dummyimage.com/800x300/f8f9fa/aaaaaa&text=Earning+&+Sessions+Graph" class="img-fluid w-100 rounded">
                          </div>
                          <div class="col-md-4">
                               <img src="https://dummyimage.com/400x300/f8f9fa/aaaaaa&text=Patient+Demographics" class="img-fluid w-100 rounded">
                          </div>
                      </div>
                 </div>
             </div>
         </div>
    </div>
</div>
@endsection

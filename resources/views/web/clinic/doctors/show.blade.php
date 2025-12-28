@extends('web.layouts.dashboard_master')

@section('title', 'Doctor Profile')
@section('header_title', 'Doctor Profile')

@section('content')
<div class="row">
    <!-- Sidebar / Info -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                 <div class="avatar-circle rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                    <i class="las la-user-md text-muted" style="font-size: 60px;"></i>
                </div>
                <h4 class="font-weight-bold mb-1">{{ $doctorData->name ?? $doctor->name }}</h4>
                <p class="text-muted">{{ $doctorData->specialty ?? $doctor->specialization ?? 'General' }}</p>
                <div class="badge {{ ($doctorData->status ?? 'Available') == 'Available' ? 'badge-success' : (($doctorData->status ?? 'Available') == 'Busy' ? 'badge-danger' : 'badge-info') }} px-3 py-2 mt-2">
                    {{ $doctorData->status ?? 'Available' }}
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    <a href="mailto:{{ $doctorData->email ?? $doctor->email }}" class="btn btn-outline-primary btn-sm mx-1">
                        <i class="las la-envelope"></i> {{ __('Message') }}
                    </a>
                    <a href="tel:{{ $doctorData->phone ?? $doctor->phone }}" class="btn btn-outline-success btn-sm mx-1">
                        <i class="las la-phone"></i> {{ __('Call') }}
                    </a>
                </div>
            </div>
             <div class="card-footer bg-white border-0 pb-4">
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <h5 class="font-weight-bold mb-0">{{ $doctorData->patients ?? 0 }}</h5>
                        <small class="text-muted">{{ __('Patients') }}</small>
                    </div>
                    <div class="col-6">
                        @if(isset($doctorData->appointments))
                            <h5 class="font-weight-bold mb-0">{{ $doctorData->appointments }}</h5>
                            <small class="text-muted">{{ __('Appointments') }}</small>
                        @else
                            <h5 class="font-weight-bold mb-0">-</h5>
                            <small class="text-muted">{{ __('Rating') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">{{ __('Contact Information') }}</h6>
                <div class="d-flex align-items-center mb-2">
                    <i class="las la-envelope text-muted mr-2"></i> 
                    <a href="mailto:{{ $doctorData->email ?? $doctor->email }}">{{ $doctorData->email ?? $doctor->email }}</a>
                </div>
                 <div class="d-flex align-items-center">
                    <i class="las la-phone text-muted mr-2"></i> 
                    <a href="tel:{{ $doctorData->phone ?? $doctor->phone }}">{{ $doctorData->phone ?? $doctor->phone }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
     <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Professional Bio</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $doctorData->bio ?? $doctor->bio ?? __('No bio available.') }}</p>
            </div>
        </div>
        
        @php
            $clinic = $clinic ?? null;
            $recentAppointments = [];
            if ($clinic) {
                $recentAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->with('patient')
                    ->latest()
                    ->take(5)
                    ->get();
            }
        @endphp
        
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold mb-0">{{ __('Recent Activity') }}</h5>
                <a href="{{ route('clinic.appointments.index', ['doctor_id' => $doctor->id]) }}" class="btn btn-sm btn-link">{{ __('View All') }}</a>
            </div>
            <div class="card-body">
                @if($recentAppointments && count($recentAppointments) > 0)
                    <div class="timeline">
                        @foreach($recentAppointments as $appointment)
                        <div class="pb-3 border-left pl-4 position-relative ml-2">
                            <div class="position-absolute bg-{{ $appointment->status == 'completed' ? 'success' : 'primary' }} rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 5px;"></div>
                            <h6 class="font-weight-bold mb-1">
                                {{ ucfirst($appointment->status) }} {{ __('Appointment') }}
                            </h6>
                            <p class="text-muted small mb-0">
                                {{ __('Patient') }}: {{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}
                                @if($appointment->appointment_date)
                                    • {{ $appointment->appointment_date->format('M d, Y h:i A') }}
                                @endif
                            </p>
                            <small class="text-muted">{{ $appointment->created_at->diffForHumans() }}</small>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="las la-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No recent appointments') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

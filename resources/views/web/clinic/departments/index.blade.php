@extends('web.layouts.dashboard_master')

@section('title', 'Departments')
@section('header_title', 'Services & Departments')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="font-weight-bold" style="color: #333;">{{ __('Departments') }}</h4>
        <p class="text-muted">{{ __('Specialties and service areas offered') }}</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('clinic.departments.create') }}" class="btn btn-primary"><i class="las la-plus"></i> {{ __('Add Department') }}</a>
    </div>
</div>

<div class="row">
    @forelse($departments as $dept)
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                     <div class="bg-teal text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: {{ $dept->status == 'Active' ? '#00897b' : '#ffa726' }};">
                         <i class="las la-stethoscope" style="font-size: 24px;"></i>
                     </div>
                     <div>
                         <h5 class="font-weight-bold text-dark mb-0">
                             {{ $dept->name }}
                             @if($dept->is_primary)
                                 <span class="badge badge-primary badge-sm">Primary</span>
                             @endif
                         </h5>
                         <small class="text-muted">{{ $dept->head }}</small>
                     </div>
                </div>
                <p class="text-muted small">{{ $dept->description }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small">
                        <i class="las la-user-md text-primary"></i> 
                        <strong>{{ $dept->doctors_count }}</strong> {{ __('Doctors') }}
                        @if($dept->assigned_doctors && $dept->assigned_doctors->count() > 0)
                            <div class="mt-1">
                                @foreach($dept->assigned_doctors->take(2) as $doctor)
                                    <small class="d-block text-muted">
                                        <i class="las la-user"></i> {{ $doctor->name ?? 'N/A' }}
                                    </small>
                                @endforeach
                                @if($dept->assigned_doctors->count() > 2)
                                    <small class="text-muted">+{{ $dept->assigned_doctors->count() - 2 }} {{ __('more') }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                    <span class="badge {{ $dept->status == 'Active' ? 'badge-success' : 'badge-warning' }}">{{ __($dept->status) }}</span>
                </div>
                <div class="mt-3 text-right">
                    <a href="{{ route('clinic.departments.show', $dept->specialty) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="las la-info-circle"></i> {{ __('View Details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-stethoscope fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No Departments Found') }}</h5>
                <p class="text-muted">{{ __('You need to select your clinic specialty first.') }}</p>
                <a href="{{ route('clinic.specialty-selection.show') }}" class="btn btn-primary mt-3">
                    <i class="las la-stethoscope"></i> {{ __('Select Your Specialty') }}
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection


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
    @foreach($departments as $dept)
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                     <div class="bg-teal text-white rounded mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: {{ $dept->status == 'Active' ? '#00897b' : '#ffa726' }};">
                         <i class="las {{ $dept->name == 'Cardiology' ? 'la-heartbeat' : ($dept->name == 'Neurology' ? 'la-brain' : 'la-bone') }}" style="font-size: 24px;"></i>
                     </div>
                     <div>
                         <h5 class="font-weight-bold text-dark mb-0">{{ $dept->name }}</h5>
                         <small class="text-muted">{{ $dept->head }}</small>
                     </div>
                </div>
                <p class="text-muted small">{{ $dept->description }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="small">
                        <i class="las la-user-md text-primary"></i> <strong>{{ $dept->doctors_count }}</strong> Doctors
                    </div>
                    <span class="badge {{ $dept->status == 'Active' ? 'badge-success' : 'badge-warning' }}">{{ $dept->status }}</span>
                </div>
                <div class="mt-3 text-right">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Manage Settings</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

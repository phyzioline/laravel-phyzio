@extends('web.layouts.dashboard_master')

@section('title', 'Doctors List')
@section('header_title', 'Doctors Management')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h4 class="font-weight-bold" style="color: #333;">{{ __('Medical Staff') }}</h4>
        <p class="text-muted">{{ __('Manage your clinic\'s doctors and specialists') }}</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('clinic.doctors.create') }}" class="btn btn-primary"><i class="las la-plus"></i> {{ __('Add New Doctor') }}</a>
    </div>
</div>

@if($doctors && $doctors->count() > 0)
<div class="row">
    @foreach($doctors as $doctor)
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <div class="avatar-circle rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="las la-user-md text-muted" style="font-size: 40px;"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">{{ $doctor->name }}</h5>
                <p class="text-muted small mb-2">{{ $doctor->specialty }}</p>
                <div class="badge badge-light text-primary mb-3">
                    {{ $doctor->patients ?? 0 }} {{ __('Patients') }}
                    @if(isset($doctor->appointments) && $doctor->appointments > 0)
                        <span class="text-muted">• {{ $doctor->appointments }} {{ __('Appointments') }}</span>
                    @endif
                </div>
                
                <div class="d-flex justify-content-center mb-3">
                     <a href="mailto:{{ $doctor->email }}" class="btn btn-sm btn-outline-info mr-2"><i class="las la-envelope"></i></a>
                     <a href="tel:{{ $doctor->phone }}" class="btn btn-sm btn-outline-success"><i class="las la-phone"></i></a>
                </div>
                
                <div class="border-top pt-3">
                    <span class="badge {{ $doctor->status == 'Available' ? 'badge-success' : ($doctor->status == 'Busy' ? 'badge-danger' : 'badge-info') }}">{{ $doctor->status }}</span>
                    <a href="{{ route('clinic.doctors.show', $doctor->id) }}" class="btn btn-sm btn-link text-primary ml-2">{{ __('View Profile') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-user-md fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No Doctors Found') }}</h5>
                <p class="text-muted">{{ __('Register doctors to assign them to appointments and patients.') }}</p>
                @if($clinic)
                    <a href="{{ route('clinic.doctors.create') }}" class="btn btn-primary mt-3">
                        <i class="las la-plus"></i> {{ __('Add Your First Doctor') }}
                    </a>
                @else
                    <div class="alert alert-warning mt-3">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>{{ __('Clinic Setup Required') }}</strong><br>
                        {{ __('Please set up your clinic first by completing your profile.') }}
                        <a href="{{ route('clinic.profile.index') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="las la-cog"></i> {{ __('Go to Profile Setup') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

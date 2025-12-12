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
                <div class="badge badge-light text-primary mb-3">{{ $doctor->patients }} Patients</div>
                
                <div class="d-flex justify-content-center mb-3">
                     <button class="btn btn-sm btn-outline-info mr-2"><i class="las la-envelope"></i></button>
                     <button class="btn btn-sm btn-outline-success"><i class="las la-phone"></i></button>
                </div>
                
                <div class="border-top pt-3">
                    <span class="badge {{ $doctor->status == 'Available' ? 'badge-success' : 'badge-warning' }}">{{ $doctor->status }}</span>
                    <a href="{{ route('clinic.doctors.show', 1) }}" class="btn btn-sm btn-link text-primary ml-2">View Profile</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

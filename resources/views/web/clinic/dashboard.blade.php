@extends('web.therapist.layout')

@section('header_title', 'Clinic Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card-box text-center bg-primary text-white">
            <h1 class="display-4">{{ $stats['total_patients'] }}</h1>
            <p class="mb-0">Total Patients</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center bg-success text-white">
            <h1 class="display-4">{{ $stats['today_appointments'] }}</h1>
            <p class="mb-0">Today's Appointments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center bg-info text-white">
            <h1 class="display-4">{{ $stats['total_appointments'] }}</h1>
            <p class="mb-0">Total Appointments</p>
        </div>
    </div>
</div>

<div class="card-box">
    <h4 class="font-weight-bold mb-4">Clinic Information</h4>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Name:</strong> {{ $clinic->name }}</p>
            <p><strong>Address:</strong> {{ $clinic->address }}, {{ $clinic->city }}</p>
            <p><strong>Phone:</strong> {{ $clinic->phone }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Subscription:</strong> <span class="badge badge-success">{{ ucfirst($clinic->subscription_tier) }}</span></p>
            <p><strong>Status:</strong> <span class="badge badge-{{ $clinic->is_active ? 'success' : 'danger' }}">{{ $clinic->is_active ? 'Active' : 'Inactive' }}</span></p>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('clinic.patients.index') }}" class="btn btn-primary mr-2"><i class="las la-users"></i> Manage Patients</a>
        <button class="btn btn-outline-secondary"><i class="las la-calendar"></i> View Appointments</button>
    </div>
</div>
@endsection

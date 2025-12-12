@extends('therapist.layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Appointment Details') }}</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                 <h6 class="m-0 font-weight-bold text-primary">{{ __('Session Information') }}</h6>
                 <span class="badge badge-success">{{ $appointment->status }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-uppercase text-muted font-weight-bold">Patient</small>
                        <h5 class="font-weight-bold text-dark">{{ $appointment->patient_name }}</h5>
                    </div>
                     <div class="col-md-6">
                        <small class="text-uppercase text-muted font-weight-bold">Type</small>
                        <h5 class="font-weight-bold text-dark">{{ $appointment->type }}</h5>
                    </div>
                </div>
                
                 <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-uppercase text-muted font-weight-bold">Date</small>
                        <h5 class="text-dark">{{ $appointment->date }}</h5>
                    </div>
                     <div class="col-md-6">
                        <small class="text-uppercase text-muted font-weight-bold">Time</small>
                        <h5 class="text-dark">{{ $appointment->time }}</h5>
                    </div>
                </div>
                
                <hr>
                <div class="form-group">
                    <label class="font-weight-bold">Therapist Notes</label>
                    <textarea class="form-control" rows="4">{{ $appointment->notes }}</textarea>
                </div>
                <button class="btn btn-primary">Save Notes</button>
            </div>
        </div>
    </div>
    
     <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                 <h6 class="m-0 font-weight-bold text-primary">{{ __('Actions') }}</h6>
            </div>
            <div class="card-body">
                <button class="btn btn-success btn-block mb-3"><i class="las la-video"></i> Start Video Call</button>
                <button class="btn btn-info btn-block mb-3"><i class="las la-file-alt"></i> View Medical Records</button>
                <button class="btn btn-warning btn-block mb-3"><i class="las la-calendar-alt"></i> Reschedule</button>
                <button class="btn btn-danger btn-block"><i class="las la-times"></i> Cancel Appointment</button>
            </div>
        </div>
    </div>
</div>
@endsection

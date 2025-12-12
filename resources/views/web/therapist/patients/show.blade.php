@extends('therapist.layouts.app')

@section('title', 'Patient Profile')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Patient Profile') }}</h1>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <img class="img-profile rounded-circle mb-3" src="{{ $patient->image }}" style="width: 120px; height: 120px;">
                <h4 class="font-weight-bold text-dark">{{ $patient->name }}</h4>
                <p class="text-muted mb-1">{{ $patient->condition }}</p>
                <span class="badge badge-success px-3 py-2">{{ $patient->status }}</span>
                
                <div class="mt-4">
                     <button class="btn btn-primary btn-sm"><i class="las la-envelope"></i> Message</button>
                     <button class="btn btn-info btn-sm"><i class="las la-phone"></i> Call</button>
                </div>
            </div>
             <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted">Email</span>
                    <span class="font-weight-bold">{{ $patient->email }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted">Phone</span>
                    <span class="font-weight-bold">{{ $patient->phone }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="text-muted">Next Visit</span>
                    <span class="font-weight-bold text-primary">{{ $patient->next_visit }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white border-bottom-0">
                 <ul class="nav nav-tabs card-header-tabs" id="patientTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">{{ __('Overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="records-tab" data-toggle="tab" href="#records" role="tab">{{ __('Medical Records') }}</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" id="appointments-tab" data-toggle="tab" href="#appointments" role="tab">{{ __('Appointments') }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="patientTabContent">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <h5 class="font-weight-bold">Notes</h5>
                        <p>Patient recovering well from ACL surgery. Range of motion increasing appropriately.</p>
                        
                        <h5 class="font-weight-bold mt-4">Conditions</h5>
                         <span class="badge badge-light border p-2">Hypertension</span>
                         <span class="badge badge-light border p-2">Diabetes Type 2</span>
                    </div>
                    <div class="tab-pane fade" id="records" role="tabpanel">
                        <p class="text-muted">No records found.</p>
                    </div>
                     <div class="tab-pane fade" id="appointments" role="tabpanel">
                        <p class="text-muted">No past appointments.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

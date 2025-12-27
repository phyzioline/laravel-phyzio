@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Patient Management') }}</h2>
            <p class="text-muted">{{ __('Manage your patient records and medical history') }}</p>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary mr-2 shadow-sm"><i class="las la-file-export"></i> {{ __('Export') }}</button>
            <a href="{{ route('therapist.patients.create') }}" class="btn btn-primary shadow-sm"><i class="las la-user-plus"></i> {{ __('Add Patient') }}</a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="las la-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-left-0" placeholder="{{ __('Search patients by name, ID, or condition...') }}">
                    </div>
                </div>
                <div class="col-md-3">
                     <select class="form-control custom-select">
                        <option selected>{{ __('All Patients') }}</option>
                        <option value="1">{{ __('Needs Follow-up') }}</option>
                        <option value="2">{{ __('Stable') }}</option>
                        <option value="3">{{ __('Recovered') }}</option>
                        <option value="4">{{ __('Critical') }}</option>
                    </select>
                </div>
                 <div class="col-md-3">
                     <select class="form-control custom-select">
                        <option selected>{{ __('Sort by Name') }}</option>
                        <option value="1">{{ __('Sort by Last Visit') }}</option>
                        <option value="2">{{ __('Sort by Age') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                <div class="card-body">
                    <i class="las la-users text-teal mb-2" style="font-size: 32px; color: #00897b;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $totalPatients ?? 0 }}</h3>
                    <p class="text-muted small mb-0">{{ __('Total Patients') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                <div class="card-body">
                    <i class="las la-user-plus text-success mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $newThisMonth ?? 0 }}</h3>
                    <p class="text-muted small mb-0">{{ __('New This Month') }}</p>
                </div>
             </div>
        </div>
         <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                <div class="card-body">
                    <i class="las la-exclamation-triangle text-warning mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $needFollowup ?? 0 }}</h3>
                    <p class="text-muted small mb-0">{{ __('Need Follow-up') }}</p>
                </div>
             </div>
        </div>
         <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                <div class="card-body">
                    <i class="las la-heartbeat text-danger mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $criticalCases ?? 0 }}</h3>
                    <p class="text-muted small mb-0">{{ __('Critical Cases') }}</p>
                </div>
             </div>
        </div>
    </div>

    <!-- Patient Grid (2 Columns) -->
    <div class="row">
        @forelse($patients as $patient)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                         <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-teal text-white rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 50px; height: 50px; background-color: #28a745; font-size: 20px;">
                                {{ $patient->image_initial }}
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark">{{ $patient->name }}</h5>
                                <div class="text-muted small">{{ $patient->id }} • Age: {{ $patient->age }} • {{ $patient->gender }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @php
                                $statusColor = 'success';
                                if($patient->status == 'Needs Follow-up') $statusColor = 'warning';
                                if($patient->status == 'Critical') $statusColor = 'danger';
                                if($patient->status == 'Stable') $statusColor = 'success';
                                if($patient->status == 'Recovered') $statusColor = 'success'; // or another color
                            @endphp
                            <span class="badge badge-dot mr-2 bg-{{ $statusColor }}"></span> <span class="text-muted small">{{ $patient->status }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="small text-muted">{{ __('Last visit:') }} <span class="text-dark">{{ $patient->last_visit }}</span></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        @foreach($patient->conditions as $condition)
                            <span class="badge badge-light border mr-1">{{ $condition }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-right">
                    <a href="{{ route('therapist.patients.show', $patient->user_id ?? $patient->id) }}" class="btn btn-sm btn-outline-primary">{{ __('View Profile') }}</a>
                </div>
                 <!-- Optional Footer Actions -->
                <!-- <div class="card-footer bg-white border-top-0 d-flex justify-content-end"> ... </div> -->
            </div>
        </div>
        @empty
             <div class="col-12 text-center py-5">
                <p class="text-muted">{{ __('No patients found.') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

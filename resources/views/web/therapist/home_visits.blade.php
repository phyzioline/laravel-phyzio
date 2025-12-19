@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Home Visits') }}</h2>
            <p class="text-muted">{{ __('View and manage your consultation schedule') }}</p>
        </div>
        <div>
             <button class="btn btn-outline-secondary mr-2 shadow-sm"><i class="las la-history"></i> {{ __('History') }}</button>
             <button class="btn btn-primary shadow-sm"><i class="las la-plus"></i> {{ __('New Visit') }}</button>
        </div>
    </div>

    <!-- Active Home Visit Card (Merged from Visits) -->
    @if(isset($activeVisit) && $activeVisit)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Active Home Visit: {{ $activeVisit->patient->name ?? 'Patient' }}</h4>
                    <span class="badge badge-light text-primary p-2">{{ strtoupper($activeVisit->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="las la-map-marker"></i> {{ $activeVisit->address }}</p>
                            <a href="https://maps.google.com/?q={{ $activeVisit->location_lat }},{{ $activeVisit->location_lng }}" target="_blank" class="btn btn-outline-primary">
                                <i class="las la-directions"></i> Open Navigation
                            </a>
                        </div>
                        <div class="col-md-6 text-center border-left">
                            @if($activeVisit->status == 'accepted')
                                <form action="{{ route('therapist.home_visits.status', $activeVisit->id) }}" method="POST">
                                    @csrf <input type="hidden" name="status" value="on_way">
                                    <button class="btn btn-warning btn-lg btn-block">Start Trip <i class="las la-car"></i></button>
                                </form>
                            @elseif($activeVisit->status == 'on_way')
                                <form action="{{ route('therapist.home_visits.status', $activeVisit->id) }}" method="POST">
                                    @csrf <input type="hidden" name="status" value="in_session">
                                    <button class="btn btn-success btn-lg btn-block">Arrived <i class="las la-check-circle"></i></button>
                                </form>
                            @elseif($activeVisit->status == 'in_session')
                                <button class="btn btn-info btn-lg btn-block" data-toggle="modal" data-target="#completeVisitModal">Complete Session <i class="las la-file-medical"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Row -->
    <div class="row mb-4">
        <!-- New Visit Stats -->
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-info py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Visit Requests') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableVisits->count() ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="las la-car-side fa-2x text-gray-300"></i></div>
                    </div>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-success py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Completed') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completed->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-warning py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Upcoming') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcoming->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
         <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-danger py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{ __('Cancelled') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelled->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <!-- Tabs & Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-0">
            <ul class="nav nav-pills" id="appointmentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="upcoming-tab" data-toggle="pill" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">{{ __('Upcoming') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-tab" data-toggle="pill" href="#past" role="tab" aria-controls="past" aria-selected="false">{{ __('Past') }}</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" id="cancelled-tab" data-toggle="pill" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">{{ __('Cancelled') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="visits-tab" data-toggle="pill" href="#visits" role="tab" aria-controls="visits" aria-selected="false">{{ __('Home Visit Requests') }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="appointmentTabsContent">
                <!-- Upcoming Tab -->
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcoming as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                                                {{ substr($appointment->patient->name ?? 'U', 0, 2) }}
                                            </div>
                                            <span class="font-weight-bold">{{ $appointment->patient->name ?? 'Unknown Patient' }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light border">{{ $appointment->service->name ?? $appointment->type }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td><span class="badge badge-warning">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('therapist.home_visits.show', $appointment->id) }}" class="btn btn-sm btn-light border shadow-sm" title="View Details"><i class="las la-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                         <div class="text-gray-500">No upcoming home visits found.</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Past Tab -->
                <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($past as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td><span class="badge badge-light border">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light border shadow-sm"><i class="las la-eye"></i></button>
                                    </td>
                                </tr>
                                @empty
                                     <tr><td colspan="4" class="text-center">No past home visits.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                 <!-- Cancelled Tab -->
                <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cancelled as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td>{{ $appointment->notes ?? 'No reason provided' }}</td>
                                </tr>
                                @empty
                                     <tr><td colspan="3" class="text-center">No cancelled home visits.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Home Visit Requests Tab -->
                <div class="tab-pane fade" id="visits" role="tabpanel" aria-labelledby="visits-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>Urgency</th>
                                    <th>Condition</th>
                                    <th>Location</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availableVisits ?? [] as $visit)
                                <tr>
                                    <td>
                                        @if($visit->urgency == 'urgent')
                                            <span class="badge badge-danger">URGENT</span>
                                        @else
                                            <span class="badge badge-primary">Scheduled</span>
                                        @endif
                                    </td>
                                    <td>{{ $visit->complain_type }}</td>
                                    <td>{{ $visit->city }} <small class="text-muted d-block">{{ Str::limit($visit->address, 30) }}</small></td>
                                    <td>{{ $visit->scheduled_at->diffForHumans() }}</td>
                                    <td>
                                        <form action="{{ route('therapist.home_visits.accept', $visit->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-primary">Accept Visit</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No new visit requests nearby.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Visit Modal (Merged) -->
@if(isset($activeVisit) && $activeVisit)
<div class="modal fade" id="completeVisitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('therapist.home_visits.complete', $activeVisit->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Complete Visit & Clinical Notes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chief Complaint</label>
                        <input type="text" name="chief_complaint" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Assessment Findings</label>
                        <textarea name="assessment_findings[]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Treatment Performed</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="treatment_performed[]" value="Manual Therapy" class="custom-control-input" id="tx_manual">
                            <label class="custom-control-label" for="tx_manual">Manual Therapy</label>
                        </div>
                         <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="treatment_performed[]" value="TherEx" class="custom-control-input" id="tx_ex">
                            <label class="custom-control-label" for="tx_ex">Therapeutic Exercise</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">Submit & Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

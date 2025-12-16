@extends('web.layouts.dashboard_master')

@section('title', 'My Home Visits')
@section('header_title', 'Home Visit Dashboard')

@section('content')
<div class="row">
    <!-- Active Visit Card (If Any) -->
    @if($activeVisit)
    <div class="col-md-12 mb-4">
        <div class="card border-primary shadow-lg">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Please Navigate to Patient</h4>
                <span class="badge badge-light text-primary p-2">{{ strtoupper($activeVisit->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="las la-user"></i> {{ $activeVisit->patient->name ?? 'Unknown Patient' }}</h5>
                        <p><i class="las la-map-marker"></i> {{ $activeVisit->address }}</p>
                        <p><i class="las la-stethoscope"></i> {{ $activeVisit->complain_type }} ({{ $activeVisit->urgency }})</p>
                        <a href="https://maps.google.com/?q={{ $activeVisit->location_lat }},{{ $activeVisit->location_lng }}" target="_blank" class="btn btn-outline-primary btn-block">
                            <i class="las la-directions"></i> Open Navigation
                        </a>
                    </div>
                    <div class="col-md-6 text-center border-left">
                        <h5>Action Required</h5>
                        
                        @if($activeVisit->status == 'accepted')
                            <p>Are you starting the trip?</p>
                            <form action="{{ route('therapist.visits.status', $activeVisit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="on_way">
                                <button class="btn btn-warning btn-lg btn-block">Start Trip <i class="las la-car"></i></button>
                            </form>
                        @elseif($activeVisit->status == 'on_way')
                            <p>Have you arrived?</p>
                            <form action="{{ route('therapist.visits.status', $activeVisit->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="in_session">
                                <button class="btn btn-success btn-lg btn-block">Check In / Arrived <i class="las la-check-circle"></i></button>
                            </form>
                        @elseif($activeVisit->status == 'in_session')
                            <p>Session in progress. Fill notes to complete.</p>
                            <button class="btn btn-info btn-lg btn-block" data-toggle="modal" data-target="#completeVisitModal">Complete Session <i class="las la-file-medical"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Available Visits -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-dark">Currently Available Requests</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Urgency</th>
                                <th>Condition</th>
                                <th>Location</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableVisits as $visit)
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
                                    <form action="{{ route('therapist.visits.accept', $visit->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Accept Visit</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No new visit requests nearby.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Visit Modal -->
@if($activeVisit)
<div class="modal fade" id="completeVisitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('therapist.visits.complete', $activeVisit->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Complete Visit & Clinical Notes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chief Complaint (Patient's words)</label>
                        <input type="text" name="chief_complaint" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Assessment Findings</label>
                        <textarea name="assessment_findings[]" class="form-control" rows="2" placeholder="e.g. ROM limited, Pain 7/10"></textarea>
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
                         <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="treatment_performed[]" value="Modalities" class="custom-control-input" id="tx_mod">
                            <label class="custom-control-label" for="tx_mod">Modalities (TENS, US)</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Payment Collected (Total: {{ $activeVisit->total_amount }} EGP)</label>
                        <input type="text" class="form-control" value="Cash Collected" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-lg">Submit Notes & Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

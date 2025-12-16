@extends('web.layouts.dashboard_master')

@section('title', 'Patient Chart')
@section('header_title')
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <span class="text-muted h6">Episode #{{ $episode->id }}</span>
            <h4 class="mb-0">{{ $episode->patient->name }} <span class="badge badge-light text-primary">{{ ucfirst($episode->specialty) }}</span></h4>
        </div>
        <div>
            <a href="{{ route('clinic.episodes.assessments.create', $episode->id) }}" class="btn btn-outline-primary"><i class="las la-clipboard-check"></i> New Assessment</a>
            <button class="btn btn-primary"><i class="las la-notes-medical"></i> Log Treatment</button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Patient Context Sidebar -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="text-uppercase text-muted font-size-12">Diagnosis</h6>
                <p class="font-weight-bold">{{ $episode->diagnosis_icd ?? 'N/A' }}</p>
                <hr>
                <h6 class="text-uppercase text-muted font-size-12">Chief Complaint</h6>
                <p>{{ $episode->chief_complaint }}</p>
                <hr>
                <h6 class="text-uppercase text-muted font-size-12">Guardian / Emergency</h6>
                <p class="mb-0">
                    @if($episode->patient->guardian_info)
                        {{ json_decode($episode->patient->guardian_info)->name ?? '' }} (Guardian)
                    @else 
                        <span class="text-muted font-italic">No info</span>
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Outcome Snapshot -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold">Recent Outcomes</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                     <!-- Dummy data for visualization -->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ROM (Knee Flex)
                        <span class="badge badge-success">Top 5%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pain Level
                        <span class="badge badge-warning">4/10</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Clinical Timeline -->
    <div class="col-md-9">
        <h5 class="mb-3">Clinical Timeline</h5>
        
        @forelse($episode->assessments->sortByDesc('assessment_date') as $assessment)
        <div class="card shadow-sm border-0 mb-3 border-left-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="text-primary mb-1">{{ ucfirst($assessment->type) }} Assessment</h5>
                    <small class="text-muted">{{ $assessment->assessment_date->format('M d, Y') }}</small>
                </div>
                <p class="text-muted mb-2">Therapist: {{ $assessment->therapist->name ?? 'Unknown Therapist' }}</p>
                
                <div class="bg-light p-3 rounded mb-2">
                    <strong>Analysis:</strong> {{ $assessment->analysis }}
                    @if($assessment->red_flags_detected)
                        <br><span class="text-danger font-weight-bold"><i class="las la-flag"></i> Red Flags Reported</span>
                    @endif
                </div>

                <!-- Showing Dynamic Data -->
                <div class="row mt-2">
                    <div class="col-md-6">
                        <h6>Objective Findings</h6>
                        <ul class="pl-3 small">
                            @foreach($assessment->objective_data ?? [] as $key => $val)
                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_array($val) ? json_encode($val) : $val }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="alert alert-info">No assessments logged yet. Start by creating an Initial Evaluation.</div>
        @endforelse

    </div>
</div>
@endsection

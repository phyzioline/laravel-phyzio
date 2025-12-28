@extends('web.layouts.dashboard_master')

@section('title', 'Clinical Note')
@section('header_title', 'Clinical Note Details')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <!-- Main Note Content -->
    <div class="col-lg-8">
        <!-- Note Header -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-0">{{ __('Clinical Note') }}</h5>
                    <small class="text-muted">
                        {{ $note->created_at->format('M d, Y H:i') }} | 
                        {{ \App\Models\ClinicalNote::SPECIALTIES[$note->specialty] ?? $note->specialty }} | 
                        {{ \App\Models\ClinicalNote::NOTE_TYPES[$note->note_type] ?? $note->note_type }}
                    </small>
                </div>
                <div>
                    @if($note->status !== 'signed')
                        <a href="{{ route('clinic.clinical-notes.edit', $note->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="las la-edit"></i> {{ __('Edit') }}
                        </a>
                        <button type="button" class="btn btn-sm btn-success" onclick="signNote({{ $note->id }})">
                            <i class="las la-signature"></i> {{ __('Sign Note') }}
                        </button>
                    @else
                        <span class="badge badge-success badge-lg">
                            <i class="las la-check-circle"></i> {{ __('Signed') }} 
                            @if($note->signed_at)
                                {{ $note->signed_at->format('M d, Y') }}
                            @endif
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Patient Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>{{ __('Patient') }}:</strong>
                        <a href="{{ route('clinic.patients.show', $note->patient_id) }}" class="ml-2">
                            {{ $note->patient->first_name ?? '' }} {{ $note->patient->last_name ?? '' }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Therapist') }}:</strong>
                        <span class="ml-2">{{ $note->therapist->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- SOAP Note -->
                <div class="soap-note">
                    @if($note->subjective)
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-primary">SUBJECTIVE (S)</h6>
                        <div class="border-left pl-3" style="border-left: 3px solid #00897b !important;">
                            <p class="mb-0">{!! nl2br(e($note->subjective)) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($note->objective)
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-info">OBJECTIVE (O)</h6>
                        <div class="border-left pl-3" style="border-left: 3px solid #17a2b8 !important;">
                            <p class="mb-0">{!! nl2br(e($note->objective)) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($note->assessment)
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-warning">ASSESSMENT (A)</h6>
                        <div class="border-left pl-3" style="border-left: 3px solid #ffc107 !important;">
                            <p class="mb-0">{!! nl2br(e($note->assessment)) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($note->plan)
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-success">PLAN (P)</h6>
                        <div class="border-left pl-3" style="border-left: 3px solid #28a745 !important;">
                            <p class="mb-0">{!! nl2br(e($note->plan)) !!}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Coding Information -->
                @if($note->diagnosis_codes || $note->procedure_codes)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="font-weight-bold mb-3">{{ __('Coding Information') }}</h6>
                    <div class="row">
                        @if($note->diagnosis_codes)
                        <div class="col-md-6">
                            <strong>{{ __('ICD-10 Codes') }}:</strong>
                            <div class="mt-2">
                                @foreach($note->diagnosis_codes as $code)
                                    <span class="badge badge-primary mr-1">{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @if($note->procedure_codes)
                        <div class="col-md-6">
                            <strong>{{ __('CPT Codes') }}:</strong>
                            <div class="mt-2">
                                @foreach($note->procedure_codes as $code)
                                    <span class="badge badge-info mr-1">{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if($note->coding_validated)
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="las la-check-circle"></i> {{ __('Coding validated') }}
                        </div>
                    @elseif($note->coding_errors)
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="las la-exclamation-triangle"></i> {{ __('Coding errors') }}: {{ $note->coding_errors }}
                        </div>
                    @endif
                </div>
                @endif

                <!-- Voice Transcription -->
                @if($note->voice_transcription)
                <div class="mt-4 pt-4 border-top">
                    <h6 class="font-weight-bold mb-2">{{ __('Voice Transcription') }}</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0 text-muted">{!! nl2br(e($note->voice_transcription)) !!}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar: Timeline & Info -->
    <div class="col-lg-4">
        <!-- Coding Validation Status -->
        @if(isset($codingValidation))
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="font-weight-bold mb-0">{{ __('Coding Validation') }}</h6>
            </div>
            <div class="card-body">
                @if($codingValidation['valid'])
                    <div class="alert alert-success mb-0">
                        <i class="las la-check-circle"></i> {{ __('All codes are valid') }}
                    </div>
                @else
                    <div class="alert alert-danger mb-0">
                        <i class="las la-exclamation-triangle"></i> {{ __('Validation errors found') }}
                        <ul class="mb-0 mt-2">
                            @foreach($codingValidation['errors'] as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Clinical Timeline -->
        @if(isset($timeline) && $timeline->count() > 0)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="font-weight-bold mb-0">{{ __('Clinical Timeline') }}</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($timeline as $event)
                    <div class="timeline-item mb-3 pb-3 border-bottom">
                        <div class="d-flex">
                            <div class="timeline-marker mr-3">
                                <i class="las la-circle text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <p class="text-muted mb-1 small">{!! nl2br(e($event->description ?? '')) !!}</p>
                                <small class="text-muted">{{ $event->event_date->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="font-weight-bold mb-0">{{ __('Quick Actions') }}</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('clinic.clinical-notes.index') }}" class="btn btn-outline-secondary btn-block mb-2">
                    <i class="las la-arrow-left"></i> {{ __('Back to Notes') }}
                </a>
                @if($note->appointment)
                <a href="{{ route('clinic.appointments.show', $note->appointment_id) }}" class="btn btn-outline-primary btn-block mb-2">
                    <i class="las la-calendar"></i> {{ __('View Appointment') }}
                </a>
                @endif
                <a href="{{ route('clinic.patients.show', $note->patient_id) }}" class="btn btn-outline-info btn-block">
                    <i class="las la-user"></i> {{ __('View Patient') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function signNote(noteId) {
    if (!confirm('Are you sure you want to sign this note? Signed notes cannot be edited.')) {
        return;
    }

    fetch(`/clinic/clinical-notes/${noteId}/sign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Note signed successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to sign note'));
            if (data.errors) {
                console.error('Validation errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to sign note');
    });
}
</script>
@endpush


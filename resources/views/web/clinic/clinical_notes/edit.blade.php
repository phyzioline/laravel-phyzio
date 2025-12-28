@extends('web.layouts.dashboard_master')

@section('title', 'Edit Clinical Note')
@section('header_title', 'Edit Clinical Note')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('clinic.clinical-notes.update', $note->id) }}" method="POST" id="clinicalNoteForm">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Patient') }}</label>
                                <input type="text" class="form-control" value="{{ $note->patient->first_name ?? '' }} {{ $note->patient->last_name ?? '' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Specialty') }}</label>
                                <input type="text" class="form-control" value="{{ \App\Models\ClinicalNote::SPECIALTIES[$note->specialty] ?? $note->specialty }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Note Type') }}</label>
                                <input type="text" class="form-control" value="{{ \App\Models\ClinicalNote::NOTE_TYPES[$note->note_type] ?? $note->note_type }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Status') }}</label>
                                <input type="text" class="form-control" value="{{ ucfirst($note->status) }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voice-to-Text Section -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">
                        <i class="las la-microphone"></i> {{ __('Voice-to-Text') }}
                        <button type="button" class="btn btn-sm btn-outline-primary ml-2" id="startRecording">
                            <i class="las la-record-vinyl"></i> {{ __('Start Recording') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="stopRecording" style="display:none;">
                            <i class="las la-stop"></i> {{ __('Stop') }}
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="voice_transcription" id="voice_transcription" class="form-control" rows="4" placeholder="{{ __('Transcription will appear here...') }}">{{ old('voice_transcription', $note->voice_transcription) }}</textarea>
                        <small class="text-muted">{{ __('Click Start Recording to use voice-to-text') }}</small>
                    </div>
                </div>
            </div>

            <!-- SOAP Note Sections -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('SOAP Note') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ __('Subjective (S)') }}</label>
                        <textarea name="subjective" id="subjective" class="form-control" rows="5" placeholder="{{ __('Patient-reported information, chief complaint, history...') }}">{{ old('subjective', $note->subjective) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Objective (O)') }}</label>
                        <textarea name="objective" id="objective" class="form-control" rows="5" placeholder="{{ __('Observations, measurements, physical examination findings...') }}">{{ old('objective', $note->objective) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Assessment (A)') }}</label>
                        <textarea name="assessment" id="assessment" class="form-control" rows="5" placeholder="{{ __('Clinical interpretation, diagnosis, progress...') }}">{{ old('assessment', $note->assessment) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Plan (P)') }}</label>
                        <textarea name="plan" id="plan" class="form-control" rows="5" placeholder="{{ __('Treatment plan, goals, next steps...') }}">{{ old('plan', $note->plan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Coding Section -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">{{ __('Coding & Billing') }}</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="validateCoding">
                        <i class="las la-check-circle"></i> {{ __('Validate Coding') }}
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('ICD-10 Diagnosis Codes') }}</label>
                                <div id="icd10_fields">
                                    @if($note->diagnosis_codes && count($note->diagnosis_codes) > 0)
                                        @foreach($note->diagnosis_codes as $code)
                                            <input type="text" name="diagnosis_codes[]" class="form-control mb-2" value="{{ $code }}" placeholder="M25.561">
                                        @endforeach
                                    @else
                                        <input type="text" name="diagnosis_codes[]" class="form-control mb-2" placeholder="M25.561" id="icd10_1">
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addICD10Field()">
                                    <i class="las la-plus"></i> {{ __('Add Code') }}
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('CPT Procedure Codes') }}</label>
                                <div id="cpt_fields">
                                    @if($note->procedure_codes && count($note->procedure_codes) > 0)
                                        @foreach($note->procedure_codes as $code)
                                            <input type="text" name="procedure_codes[]" class="form-control mb-2" value="{{ $code }}" placeholder="97110">
                                        @endforeach
                                    @else
                                        <input type="text" name="procedure_codes[]" class="form-control mb-2" placeholder="97110" id="cpt_1">
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCPTField()">
                                    <i class="las la-plus"></i> {{ __('Add Code') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="codingValidationResult" class="mt-3">
                        @if($note->coding_validated)
                            <div class="alert alert-success mb-0">
                                <i class="las la-check-circle"></i> {{ __('Coding validated') }}
                            </div>
                        @elseif($note->coding_errors)
                            <div class="alert alert-danger mb-0">
                                <i class="las la-exclamation-triangle"></i> {{ __('Coding errors') }}: {{ $note->coding_errors }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> {{ __('Update Note') }}
                    </button>
                    <a href="{{ route('clinic.clinical-notes.show', $note->id) }}" class="btn btn-outline-secondary">
                        <i class="las la-times"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
let recognition = null;
let isRecording = false;

// Initialize Web Speech API
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';

    recognition.onresult = function(event) {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            transcript += event.results[i][0].transcript;
        }
        const currentText = document.getElementById('voice_transcription').value;
        document.getElementById('voice_transcription').value = currentText + transcript + ' ';
    };

    recognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        alert('Speech recognition error: ' + event.error);
    };
} else {
    document.getElementById('startRecording').disabled = true;
    document.getElementById('startRecording').title = 'Speech recognition not supported in this browser';
}

document.getElementById('startRecording').addEventListener('click', function() {
    if (recognition && !isRecording) {
        recognition.start();
        isRecording = true;
        document.getElementById('startRecording').style.display = 'none';
        document.getElementById('stopRecording').style.display = 'inline-block';
    }
});

document.getElementById('stopRecording').addEventListener('click', function() {
    if (recognition && isRecording) {
        recognition.stop();
        isRecording = false;
        document.getElementById('startRecording').style.display = 'inline-block';
        document.getElementById('stopRecording').style.display = 'none';
    }
});

// Add ICD-10 field
let icd10Count = {{ ($note->diagnosis_codes ? count($note->diagnosis_codes) : 1) }};
function addICD10Field() {
    icd10Count++;
    const div = document.createElement('div');
    div.className = 'mb-2';
    div.innerHTML = `
        <input type="text" name="diagnosis_codes[]" class="form-control" placeholder="M25.561" id="icd10_${icd10Count}">
    `;
    document.getElementById('icd10_fields').appendChild(div);
}

// Add CPT field
let cptCount = {{ ($note->procedure_codes ? count($note->procedure_codes) : 1) }};
function addCPTField() {
    cptCount++;
    const div = document.createElement('div');
    div.className = 'mb-2';
    div.innerHTML = `
        <input type="text" name="procedure_codes[]" class="form-control" placeholder="97110" id="cpt_${cptCount}">
    `;
    document.getElementById('cpt_fields').appendChild(div);
}

// Validate coding
document.getElementById('validateCoding').addEventListener('click', function() {
    const diagnosisCodes = Array.from(document.querySelectorAll('input[name="diagnosis_codes[]"]'))
        .map(input => input.value)
        .filter(val => val.trim() !== '');
    const procedureCodes = Array.from(document.querySelectorAll('input[name="procedure_codes[]"]'))
        .map(input => input.value)
        .filter(val => val.trim() !== '');

    fetch('{{ route("clinic.clinical-notes.validateCoding") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            diagnosis_codes: diagnosisCodes,
            procedure_codes: procedureCodes
        })
    })
    .then(response => response.json())
    .then(data => {
        let html = '<div class="alert alert-info"><h6>Coding Validation Results:</h6><ul>';
        if (data.validation.icd10.valid) {
            html += '<li class="text-success">✓ ICD-10 codes are valid</li>';
        } else {
            html += '<li class="text-danger">✗ ICD-10 errors: ' + data.validation.icd10.errors.join(', ') + '</li>';
        }
        if (data.validation.cpt.valid) {
            html += '<li class="text-success">✓ CPT codes are valid</li>';
        } else {
            html += '<li class="text-danger">✗ CPT errors: ' + data.validation.cpt.errors.join(', ') + '</li>';
        }
        if (data.validation.ncci.violations.length > 0) {
            html += '<li class="text-warning">⚠ NCCI violations: ' + data.validation.ncci.violations.join(', ') + '</li>';
        }
        html += '</ul></div>';
        document.getElementById('codingValidationResult').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to validate coding');
    });
});
</script>
@endpush


@extends('web.layouts.dashboard_master')

@section('title', 'Create Clinical Note')
@section('header_title', 'Create Clinical Note')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('clinic.clinical-notes.store') }}" method="POST" id="clinicalNoteForm">
            @csrf
            
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Patient') }} <span class="text-danger">*</span></label>
                                <select name="patient_id" id="patient_id" class="form-control" required>
                                    <option value="">{{ __('Select Patient') }}</option>
                                    @foreach($patients ?? [] as $p)
                                        <option value="{{ $p->id }}" {{ ($patient && $patient->id == $p->id) ? 'selected' : '' }}>
                                            {{ $p->first_name }} {{ $p->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Specialty') }} <span class="text-danger">*</span></label>
                                <select name="specialty" id="specialty" class="form-control" required>
                                    @foreach(\App\Models\ClinicalNote::SPECIALTIES as $key => $label)
                                        <option value="{{ $key }}" {{ $specialty == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Note Type') }} <span class="text-danger">*</span></label>
                                <select name="note_type" id="note_type" class="form-control" required>
                                    @foreach(\App\Models\ClinicalNote::NOTE_TYPES as $key => $label)
                                        <option value="{{ $key }}" {{ $noteType == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ __('Episode') }}</label>
                                <select name="episode_id" id="episode_id" class="form-control">
                                    <option value="">{{ __('None') }}</option>
                                    @foreach($episodes ?? [] as $ep)
                                        <option value="{{ $ep->id }}" {{ ($episode && $episode->id == $ep->id) ? 'selected' : '' }}>
                                            Episode #{{ $ep->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    @if($appointment)
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                    <div class="alert alert-info">
                        <i class="las la-calendar"></i> {{ __('Linked to appointment on') }} {{ $appointment->appointment_date->format('M d, Y H:i') }}
                    </div>
                    @endif
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
                        <textarea name="voice_transcription" id="voice_transcription" class="form-control" rows="4" placeholder="{{ __('Transcription will appear here...') }}"></textarea>
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
                        <textarea name="subjective" id="subjective" class="form-control" rows="5" placeholder="{{ __('Patient-reported information, chief complaint, history...') }}">{{ old('subjective') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Objective (O)') }}</label>
                        <textarea name="objective" id="objective" class="form-control" rows="5" placeholder="{{ __('Observations, measurements, physical examination findings...') }}">{{ old('objective') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Assessment (A)') }}</label>
                        <textarea name="assessment" id="assessment" class="form-control" rows="5" placeholder="{{ __('Clinical interpretation, diagnosis, progress...') }}">{{ old('assessment') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Plan (P)') }}</label>
                        <textarea name="plan" id="plan" class="form-control" rows="5" placeholder="{{ __('Treatment plan, goals, next steps...') }}">{{ old('plan') }}</textarea>
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
                                <input type="text" name="diagnosis_codes[]" class="form-control mb-2" placeholder="M25.561" id="icd10_1">
                                <div id="icd10_fields"></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addICD10Field()">
                                    <i class="las la-plus"></i> {{ __('Add Code') }}
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('CPT Procedure Codes') }}</label>
                                <input type="text" name="procedure_codes[]" class="form-control mb-2" placeholder="97110" id="cpt_1">
                                <div id="cpt_fields"></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCPTField()">
                                    <i class="las la-plus"></i> {{ __('Add Code') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="codingValidationResult" class="mt-3"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> {{ __('Save as Draft') }}
                    </button>
                    <a href="{{ route('clinic.clinical-notes.index') }}" class="btn btn-outline-secondary">
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
        document.getElementById('voice_transcription').value += transcript + ' ';
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
let icd10Count = 1;
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
let cptCount = 1;
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


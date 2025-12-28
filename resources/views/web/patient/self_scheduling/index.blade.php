@extends('web.layouts.dashboard_master')

@section('title', 'Schedule Appointment')
@section('header_title', 'Schedule Your Appointment')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('patient.self-schedule.store') }}" method="POST" id="schedulingForm">
            @csrf
            
            <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
            
            <!-- Clinic Info -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Clinic Information') }}</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $clinic->name }}</h6>
                    <p class="text-muted mb-0">{{ $clinic->address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Appointment Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Doctor/Therapist') }} <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-control" required>
                                    <option value="">{{ __('Select Doctor') }}</option>
                                    @foreach($doctors ?? [] as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Visit Type') }} <span class="text-danger">*</span></label>
                                <select name="visit_type" class="form-control" required>
                                    <option value="evaluation" {{ old('visit_type') == 'evaluation' ? 'selected' : '' }}>{{ __('Initial Evaluation') }}</option>
                                    <option value="followup" {{ old('visit_type') == 'followup' ? 'selected' : '' }}>{{ __('Follow-up') }}</option>
                                    <option value="re_evaluation" {{ old('visit_type') == 're_evaluation' ? 'selected' : '' }}>{{ __('Re-evaluation') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" id="appointment_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                                @error('appointment_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Time') }} <span class="text-danger">*</span></label>
                                <select name="appointment_time" id="appointment_time" class="form-control" required>
                                    <option value="">{{ __('Select Time') }}</option>
                                </select>
                                @error('appointment_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Duration (minutes)') }}</label>
                                <select name="duration_minutes" class="form-control">
                                    <option value="30">30 {{ __('minutes') }}</option>
                                    <option value="45" selected>45 {{ __('minutes') }}</option>
                                    <option value="60">60 {{ __('minutes') }}</option>
                                    <option value="90">90 {{ __('minutes') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intake Forms -->
            @if(isset($intakeForms) && $intakeForms->count() > 0)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Pre-Visit Questionnaire') }}</h5>
                </div>
                <div class="card-body">
                    @foreach($intakeForms as $form)
                    <div class="intake-form-section mb-4" data-form-id="{{ $form->id }}">
                        <h6>{{ $form->name }}</h6>
                        @if($form->description)
                            <p class="text-muted small">{{ $form->description }}</p>
                        @endif
                        
                        <input type="hidden" name="intake_form_id" value="{{ $form->id }}">
                        
                        @foreach($form->form_fields as $fieldKey => $field)
                        <div class="form-group">
                            <label>{{ $field['label'] ?? $fieldKey }} @if(isset($field['required']) && $field['required'])<span class="text-danger">*</span>@endif</label>
                            
                            @if($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'tel')
                                <input type="{{ $field['type'] }}" name="intake_responses[{{ $fieldKey }}]" class="form-control" 
                                       value="{{ old("intake_responses.$fieldKey") }}" 
                                       @if(isset($field['required']) && $field['required']) required @endif>
                            @elseif($field['type'] === 'textarea')
                                <textarea name="intake_responses[{{ $fieldKey }}]" class="form-control" rows="3"
                                          @if(isset($field['required']) && $field['required']) required @endif>{{ old("intake_responses.$fieldKey") }}</textarea>
                            @elseif($field['type'] === 'select')
                                <select name="intake_responses[{{ $fieldKey }}]" class="form-control"
                                        @if(isset($field['required']) && $field['required']) required @endif>
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option value="{{ $option }}" {{ old("intake_responses.$fieldKey") == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif($field['type'] === 'checkbox')
                                <div>
                                    @foreach($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="intake_responses[{{ $fieldKey }}][]" 
                                               value="{{ $option }}" id="check_{{ $fieldKey }}_{{ $loop->index }}">
                                        <label class="form-check-label" for="check_{{ $fieldKey }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            @elseif($field['type'] === 'radio')
                                <div>
                                    @foreach($field['options'] ?? [] as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="intake_responses[{{ $fieldKey }}]" 
                                               value="{{ $option }}" id="radio_{{ $fieldKey }}_{{ $loop->index }}"
                                               @if(isset($field['required']) && $field['required']) required @endif>
                                        <label class="form-check-label" for="radio_{{ $fieldKey }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Submit -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="las la-calendar-check"></i> {{ __('Schedule Appointment') }}
                    </button>
                    <a href="{{ route('patient.self-schedule.index') }}" class="btn btn-outline-secondary">
                        <i class="las la-times"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('doctor_id').addEventListener('change', function() {
    loadAvailableSlots();
});

document.getElementById('appointment_date').addEventListener('change', function() {
    loadAvailableSlots();
});

function loadAvailableSlots() {
    const doctorId = document.getElementById('doctor_id').value;
    const date = document.getElementById('appointment_date').value;
    const timeSelect = document.getElementById('appointment_time');
    
    if (!doctorId || !date) {
        timeSelect.innerHTML = '<option value="">{{ __('Select Doctor and Date first') }}</option>';
        return;
    }
    
    timeSelect.innerHTML = '<option value="">{{ __('Loading...') }}</option>';
    
    fetch(`{{ route('patient.self-schedule.slots') }}?clinic_id={{ $clinic->id }}&doctor_id=${doctorId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            timeSelect.innerHTML = '<option value="">{{ __('Select Time') }}</option>';
            if (data.success && data.slots.length > 0) {
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot;
                    option.textContent = slot;
                    timeSelect.appendChild(option);
                });
            } else {
                timeSelect.innerHTML = '<option value="">{{ __('No available slots') }}</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            timeSelect.innerHTML = '<option value="">{{ __('Error loading slots') }}</option>';
        });
}
</script>
@endpush


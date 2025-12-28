@extends('web.layouts.dashboard_master')

@section('title', 'Create Intake Form')
@section('header_title', 'Create Intake Form')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-12">
        <form action="{{ route('clinic.intake-forms.store') }}" method="POST" id="intakeFormBuilder">
            @csrf
            
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Basic Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ __('Form Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="{{ __('e.g., Pre-Visit Questionnaire') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Describe the purpose of this form...') }}">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_required" value="1" id="is_required" {{ old('is_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">
                            {{ __('Required before appointment') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Builder -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">{{ __('Form Fields') }}</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addField()">
                        <i class="las la-plus"></i> {{ __('Add Field') }}
                    </button>
                </div>
                <div class="card-body">
                    <div id="formFieldsContainer">
                        <!-- Fields will be added here dynamically -->
                    </div>
                    <input type="hidden" name="form_fields" id="form_fields_json" value="{{ old('form_fields', '[]') }}">
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> {{ __('Create Form') }}
                    </button>
                    <a href="{{ route('clinic.intake-forms.index') }}" class="btn btn-outline-secondary">
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
let fieldCount = 0;
let formFields = [];

function addField() {
    fieldCount++;
    const fieldId = 'field_' + fieldCount;
    
    const fieldHtml = `
        <div class="card mb-3 border" id="${fieldId}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ __('Field') }} ${fieldCount}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField('${fieldId}')">
                        <i class="las la-times"></i>
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Field Key') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control field-key" placeholder="e.g., chief_complaint" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Field Label') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control field-label" placeholder="e.g., Chief Complaint" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('Field Type') }} <span class="text-danger">*</span></label>
                            <select class="form-control field-type" onchange="updateFieldOptions(this)" required>
                                <option value="text">{{ __('Text') }}</option>
                                <option value="textarea">{{ __('Textarea') }}</option>
                                <option value="email">{{ __('Email') }}</option>
                                <option value="tel">{{ __('Phone') }}</option>
                                <option value="select">{{ __('Select Dropdown') }}</option>
                                <option value="radio">{{ __('Radio Buttons') }}</option>
                                <option value="checkbox">{{ __('Checkboxes') }}</option>
                                <option value="date">{{ __('Date') }}</option>
                                <option value="number">{{ __('Number') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group field-options-container" style="display:none;">
                    <label>{{ __('Options') }} ({{ __('one per line') }})</label>
                    <textarea class="form-control field-options" rows="3" placeholder="{{ __('Option 1\nOption 2\nOption 3') }}"></textarea>
                    <small class="text-muted">{{ __('Enter options one per line (for select, radio, checkbox)') }}</small>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input field-required" type="checkbox" id="required_${fieldId}">
                    <label class="form-check-label" for="required_${fieldId}">
                        {{ __('Required field') }}
                    </label>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('formFieldsContainer').insertAdjacentHTML('beforeend', fieldHtml);
}

function removeField(fieldId) {
    document.getElementById(fieldId).remove();
    updateFormFields();
}

function updateFieldOptions(select) {
    const container = select.closest('.card-body').querySelector('.field-options-container');
    const fieldType = select.value;
    
    if (['select', 'radio', 'checkbox'].includes(fieldType)) {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

function updateFormFields() {
    formFields = [];
    const fieldCards = document.querySelectorAll('#formFieldsContainer .card');
    
    fieldCards.forEach(card => {
        const key = card.querySelector('.field-key')?.value;
        const label = card.querySelector('.field-label')?.value;
        const type = card.querySelector('.field-type')?.value;
        const required = card.querySelector('.field-required')?.checked;
        const optionsText = card.querySelector('.field-options')?.value || '';
        
        if (key && label && type) {
            const field = {
                label: label,
                type: type,
                required: required
            };
            
            if (['select', 'radio', 'checkbox'].includes(type) && optionsText) {
                field.options = optionsText.split('\n').filter(opt => opt.trim() !== '').map(opt => opt.trim());
            }
            
            formFields[key] = field;
        }
    });
    
    document.getElementById('form_fields_json').value = JSON.stringify(formFields);
}

// Update form fields before submit
document.getElementById('intakeFormBuilder').addEventListener('submit', function(e) {
    updateFormFields();
    
    if (Object.keys(formFields).length === 0) {
        e.preventDefault();
        alert('{{ __('Please add at least one field to the form.') }}');
        return false;
    }
});

// Auto-update on field changes
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('field-key') || 
        e.target.classList.contains('field-label') || 
        e.target.classList.contains('field-options') ||
        e.target.classList.contains('field-required')) {
        updateFormFields();
    }
});

// Initialize with one field
addField();
</script>
@endpush


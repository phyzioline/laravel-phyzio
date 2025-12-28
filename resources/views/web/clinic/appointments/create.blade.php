@extends('web.layouts.dashboard_master')

@section('title', 'Create Appointment')
@section('header_title', 'Schedule New Appointment')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Appointment Details') }}</h5>
            </div>
            <div class="card-body px-4">
                <form id="appointmentForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Patient') }} <span class="text-danger">*</span></label>
                                <select name="patient_id" class="form-control" required>
                                    <option value="">{{ __('Select Patient') }}</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Therapist') }}</label>
                                <select name="doctor_id" class="form-control">
                                    <option value="">{{ __('Select Therapist') }}</option>
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" class="form-control" 
                                       value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Time') }} <span class="text-danger">*</span></label>
                                <input type="time" name="appointment_time" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Visit Type & Location -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Visit Type') }} <span class="text-danger">*</span></label>
                                <select name="visit_type" class="form-control" required>
                                    <option value="evaluation">{{ __('Evaluation') }}</option>
                                    <option value="followup" selected>{{ __('Follow-up') }}</option>
                                    <option value="re_evaluation">{{ __('Re-evaluation') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Location') }} <span class="text-danger">*</span></label>
                                <select name="location" id="location" class="form-control" required>
                                    <option value="clinic" selected>{{ __('Clinic') }}</option>
                                    <option value="home">{{ __('Home Care') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Duration (minutes)') }}</label>
                                <select name="duration_minutes" class="form-control">
                                    <option value="30">30 min</option>
                                    <option value="45">45 min</option>
                                    <option value="60" selected>60 min</option>
                                    <option value="90">90 min</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Specialty Selection -->
                    <div class="form-group">
                        <label>{{ __('Specialty') }} <span class="text-danger">*</span></label>
                        <select name="specialty" id="specialty" class="form-control" required>
                            <option value="">{{ __('Select Specialty') }}</option>
                            @if($clinic && $clinic->primary_specialty)
                                <option value="{{ $clinic->primary_specialty }}" selected>
                                    {{ $clinic->getPrimarySpecialtyDisplayName() }}
                                </option>
                            @endif
                            @foreach(['orthopedic', 'pediatric', 'neurological', 'sports', 'geriatric', 'womens_health', 'cardiorespiratory', 'home_care'] as $spec)
                                @if(!$clinic || $clinic->primary_specialty != $spec)
                                    <option value="{{ $spec }}">
                                        {{ ucfirst(str_replace('_', ' ', $spec)) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Specialty-Specific Fields (Dynamic) -->
                    <div id="specialtyFieldsContainer">
                        <!-- Fields will be loaded dynamically here -->
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label>{{ __('Payment Method') }}</label>
                        <select name="payment_method" class="form-control">
                            <option value="">{{ __('Select Payment Method') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="card">{{ __('Card') }}</option>
                            <option value="insurance">{{ __('Insurance') }}</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label>{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Additional notes about this appointment..."></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="las la-calendar-check"></i> {{ __('Schedule Appointment') }}
                        </button>
                        <a href="{{ route('clinic.appointments.index') }}" class="btn btn-secondary btn-lg">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Price Preview -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Price Preview') }}</h5>
            </div>
            <div class="card-body px-4" id="pricePreview">
                <div class="text-center py-4">
                    <i class="las la-calculator fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('Fill in appointment details to see pricing') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointmentForm');
    const specialtySelect = document.getElementById('specialty');
    const specialtyFieldsContainer = document.getElementById('specialtyFieldsContainer');
    const pricePreview = document.getElementById('pricePreview');
    
    // Load specialty fields when specialty changes
    specialtySelect.addEventListener('change', function() {
        const specialty = this.value;
        if (specialty) {
            loadSpecialtyFields(specialty);
            calculatePrice();
        } else {
            specialtyFieldsContainer.innerHTML = '';
            pricePreview.innerHTML = '<div class="text-center py-4"><i class="las la-calculator fa-3x text-muted mb-3"></i><p class="text-muted">Fill in appointment details to see pricing</p></div>';
        }
    });
    
    // Calculate price on field changes
    const priceFields = ['specialty', 'visit_type', 'location', 'duration_minutes'];
    priceFields.forEach(field => {
        const element = form.querySelector(`[name="${field}"]`);
        if (element) {
            element.addEventListener('change', calculatePrice);
        }
    });
    
    function loadSpecialtyFields(specialty) {
        fetch(`{{ route('clinic.appointments.specialtyFields') }}?specialty=${specialty}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fields) {
                renderSpecialtyFields(data.fields);
            }
        })
        .catch(error => {
            console.error('Error loading specialty fields:', error);
        });
    }
    
    function renderSpecialtyFields(fields) {
        let html = '<div class="border-top pt-4 mt-4"><h6 class="mb-3">Specialty-Specific Information</h6>';
        
        for (const [fieldName, fieldConfig] of Object.entries(fields)) {
            html += renderField(fieldName, fieldConfig);
        }
        
        html += '</div>';
        specialtyFieldsContainer.innerHTML = html;
    }
    
    function renderField(fieldName, config) {
        let html = '<div class="form-group">';
        html += `<label>${config.label}${config.required ? ' <span class="text-danger">*</span>' : ''}</label>`;
        
        switch (config.type) {
            case 'select':
                html += `<select name="${fieldName}" class="form-control" ${config.required ? 'required' : ''}>`;
                html += '<option value="">Select...</option>';
                for (const [value, label] of Object.entries(config.options)) {
                    html += `<option value="${value}">${label}</option>`;
                }
                html += '</select>';
                break;
                
            case 'checkbox':
                html += '<div class="row">';
                for (const [value, label] of Object.entries(config.options)) {
                    html += `<div class="col-md-6 mb-2">`;
                    html += `<div class="custom-control custom-checkbox">`;
                    html += `<input type="checkbox" name="${fieldName}[]" value="${value}" class="custom-control-input" id="${fieldName}_${value}">`;
                    html += `<label class="custom-control-label" for="${fieldName}_${value}">${label}</label>`;
                    html += `</div></div>`;
                }
                html += '</div>';
                break;
                
            case 'number':
                html += `<input type="number" name="${fieldName}" class="form-control" 
                         ${config.min !== undefined ? `min="${config.min}"` : ''} 
                         ${config.max !== undefined ? `max="${config.max}"` : ''} 
                         ${config.required ? 'required' : ''}
                         ${config.placeholder ? `placeholder="${config.placeholder}"` : ''}>`;
                if (config.help) {
                    html += `<small class="form-text text-muted">${config.help}</small>`;
                }
                break;
                
            case 'textarea':
                html += `<textarea name="${fieldName}" class="form-control" rows="3" 
                         ${config.required ? 'required' : ''}
                         ${config.placeholder ? `placeholder="${config.placeholder}"` : ''}></textarea>`;
                break;
                
            default: // text
                html += `<input type="text" name="${fieldName}" class="form-control" 
                         ${config.required ? 'required' : ''}
                         ${config.placeholder ? `placeholder="${config.placeholder}"` : ''}>`;
        }
        
        html += '</div>';
        return html;
    }
    
    function calculatePrice() {
        const specialty = specialtySelect.value;
        const visitType = form.querySelector('[name="visit_type"]').value;
        const location = form.querySelector('[name="location"]').value;
        const durationMinutes = form.querySelector('[name="duration_minutes"]').value;
        
        if (!specialty || !visitType || !location) {
            return;
        }
        
        // Get equipment from specialty fields
        const equipment = [];
        const equipmentCheckboxes = form.querySelectorAll('input[name="equipment[]"]:checked');
        equipmentCheckboxes.forEach(cb => equipment.push(cb.value));
        
        fetch('{{ route("clinic.appointments.calculatePrice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                specialty: specialty,
                visit_type: visitType,
                location: location,
                duration_minutes: durationMinutes,
                equipment: equipment
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.pricing) {
                const pricing = data.pricing;
                pricePreview.innerHTML = `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Base Price') }}</small>
                        <h6 class="mb-0">$${pricing.base_price.toFixed(2)}</h6>
                    </div>
                    ${pricing.equipment_total > 0 ? `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Equipment') }}</small>
                        <h6 class="mb-0">+$${pricing.equipment_total.toFixed(2)}</h6>
                    </div>
                    ` : ''}
                    ${pricing.discount_amount > 0 ? `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Discount') }}</small>
                        <h6 class="mb-0 text-success">-$${pricing.discount_amount.toFixed(2)}</h6>
                    </div>
                    ` : ''}
                    <hr>
                    <div class="mb-0">
                        <small class="text-muted d-block mb-2">{{ __('Total Price') }}</small>
                        <h3 class="mb-0 text-primary">$${pricing.final_price.toFixed(2)}</h3>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error calculating price:', error);
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('{{ route("clinic.appointments.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': formData.get('_token')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'An error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("clinic.appointments.index") }}';
                }
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Network error. Please try again.');
        });
    });
    
    // Load fields if specialty is pre-selected
    if (specialtySelect.value) {
        loadSpecialtyFields(specialtySelect.value);
    }
});
</script>
@endsection


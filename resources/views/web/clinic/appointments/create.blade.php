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

                    <!-- Booking Type Selection -->
                    <div class="form-group">
                        <label>{{ __('Booking Type') }} <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="booking_type_regular" name="booking_type" value="regular" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="booking_type_regular">
                                        <strong>{{ __('Regular Session') }}</strong>
                                        <small class="d-block text-muted">{{ __('Standard appointment with one doctor') }}</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="booking_type_intensive" name="booking_type" value="intensive" class="custom-control-input">
                                    <label class="custom-control-label" for="booking_type_intensive">
                                        <strong>{{ __('Intensive Session') }}</strong>
                                        <small class="d-block text-muted">{{ __('Children\'s Intensive Treatment (1-4 hours)') }}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intensive Session Configuration (Hidden by default) -->
                    <div id="intensiveSessionConfig" style="display: none;" class="border rounded p-3 mb-3 bg-light">
                        <h6 class="mb-3">{{ __('Intensive Session Configuration') }}</h6>
                        <div class="form-group">
                            <label>{{ __('Session Duration') }} <span class="text-danger">*</span></label>
                            <select name="total_hours" id="total_hours" class="form-control">
                                <option value="1">1 {{ __('hour') }}</option>
                                <option value="2">2 {{ __('hours') }}</option>
                                <option value="3">3 {{ __('hours') }}</option>
                                <option value="4">4 {{ __('hours') }}</option>
                            </select>
                            <small class="form-text text-muted">
                                {{ __('The session will be automatically divided into hourly slots. Doctors will be assigned per slot.') }}
                            </small>
                        </div>
                    </div>

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
                        <div class="col-md-6" id="regularTherapistField">
                            <div class="form-group">
                                <label>{{ __('Therapist') }}</label>
                                <select name="doctor_id" class="form-control">
                                    <option value="">{{ __('Select Therapist') }}</option>
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">{{ __('For intensive sessions, doctors will be assigned per slot') }}</small>
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

                    <!-- Services Selection -->
                    <div class="form-group" id="servicesContainer" style="display: none;">
                        <label>{{ __('Additional Services') }}</label>
                        <p class="text-muted small mb-2">{{ __('Select services to include (prices will be added to total)') }}</p>
                        <div id="servicesList" class="row">
                            <!-- Services will be loaded dynamically here -->
                        </div>
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
    
    // Booking type toggle
    const bookingTypeRadios = document.querySelectorAll('input[name="booking_type"]');
    const intensiveConfig = document.getElementById('intensiveSessionConfig');
    const regularTherapistField = document.getElementById('regularTherapistField');
    
    bookingTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'intensive') {
                intensiveConfig.style.display = 'block';
                regularTherapistField.querySelector('select[name="doctor_id"]').required = false;
                regularTherapistField.querySelector('small').textContent = '{{ __("For intensive sessions, doctors will be assigned per slot") }}';
            } else {
                intensiveConfig.style.display = 'none';
                regularTherapistField.querySelector('select[name="doctor_id"]').required = false;
                regularTherapistField.querySelector('small').textContent = '';
            }
        });
    });
    
    // Load specialty fields when specialty changes
    specialtySelect.addEventListener('change', function() {
        const specialty = this.value;
        if (specialty) {
            loadSpecialtyFields(specialty);
            loadAvailableServices(specialty);
            calculatePrice();
        } else {
            specialtyFieldsContainer.innerHTML = '';
            document.getElementById('servicesContainer').style.display = 'none';
            pricePreview.innerHTML = '<div class="text-center py-4"><i class="las la-calculator fa-3x text-muted mb-3"></i><p class="text-muted">Fill in appointment details to see pricing</p></div>';
        }
    });
    
    // Load available services for specialty
    function loadAvailableServices(specialty) {
        fetch(`{{ route('clinic.appointments.availableServices') }}?specialty=${specialty}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.services && data.services.length > 0) {
                const container = document.getElementById('servicesContainer');
                const list = document.getElementById('servicesList');
                list.innerHTML = '';
                
                data.services.forEach(service => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-2';
                    col.innerHTML = `
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" 
                                   id="service_${service.key}" 
                                   name="equipment[]" 
                                   value="${service.key}"
                                   onchange="calculatePrice()">
                            <label class="custom-control-label" for="service_${service.key}">
                                ${service.name} <strong class="text-primary">($${parseFloat(service.price).toFixed(2)})</strong>
                            </label>
                        </div>
                    `;
                    list.appendChild(col);
                });
                
                container.style.display = 'block';
            } else {
                document.getElementById('servicesContainer').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading services:', error);
        });
    }
    
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
                let servicesHtml = '';
                
                // Show individual services if available
                if (pricing.service_details && pricing.service_details.length > 0) {
                    servicesHtml = '<div class="mb-3"><small class="text-muted d-block mb-2">{{ __("Services") }}:</small>';
                    pricing.service_details.forEach(service => {
                        servicesHtml += `<div class="d-flex justify-content-between mb-1">
                            <small>${service.name}</small>
                            <small class="text-muted">+$${parseFloat(service.price).toFixed(2)}</small>
                        </div>`;
                    });
                    servicesHtml += `<div class="mt-2 pt-2 border-top">
                        <small class="text-muted">{{ __("Services Total") }}</small>
                        <h6 class="mb-0">+$${pricing.equipment_total.toFixed(2)}</h6>
                    </div></div>`;
                } else if (pricing.equipment_total > 0) {
                    servicesHtml = `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Services') }}</small>
                        <h6 class="mb-0">+$${pricing.equipment_total.toFixed(2)}</h6>
                    </div>`;
                }
                
                pricePreview.innerHTML = `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Base Price') }}</small>
                        <h6 class="mb-0">$${pricing.base_price.toFixed(2)}</h6>
                    </div>
                    ${servicesHtml}
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
        loadAvailableServices(specialtySelect.value);
    }
});
</script>
@endsection


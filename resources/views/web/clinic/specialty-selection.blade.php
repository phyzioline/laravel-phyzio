@extends('web.layouts.dashboard_master')

@section('title', 'Select Your Physical Therapy Specialty')
@section('header_title', 'Welcome to Phyzioline Clinic Management')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2 class="mb-0">
                    <i class="las la-stethoscope"></i> Select Your Physical Therapy Specialty
                </h2>
                <p class="mb-0 mt-2">Choose your clinic's primary specialty to activate the right tools and workflows</p>
            </div>
            <div class="card-body p-5">
                <form id="specialtySelectionForm">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i> 
                        <strong>Note:</strong> This selection will customize your dashboard, assessment forms, and treatment templates. 
                        You can add more specialties later from your clinic settings.
                    </div>

                    <div class="row mt-4">
                        @foreach($availableSpecialties as $key => $name)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="specialty-card" data-specialty="{{ $key }}">
                                <div class="card h-100 border specialty-option" style="cursor: pointer; transition: all 0.3s;">
                                    <div class="card-body text-center p-4">
                                        <div class="specialty-icon mb-3">
                                            @if($key === 'orthopedic')
                                                <i class="las la-bone fa-3x text-primary"></i>
                                            @elseif($key === 'pediatric')
                                                <i class="las la-baby fa-3x text-info"></i>
                                            @elseif($key === 'neurological')
                                                <i class="las la-brain fa-3x text-purple"></i>
                                            @elseif($key === 'sports')
                                                <i class="las la-running fa-3x text-success"></i>
                                            @elseif($key === 'geriatric')
                                                <i class="las la-wheelchair fa-3x text-warning"></i>
                                            @elseif($key === 'womens_health')
                                                <i class="las la-female fa-3x text-pink"></i>
                                            @elseif($key === 'cardiorespiratory')
                                                <i class="las la-lungs fa-3x text-danger"></i>
                                            @elseif($key === 'home_care')
                                                <i class="las la-home fa-3x text-secondary"></i>
                                            @elseif($key === 'multi_specialty')
                                                <i class="las la-hospital fa-3x text-dark"></i>
                                            @else
                                                <i class="las la-clinic-medical fa-3x text-primary"></i>
                                            @endif
                                        </div>
                                        <h5 class="card-title mb-2">{{ $name }}</h5>
                                        <div class="form-check d-none">
                                            <input class="form-check-input specialty-checkbox" type="checkbox" 
                                                   name="specialties[]" value="{{ $key }}" id="specialty_{{ $key }}">
                                            <label class="form-check-label" for="specialty_{{ $key }}"></label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input primary-radio" type="radio" 
                                                   name="primary_specialty" value="{{ $key }}" id="primary_{{ $key }}">
                                            <label class="form-check-label small" for="primary_{{ $key }}">
                                                Set as Primary
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-warning d-none" id="selectionWarning">
                                <i class="las la-exclamation-triangle"></i> 
                                Please select at least one specialty and mark one as primary.
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="las la-check-circle"></i> Continue with Selected Specialty
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.specialty-card .card {
    border: 2px solid #e0e0e0;
}

.specialty-card .card:hover {
    border-color: #00897b;
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 137, 123, 0.15);
}

.specialty-card.selected .card {
    border-color: #00897b;
    background-color: #f0f9f8;
}

.specialty-card .specialty-checkbox:checked ~ .card,
.specialty-card input[type="checkbox"]:checked ~ .card {
    border-color: #00897b;
    background-color: #f0f9f8;
}

.text-purple {
    color: #9c27b0 !important;
}

.text-pink {
    color: #e91e63 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('specialtySelectionForm');
    const checkboxes = document.querySelectorAll('.specialty-checkbox');
    const radios = document.querySelectorAll('.primary-radio');
    const specialtyCards = document.querySelectorAll('.specialty-card');
    const submitBtn = document.getElementById('submitBtn');
    const warning = document.getElementById('selectionWarning');

    // Handle card click
    specialtyCards.forEach(card => {
        const checkbox = card.querySelector('.specialty-checkbox');
        const radio = card.querySelector('.primary-radio');
        
        card.addEventListener('click', function(e) {
            // Don't toggle if clicking on radio button
            if (e.target.type === 'radio') {
                return;
            }
            
            // Toggle checkbox
            checkbox.checked = !checkbox.checked;
            
            // If checking, also set as primary if no primary is selected
            if (checkbox.checked && !document.querySelector('.primary-radio:checked')) {
                radio.checked = true;
            }
            
            // If unchecking and it was primary, uncheck primary
            if (!checkbox.checked && radio.checked) {
                radio.checked = false;
            }
            
            updateCardStyles();
            validateForm();
        });
    });

    // Handle checkbox change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCardStyles();
            validateForm();
        });
    });

    // Handle radio change
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Ensure the corresponding checkbox is checked
            const card = radio.closest('.specialty-card');
            const checkbox = card.querySelector('.specialty-checkbox');
            if (radio.checked && !checkbox.checked) {
                checkbox.checked = true;
            }
            validateForm();
        });
    });

    function updateCardStyles() {
        specialtyCards.forEach(card => {
            const checkbox = card.querySelector('.specialty-checkbox');
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    function validateForm() {
        const checkedBoxes = document.querySelectorAll('.specialty-checkbox:checked');
        const checkedRadio = document.querySelector('.primary-radio:checked');
        
        if (checkedBoxes.length === 0 || !checkedRadio) {
            warning.classList.remove('d-none');
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled');
            return false;
        } else {
            warning.classList.add('d-none');
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled');
            return true;
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Processing...';

        const formData = new FormData(form);
        
        fetch('{{ route("clinic.specialty-selection.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="las la-check-circle"></i> ${data.message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                form.insertBefore(alert, form.firstChild);
                
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = data.redirect || '{{ route("clinic.dashboard") }}';
                }, 1500);
            } else {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="las la-exclamation-circle"></i> ${data.message || 'An error occurred. Please try again.'}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                form.insertBefore(alert, form.firstChild);
                
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="las la-check-circle"></i> Continue with Selected Specialty';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                <i class="las la-exclamation-circle"></i> Network error. Please check your connection and try again.
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            `;
            form.insertBefore(alert, form.firstChild);
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="las la-check-circle"></i> Continue with Selected Specialty';
        });
    });

    // Initial validation
    validateForm();
});
</script>
@endsection


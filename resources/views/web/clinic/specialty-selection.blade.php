@extends('web.layouts.dashboard_master')

@section('title', 'Select Your Physical Therapy Specialty')
@section('header_title', 'Welcome to Phyzioline Clinic Management')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="card-header text-white text-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="mb-3">
                    <i class="fas fa-stethoscope fa-3x" style="opacity: 0.9;"></i>
                </div>
                <h2 class="mb-2 font-weight-bold">Select Your Physical Therapy Specialty</h2>
                <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">Choose your clinic's primary specialty to activate specialized tools and workflows</p>
            </div>
            <div class="card-body p-5" style="background: linear-gradient(to bottom, #f8f9fa, #ffffff);">
                <form id="specialtySelectionForm">
                    @csrf
                    
                    <div class="alert alert-info border-0 shadow-sm" style="border-radius: 15px; border-left: 4px solid #00897b;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> This selection will customize your dashboard, assessment forms, treatment templates, and pricing. 
                        You can add more specialties later from your clinic settings.
                    </div>

                    <div class="row mt-4">
                        @php
                            $specialtyFeatures = [
                                'orthopedic' => [
                                    'icon' => 'fas fa-bone',
                                    'color' => '#4a90e2',
                                    'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                    'features' => ['Musculoskeletal Conditions', 'Post-Op Rehabilitation', 'Pain Management', 'ROM & Strength Training'],
                                    'sessions' => '2-3 sessions/week',
                                    'duration' => '60 min sessions'
                                ],
                                'pediatric' => [
                                    'icon' => 'fas fa-baby',
                                    'color' => '#17a2b8',
                                    'gradient' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                                    'features' => ['Developmental Milestones', 'Play-Based Therapy', 'Motor Skills', 'Parent Education'],
                                    'sessions' => '1-2 sessions/week',
                                    'duration' => '45 min sessions'
                                ],
                                'neurological' => [
                                    'icon' => 'fas fa-brain',
                                    'color' => '#9c27b0',
                                    'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                    'features' => ['Stroke Rehabilitation', 'SCI Recovery', 'Balance Training', 'Functional Independence'],
                                    'sessions' => '3-5 sessions/week',
                                    'duration' => '90 min sessions'
                                ],
                                'sports' => [
                                    'icon' => 'fas fa-running',
                                    'color' => '#28a745',
                                    'gradient' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                                    'features' => ['Return to Play', 'Performance Metrics', 'Injury Prevention', 'Sport-Specific Training'],
                                    'sessions' => '2-4 sessions/week',
                                    'duration' => '60 min sessions'
                                ],
                                'geriatric' => [
                                    'icon' => 'fas fa-wheelchair',
                                    'color' => '#ffc107',
                                    'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                    'features' => ['Fall Prevention', 'Mobility Enhancement', 'Balance Training', 'Safety Focus'],
                                    'sessions' => '1-2 sessions/week',
                                    'duration' => '45 min sessions'
                                ],
                                'womens_health' => [
                                    'icon' => 'fas fa-venus',
                                    'color' => '#e91e63',
                                    'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                    'features' => ['Pelvic Floor Therapy', 'Pregnancy Care', 'Postpartum Recovery', 'Core Stability'],
                                    'sessions' => '1-2 sessions/week',
                                    'duration' => '60 min sessions'
                                ],
                                'cardiorespiratory' => [
                                    'icon' => 'fas fa-lungs',
                                    'color' => '#dc3545',
                                    'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                                    'features' => ['Cardiac Rehab', 'Pulmonary Therapy', 'Vital Signs Monitoring', 'Exercise Tolerance'],
                                    'sessions' => '2-3 sessions/week',
                                    'duration' => '60 min sessions'
                                ],
                                'home_care' => [
                                    'icon' => 'fas fa-home',
                                    'color' => '#6c757d',
                                    'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                    'features' => ['Mobile Services', 'Home Environment Assessment', 'Family Training', 'Convenience'],
                                    'sessions' => '1-2 sessions/week',
                                    'duration' => '60 min sessions'
                                ]
                            ];
                        @endphp
                        @foreach($availableSpecialties as $key => $name)
                        @php
                            $features = $specialtyFeatures[$key] ?? [
                                'icon' => 'fas fa-clinic-medical',
                                'color' => '#00897b',
                                'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                'features' => ['General Therapy'],
                                'sessions' => '2-3 sessions/week',
                                'duration' => '60 min sessions'
                            ];
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="specialty-card-3d" data-specialty="{{ $key }}">
                                <div class="card h-100 border-0 specialty-option shadow-sm" 
                                     style="cursor: pointer; border-radius: 20px; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;">
                                    <div class="card-header border-0 text-white text-center py-4 specialty-header" 
                                         style="background: {{ $features['gradient'] }}; position: relative; overflow: hidden;">
                                        <div class="specialty-icon-wrapper">
                                            <div class="icon-circle" style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto; backdrop-filter: blur(10px);">
                                                <i class="{{ $features['icon'] }} fa-3x" style="color: white; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
                                            </div>
                                        </div>
                                        <div class="floating-shapes">
                                            <div class="shape shape-1"></div>
                                            <div class="shape shape-2"></div>
                                            <div class="shape shape-3"></div>
                                        </div>
                                    </div>
                                    <div class="card-body p-4" style="background: white;">
                                        <h5 class="card-title text-center mb-3 font-weight-bold" style="color: #2c3e50;">{{ $name }}</h5>
                                        
                                        <!-- Features List -->
                                        <ul class="list-unstyled mb-3 specialty-features">
                                            @foreach($features['features'] as $feature)
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success mr-2" style="font-size: 0.85rem;"></i>
                                                <small style="color: #5a6c7d;">{{ $feature }}</small>
                                            </li>
                                            @endforeach
                                        </ul>
                                        
                                        <!-- Session Info -->
                                        <div class="text-center mb-3 p-2" style="background: #f8f9fa; border-radius: 10px;">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar-alt mr-1"></i> {{ $features['sessions'] }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i> {{ $features['duration'] }}
                                            </small>
                                        </div>
                                        
                                        <!-- Hidden Form Fields -->
                                        <div class="form-check d-none">
                                            <input class="form-check-input specialty-checkbox" type="checkbox" 
                                                   name="specialties[]" value="{{ $key }}" id="specialty_{{ $key }}">
                                            <label class="form-check-label" for="specialty_{{ $key }}"></label>
                                        </div>
                                        
                                        <!-- Primary Selection -->
                                        <div class="form-check text-center">
                                            <input class="form-check-input primary-radio" type="radio" 
                                                   name="primary_specialty" value="{{ $key }}" id="primary_{{ $key }}">
                                            <label class="form-check-label small font-weight-bold" for="primary_{{ $key }}" style="color: #00897b; cursor: pointer;">
                                                <i class="fas fa-star mr-1"></i> Set as Primary
                                            </label>
                                        </div>
                                        
                                    </div>
                                    <!-- Selection Indicator -->
                                    <div class="selection-badge" style="display: none; position: absolute; top: 15px; right: 15px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4); z-index: 10; border: 3px solid white;">
                                        <i class="fas fa-check fa-lg"></i>
                                    </div>
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
/* 3D Card Effects */
.specialty-card-3d {
    perspective: 1000px;
    position: relative;
}

.specialty-card-3d .card {
    position: relative;
    transform-style: preserve-3d;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.specialty-card-3d:hover .card {
    transform: translateY(-10px) rotateX(2deg);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 0 20px rgba(0, 137, 123, 0.1);
}

.specialty-card-3d.selected .card {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 137, 123, 0.2), 0 0 15px rgba(0, 137, 123, 0.15);
    border: 2px solid #00897b !important;
}

.specialty-card-3d.selected .specialty-header {
    box-shadow: inset 0 -10px 20px rgba(0, 0, 0, 0.1);
}

/* Floating Shapes Animation */
.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 60px;
    height: 60px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 40px;
    height: 40px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 30px;
    height: 30px;
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) translateX(0) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-20px) translateX(10px) scale(1.1);
        opacity: 0.6;
    }
}

/* Icon Animation */
.specialty-icon-wrapper {
    position: relative;
    z-index: 2;
    animation: iconPulse 2s ease-in-out infinite;
}

@keyframes iconPulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.specialty-card-3d:hover .specialty-icon-wrapper {
    animation: iconBounce 0.6s ease;
}

@keyframes iconBounce {
    0%, 100% {
        transform: scale(1) translateY(0);
    }
    50% {
        transform: scale(1.1) translateY(-5px);
    }
}

/* Selection Badge */
.selection-badge {
    animation: badgePop 0.3s ease;
}

@keyframes badgePop {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

/* Features List Animation */
.specialty-features li {
    opacity: 0;
    transform: translateX(-10px);
    animation: fadeInLeft 0.5s ease forwards;
}

.specialty-features li:nth-child(1) { animation-delay: 0.1s; }
.specialty-features li:nth-child(2) { animation-delay: 0.2s; }
.specialty-features li:nth-child(3) { animation-delay: 0.3s; }
.specialty-features li:nth-child(4) { animation-delay: 0.4s; }

@keyframes fadeInLeft {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Card Hover Effects */
.specialty-card-3d .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 137, 123, 0.05) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.3s;
    border-radius: 20px;
    pointer-events: none;
}

.specialty-card-3d:hover .card::before {
    opacity: 1;
}

.specialty-card-3d.selected .card::before {
    opacity: 1;
}

/* Primary Radio Styling */
.primary-radio:checked + label {
    color: #00897b !important;
    font-weight: bold;
}

.primary-radio:checked + label i {
    animation: starSpin 0.5s ease;
}

@keyframes starSpin {
    0% {
        transform: rotate(0deg) scale(1);
    }
    50% {
        transform: rotate(180deg) scale(1.3);
    }
    100% {
        transform: rotate(360deg) scale(1);
    }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .specialty-card-3d:hover .card {
        transform: translateY(-5px);
    }
    
    .icon-circle {
        width: 60px !important;
        height: 60px !important;
    }
    
    .icon-circle i {
        font-size: 2rem !important;
    }
}

/* Glassmorphism Effect */
.icon-circle {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

/* Gradient Text Effect */
.specialty-header h5 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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
            const badge = card.querySelector('.selection-badge');
            if (checkbox.checked) {
                card.classList.add('selected');
                if (badge) badge.style.display = 'flex';
            } else {
                card.classList.remove('selected');
                if (badge) badge.style.display = 'none';
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


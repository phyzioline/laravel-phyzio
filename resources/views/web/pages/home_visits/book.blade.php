@extends('web.layouts.app')

@section('content')
<main>
    <!-- Hero Section with Therapist Info -->
    <section class="py-5" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); padding-top: 140px !important; padding-bottom: 60px !important;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="text-white text-center mb-4">
                        <h1 class="font-weight-bold mb-3 display-4">{{ __('Book Your Home Visit') }}</h1>
                        <p class="lead opacity-90">{{ __('Schedule your appointment with our certified therapist') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="py-5 bg-light" style="padding-top: 40px !important;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Therapist Summary Card -->
                    <div class="card border-0 shadow-lg mb-4" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-body p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                            <div class="d-flex align-items-center flex-wrap">
                                @php
                                    $imageUrl = ($therapist->user && $therapist->user->profile_photo_url) 
                                        ? $therapist->user->profile_photo_url
                                        : ($therapist->profile_photo 
                                            ? (str_starts_with($therapist->profile_photo, 'storage/') 
                                                ? asset($therapist->profile_photo) 
                                                : asset('storage/' . $therapist->profile_photo))
                                            : ($therapist->profile_image 
                                                ? (str_starts_with($therapist->profile_image, 'storage/') 
                                                    ? asset($therapist->profile_image) 
                                                    : asset('storage/' . $therapist->profile_image))
                                                : asset('web/assets/images/default-user.png')));
                                @endphp
                                <img src="{{ $imageUrl }}" 
                                     class="rounded-circle shadow-lg mr-4" 
                                     style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #02767F;"
                                     onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                                     alt="{{ $therapist->user->name ?? 'Therapist' }}">
                                <div class="flex-grow-1">
                                    <h4 class="font-weight-bold mb-2" style="color: #02767F;">{{ $therapist->user->name }}</h4>
                                    <p class="text-muted mb-2">
                                        <i class="las la-user-md text-primary"></i> 
                                        <strong>{{ $therapist->specialization }}</strong>
                                    </p>
                                    @if($therapist->rating)
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="las la-star {{ $i <= $therapist->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-muted">
                                            <strong>{{ number_format($therapist->rating, 1) }}</strong> 
                                            ({{ $therapist->total_reviews ?? 0 }} {{ __('reviews') }})
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center ml-auto">
                                    <div class="pricing-badge p-3 rounded-lg shadow-sm" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); color: white; min-width: 150px;">
                                        <span class="d-block small mb-1 opacity-90">{{ __('Home Visit Fee') }}</span>
                                        <span class="h3 font-weight-bold mb-0">{{ number_format($therapist->home_visit_rate, 2) }} {{ __('EGP') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form Card -->
                    <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header bg-white border-bottom p-4" style="border-radius: 15px 15px 0 0;">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="font-weight-bold mb-0" style="color: #02767F;">
                                    <i class="las la-calendar-check mr-2"></i>{{ __('Appointment Details') }}
                                </h3>
                                <span class="badge badge-primary px-3 py-2" style="background-color: #02767F; font-size: 0.9rem;">
                                    {{ __('Step 1 of 3') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('web.home_visits.store') }}" method="POST" id="bookingForm">
                                @csrf
                                <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                                
                                <!-- Step 1: Date & Time -->
                                <div class="step-section mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="step-number mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">1</div>
                                        <div>
                                            <h5 class="font-weight-bold mb-0" style="color: #02767F;">{{ __('Select Date & Time') }}</h5>
                                            <small class="text-muted">{{ __('Choose your preferred appointment slot') }}</small>
                                        </div>
                                    </div>
                                    
                                    @if(empty($availableSlots))
                                        <div class="alert alert-warning border-0 shadow-sm" style="border-left: 4px solid #ffc107;">
                                            <i class="las la-exclamation-triangle"></i> 
                                            <strong>{{ __('No Availability Set') }}</strong><br>
                                            {{ __('This therapist has not set their availability schedule yet. Please contact them directly or try another therapist.') }}
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold mb-2">
                                                        <i class="las la-calendar text-primary"></i> {{ __('Select Date') }}
                                                    </label>
                                                    <input type="date" 
                                                           name="appointment_date" 
                                                           id="appointment_date" 
                                                           class="form-control form-control-lg border-0 shadow-sm" 
                                                           required 
                                                           min="{{ date('Y-m-d') }}"
                                                           max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                                           style="border-radius: 10px;">
                                                    <small class="text-muted mt-1 d-block">
                                                        <i class="las la-info-circle"></i> {{ __('Only dates with available slots are selectable') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold mb-2">
                                                        <i class="las la-clock text-primary"></i> {{ __('Select Time') }}
                                                    </label>
                                                    <select name="appointment_time" id="appointment_time" class="form-control form-control-lg border-0 shadow-sm" required disabled style="border-radius: 10px;">
                                                        <option value="">{{ __('Select a date first') }}</option>
                                                    </select>
                                                    <small class="text-muted mt-1 d-block" id="time_help">
                                                        <i class="las la-info-circle"></i> {{ __('Available times will appear after selecting a date') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($schedules && $schedules->isNotEmpty())
                                            <div class="alert alert-info border-0 shadow-sm mt-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-left: 4px solid #02767F;">
                                                <strong><i class="las la-info-circle"></i> {{ __('Therapist Availability Schedule') }}:</strong>
                                                <div class="row mt-2">
                                                    @foreach($schedules as $day => $scheduleList)
                                                        @php
                                                            $schedule = $scheduleList->first();
                                                            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('g:i A');
                                                            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                                                        @endphp
                                                        <div class="col-md-6 mb-2">
                                                            <i class="las la-calendar-check text-primary"></i> 
                                                            <strong>{{ ucfirst($day) }}:</strong> {{ $startTime }} - {{ $endTime }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <hr class="my-4" style="border-color: #e0e0e0;">
                                
                                <!-- Step 2: Location -->
                                <div class="step-section mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="step-number mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">2</div>
                                        <div>
                                            <h5 class="font-weight-bold mb-0" style="color: #02767F;">{{ __('Location Details') }}</h5>
                                            <small class="text-muted">{{ __('Where should the therapist visit?') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold mb-2">
                                            <i class="las la-map-marker-alt text-danger"></i> {{ __('Home Address') }}
                                        </label>
                                        <input type="text" 
                                               name="location_address" 
                                               class="form-control form-control-lg border-0 shadow-sm" 
                                               placeholder="{{ __('Enter full address (Street, Building, Apartment, Floor)') }}" 
                                               required
                                               style="border-radius: 10px;">
                                        <small class="text-muted mt-1 d-block">
                                            <i class="las la-info-circle"></i> {{ __('Please provide complete address for accurate service delivery') }}
                                        </small>
                                    </div>
                                </div>
                                
                                <hr class="my-4" style="border-color: #e0e0e0;">
                                
                                <!-- Step 3: Patient Details -->
                                <div class="step-section mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="step-number mr-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">3</div>
                                        <div>
                                            <h5 class="font-weight-bold mb-0" style="color: #02767F;">{{ __('Patient Information') }}</h5>
                                            <small class="text-muted">{{ __('Tell us about the patient') }}</small>
                                        </div>
                                    </div>
                                    @if(!auth()->check())
                                        <div class="alert alert-info border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-left: 4px solid #02767F;">
                                            <i class="las la-info-circle"></i> 
                                            <strong>{{ __('Guest Booking') }}</strong><br>
                                            {{ __('You can book without creating an account. Your information will be saved securely.') }}
                                        </div>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold mb-2">
                                                    <i class="las la-user text-primary"></i> {{ __('Patient Name') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       name="patient_name" 
                                                       class="form-control form-control-lg border-0 shadow-sm" 
                                                       value="{{ auth()->user()->name ?? old('patient_name') }}" 
                                                       {{ !auth()->check() ? 'required' : '' }}
                                                       style="border-radius: 10px;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold mb-2">
                                                    <i class="las la-phone text-primary"></i> {{ __('Phone Number') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="tel" 
                                                       name="patient_phone" 
                                                       class="form-control form-control-lg border-0 shadow-sm" 
                                                       value="{{ auth()->user()->phone ?? old('patient_phone') }}" 
                                                       required
                                                       style="border-radius: 10px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if(!auth()->check())
                                        <div class="form-group mb-3">
                                            <label class="font-weight-bold mb-2">
                                                <i class="las la-envelope text-primary"></i> {{ __('Email Address') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   name="patient_email" 
                                                   class="form-control form-control-lg border-0 shadow-sm" 
                                                   value="{{ old('patient_email') }}" 
                                                   required
                                                   style="border-radius: 10px;">
                                        </div>
                                    @endif
                                    
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold mb-2">
                                            <i class="las la-file-medical text-primary"></i> {{ __('Medical Notes / Complaint') }} 
                                            <span class="badge badge-secondary ml-2">{{ __('Optional') }}</span>
                                        </label>
                                        <textarea name="patient_notes" 
                                                  class="form-control border-0 shadow-sm" 
                                                  rows="4" 
                                                  placeholder="{{ __('Briefly describe the condition, symptoms, or any special requirements...') }}"
                                                  style="border-radius: 10px;">{{ old('patient_notes') }}</textarea>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="las la-info-circle"></i> {{ __('This information helps the therapist prepare for your visit') }}
                                        </small>
                                    </div>
                                </div>

                                <hr class="my-4" style="border-color: #e0e0e0;">
                                
                                <!-- Summary -->
                                <div class="summary-card p-4 mb-4 rounded-lg shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border: 2px solid #02767F;">
                                    <h5 class="font-weight-bold mb-4" style="color: #02767F;">
                                        <i class="las la-receipt mr-2"></i>{{ __('Booking Summary') }}
                                    </h5>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e0e0e0;">
                                        <span class="text-muted">
                                            <i class="las la-user-md mr-2"></i>{{ __('Consultation Fee') }}
                                        </span>
                                        <span class="font-weight-bold">{{ number_format($therapist->home_visit_rate, 2) }} {{ __('EGP') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e0e0e0;">
                                        <span class="text-muted">
                                            <i class="las la-calendar-check mr-2"></i>{{ __('Booking Fee') }}
                                        </span>
                                        <span class="font-weight-bold text-success">0.00 {{ __('EGP') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-3">
                                        <span class="h5 font-weight-bold mb-0" style="color: #02767F;">{{ __('Total') }}</span>
                                        <span class="h4 font-weight-bold mb-0" style="color: #02767F;">{{ number_format($therapist->home_visit_rate, 2) }} {{ __('EGP') }}</span>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="btn btn-block text-white font-weight-bold py-3 shadow-lg" 
                                        style="background: linear-gradient(135deg, #ea3d2f 0%, #c62828 100%); font-size: 1.2rem; border-radius: 10px; border: none; transition: all 0.3s ease;" 
                                        {{ empty($availableSlots) ? 'disabled' : '' }}
                                        onmouseover="if(!this.disabled) this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(234, 61, 47, 0.4)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(234, 61, 47, 0.3)';">
                                    <i class="las la-check-circle mr-2"></i>{{ __('Confirm Booking') }}
                                </button>
                                
                                @if(empty($availableSlots))
                                    <div class="alert alert-warning mt-3 border-0 shadow-sm">
                                        <i class="las la-exclamation-triangle"></i> 
                                        {{ __('Please wait for the therapist to set their availability schedule.') }}
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('styles')
<style>
    /* Fix header overlap */
    body {
        padding-top: 120px !important;
    }

    @media (max-width: 991px) {
        body {
            padding-top: 100px !important;
        }
    }

    @media (max-width: 768px) {
        body {
            padding-top: 90px !important;
        }
    }

    header,
    .header-section,
    #header-section {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
    }

    /* Form enhancements */
    .form-control:focus,
    select:focus {
        border-color: #02767F !important;
        box-shadow: 0 0 0 0.2rem rgba(2, 118, 127, 0.25) !important;
    }

    .step-section {
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .step-number {
        box-shadow: 0 4px 15px rgba(2, 118, 127, 0.3);
        transition: all 0.3s ease;
    }

    .step-number:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(2, 118, 127, 0.4);
    }

    .summary-card {
        animation: slideInRight 0.5s ease;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .step-number {
            width: 40px !important;
            height: 40px !important;
            font-size: 1rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Available slots data from server
    const availableSlots = @json($availableSlots ?? []);
    
    // Get available dates (for date picker validation)
    const availableDates = Object.keys(availableSlots);
    
    // Date input handler
    document.getElementById('appointment_date')?.addEventListener('change', function() {
        const selectedDate = this.value;
        const timeSelect = document.getElementById('appointment_time');
        const timeHelp = document.getElementById('time_help');
        
        // Clear previous options
        timeSelect.innerHTML = '<option value="">{{ __('Select Time') }}</option>';
        
        if (availableSlots[selectedDate] && availableSlots[selectedDate].length > 0) {
            // Enable time select
            timeSelect.disabled = false;
            timeHelp.textContent = '{{ __('Available times for selected date') }}';
            
            // Populate time options
            availableSlots[selectedDate].forEach(function(slot) {
                const option = document.createElement('option');
                option.value = slot.time_24;
                option.textContent = slot.display;
                timeSelect.appendChild(option);
            });
        } else {
            // No slots available for this date
            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="">{{ __('No available times for this date') }}</option>';
            timeHelp.textContent = '{{ __('Please select a different date') }}';
            timeHelp.classList.add('text-danger');
        }
    });
    
    // Restrict date picker to available dates only
    document.getElementById('appointment_date')?.addEventListener('focus', function() {
        // Set min date to today
        this.setAttribute('min', new Date().toISOString().split('T')[0]);
        // Set max date to 30 days from now
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 30);
        this.setAttribute('max', maxDate.toISOString().split('T')[0]);
    });
    
    // Validate date on input
    document.getElementById('appointment_date')?.addEventListener('input', function() {
        const selectedDate = this.value;
        if (selectedDate && !availableSlots[selectedDate]) {
            // Date selected but no slots available
            this.setCustomValidity('{{ __('No available slots for this date. Please select another date.') }}');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush
@endsection

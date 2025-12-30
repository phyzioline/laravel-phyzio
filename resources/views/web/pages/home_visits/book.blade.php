@extends('web.layouts.app')

@section('content')
<main>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white border-bottom p-4">
                            <h3 class="font-weight-bold mb-0">{{ __('Book Appointment') }}</h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Therapist Summary -->
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                @php
                                    // Use profile_photo_url accessor if available, otherwise check therapist profile_photo, then default
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
                                     class="rounded-circle mr-3 shadow-sm" 
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #02767F;"
                                     onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                                     alt="{{ $therapist->user->name ?? 'Therapist' }} {{ __('Profile Photo') }}">
                                <div>
                                    <h5 class="font-weight-bold mb-1">{{ $therapist->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $therapist->specialization }}</p>
                                </div>
                                <div class="ml-auto text-right">
                                    <span class="d-block text-muted small">{{ __('Fees') }}</span>
                                    <span class="h5 font-weight-bold text-primary">{{ $therapist->home_visit_rate }} {{ __('EGP') }}</span>
                                </div>
                            </div>

                            <form action="{{ route('web.home_visits.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                                
                                <!-- Step 1: Date & Time -->
                                <h5 class="font-weight-bold mb-3">1. {{ __('Select Date & Time') }}</h5>
                                
                                @if(empty($availableSlots))
                                    <div class="alert alert-warning">
                                        <i class="las la-exclamation-triangle"></i> 
                                        {{ __('This therapist has not set their availability schedule yet. Please contact them directly or try another therapist.') }}
                                    </div>
                                @else
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Date') }}</label>
                                                <input type="date" 
                                                       name="appointment_date" 
                                                       id="appointment_date" 
                                                       class="form-control" 
                                                       required 
                                                       min="{{ date('Y-m-d') }}"
                                                       max="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                                <small class="text-muted">{{ __('Only dates with available slots are selectable') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Time') }}</label>
                                                <select name="appointment_time" id="appointment_time" class="form-control" required disabled>
                                                    <option value="">{{ __('Select a date first') }}</option>
                                                </select>
                                                <small class="text-muted" id="time_help">{{ __('Available times will appear after selecting a date') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($schedules && $schedules->isNotEmpty())
                                        <div class="alert alert-info mb-4">
                                            <strong><i class="las la-info-circle"></i> {{ __('Therapist Availability') }}:</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach($schedules as $day => $scheduleList)
                                                    @php
                                                        $schedule = $scheduleList->first();
                                                        $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('g:i A');
                                                        $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('g:i A');
                                                    @endphp
                                                    <li>{{ ucfirst($day) }}: {{ $startTime }} - {{ $endTime }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endif

                                <!-- Step 2: Location -->
                                <h5 class="font-weight-bold mb-3">2. {{ __('Location Details') }}</h5>
                                <div class="form-group mb-4">
                                    <label>{{ __('Home Address') }}</label>
                                    <input type="text" name="location_address" class="form-control" placeholder="{{ __('Enter full address (Street, Building, Apartment)') }}" required>
                                </div>
                                
                                <!-- Step 3: Patient Details -->
                                <h5 class="font-weight-bold mb-3">3. {{ __('Patient Details') }}</h5>
                                @if(!auth()->check())
                                    <div class="alert alert-info mb-3">
                                        <i class="las la-info-circle"></i> {{ __('You can book without creating an account. Your information will be saved securely.') }}
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label>{{ __('Patient Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="patient_name" class="form-control" value="{{ auth()->user()->name ?? old('patient_name') }}" {{ !auth()->check() ? 'required' : '' }}>
                                </div>
                                @if(!auth()->check())
                                    <div class="form-group mb-3">
                                        <label>{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                        <input type="email" name="patient_email" class="form-control" value="{{ old('patient_email') }}" required>
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label>{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" name="patient_phone" class="form-control" value="{{ auth()->user()->phone ?? old('patient_phone') }}" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label>{{ __('Medical Notes / Complaint') }} ({{ __('Optional') }})</label>
                                    <textarea name="patient_notes" class="form-control" rows="3" placeholder="{{ __('Briefly describe the condition...') }}">{{ old('patient_notes') }}</textarea>
                                </div>

                                <!-- Summary -->
                                <div class="bg-light p-3 rounded mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('Consultation Fee') }}</span>
                                        <span>{{ $therapist->home_visit_rate }} {{ __('EGP') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('Booking Fee') }}</span>
                                        <span>0 {{ __('EGP') }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>{{ __('Total') }}</span>
                                        <span class="text-primary">{{ $therapist->home_visit_rate }} {{ __('EGP') }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block text-white font-weight-bold py-3" style="background-color: #ea3d2f; font-size: 1.1rem;" {{ empty($availableSlots) ? 'disabled' : '' }}>
                                    {{ __('Confirm Booking') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

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

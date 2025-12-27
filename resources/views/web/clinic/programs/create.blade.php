@extends('web.layouts.dashboard_master')

@section('title', 'Create Weekly Program')
@section('header_title', 'Create New Treatment Program')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Program Details') }}</h5>
            </div>
            <div class="card-body px-4">
                <form id="programForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="form-group">
                        <label>{{ __('Program Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="program_name" class="form-control" 
                               placeholder="e.g., Post-ACL Reconstruction - 12 Weeks" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Patient') }} <span class="text-danger">*</span></label>
                                <select name="patient_id" class="form-control" required>
                                    <option value="">{{ __('Select Patient') }}</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                                {{ $patientId == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Specialty') }} <span class="text-danger">*</span></label>
                                <select name="specialty" id="specialty" class="form-control" required>
                                    <option value="">{{ __('Select Specialty') }}</option>
                                    @foreach(['orthopedic', 'pediatric', 'neurological', 'sports', 'geriatric', 'womens_health', 'cardiorespiratory', 'home_care'] as $spec)
                                        <option value="{{ $spec }}" {{ $specialty == $spec ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $spec)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Program Configuration -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Sessions per Week') }} <span class="text-danger">*</span></label>
                                <select name="sessions_per_week" id="sessions_per_week" class="form-control" required>
                                    <option value="1">1 session/week</option>
                                    <option value="2" selected>2 sessions/week</option>
                                    <option value="3">3 sessions/week</option>
                                    <option value="4">4 sessions/week</option>
                                    <option value="5">5 sessions/week</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Total Weeks') }} <span class="text-danger">*</span></label>
                                <input type="number" name="total_weeks" id="total_weeks" 
                                       class="form-control" min="1" max="52" value="4" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Session Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Duration per Session (minutes)') }}</label>
                                <select name="duration_minutes" class="form-control">
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60" selected">60 minutes</option>
                                    <option value="90">90 minutes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Therapist Level') }}</label>
                                <select name="therapist_level" class="form-control">
                                    <option value="junior">Junior</option>
                                    <option value="senior" selected>Senior</option>
                                    <option value="consultant">Consultant</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Therapist & Episode -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Primary Therapist') }}</label>
                                <select name="therapist_id" class="form-control">
                                    <option value="">{{ __('Select Therapist') }}</option>
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Episode of Care') }}</label>
                                <select name="episode_id" class="form-control">
                                    <option value="">{{ __('Select Episode') }}</option>
                                    @foreach($episodes as $episode)
                                        <option value="{{ $episode->id }}">{{ $episode->title ?? 'Episode #' . $episode->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Plan -->
                    <div class="form-group">
                        <label>{{ __('Payment Plan') }} <span class="text-danger">*</span></label>
                        <select name="payment_plan" id="payment_plan" class="form-control" required>
                            <option value="pay_per_week">{{ __('Pay Per Week') }}</option>
                            <option value="monthly">{{ __('Monthly Subscription') }}</option>
                            <option value="upfront">{{ __('Upfront Payment (Largest Discount)') }}</option>
                        </select>
                    </div>

                    <!-- Auto-booking -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="auto_booking_enabled" 
                                   class="custom-control-input" id="auto_booking" value="1" checked>
                            <label class="custom-control-label" for="auto_booking">
                                {{ __('Enable Auto-booking') }} - Automatically book sessions as appointments
                            </label>
                        </div>
                    </div>

                    <!-- Goals & Notes -->
                    <div class="form-group">
                        <label>{{ __('Program Goals') }}</label>
                        <textarea name="goals" class="form-control" rows="3" 
                                  placeholder="Enter program goals and objectives..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Additional notes..."></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="las la-save"></i> {{ __('Create Program') }}
                        </button>
                        <a href="{{ route('clinic.programs.index') }}" class="btn btn-secondary btn-lg">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pricing Preview -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Pricing Preview') }}</h5>
            </div>
            <div class="card-body px-4" id="pricingPreview">
                <div class="text-center py-4">
                    <i class="las la-calculator fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('Fill in program details to see pricing') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('programForm');
    const pricingPreview = document.getElementById('pricingPreview');
    
    // Fields that trigger price calculation
    const priceFields = ['specialty', 'sessions_per_week', 'total_weeks', 'duration_minutes', 'therapist_level', 'payment_plan'];
    
    // Calculate price on field change
    priceFields.forEach(field => {
        const element = document.getElementById(field) || form.querySelector(`[name="${field}"]`);
        if (element) {
            element.addEventListener('change', calculatePrice);
        }
    });
    
    // Initial calculation
    calculatePrice();
    
    function calculatePrice() {
        const specialty = document.getElementById('specialty').value;
        const sessionsPerWeek = document.getElementById('sessions_per_week').value;
        const totalWeeks = document.getElementById('total_weeks').value;
        const durationMinutes = form.querySelector('[name="duration_minutes"]').value;
        const therapistLevel = form.querySelector('[name="therapist_level"]').value;
        
        if (!specialty || !sessionsPerWeek || !totalWeeks) {
            return;
        }
        
        fetch('{{ route("clinic.programs.calculatePrice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                specialty: specialty,
                sessions_per_week: sessionsPerWeek,
                total_weeks: totalWeeks,
                duration_minutes: durationMinutes,
                therapist_level: therapistLevel,
                location: 'clinic'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pricing = data.pricing;
                const paymentPlan = document.getElementById('payment_plan').value;
                
                let planPrice = pricing.total_with_discount;
                if (paymentPlan === 'weekly') {
                    planPrice = pricing.weekly_price;
                } else if (paymentPlan === 'monthly') {
                    planPrice = pricing.monthly_price;
                }
                
                pricingPreview.innerHTML = `
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Single Session Price') }}</small>
                        <h4 class="mb-0">$${pricing.single_session_price.toFixed(2)}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Total Sessions') }}</small>
                        <h5 class="mb-0">${pricing.total_sessions}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Total Without Discount') }}</small>
                        <h5 class="mb-0 text-muted"><s>$${pricing.total_without_discount.toFixed(2)}</s></h5>
                    </div>
                    <div class="mb-3 p-3 bg-light rounded">
                        <small class="text-muted d-block mb-1">{{ __('Discount') }}</small>
                        <span class="badge badge-success">${pricing.discount_percentage}% OFF</span>
                        <small class="text-muted d-block mt-1">-$${pricing.discount_amount.toFixed(2)}</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">{{ __('Total Program Price') }}</small>
                        <h3 class="mb-0 text-primary">$${pricing.total_with_discount.toFixed(2)}</h3>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">{{ __('Payment Plan') }}</small>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Weekly') }}:</span>
                                <strong>$${pricing.weekly_price.toFixed(2)}/week</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Monthly') }}:</span>
                                <strong>$${pricing.monthly_price.toFixed(2)}/month</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Upfront') }}:</span>
                                <strong class="text-success">$${pricing.upfront_price.toFixed(2)}</strong>
                            </div>
                        </div>
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
        
        fetch('{{ route("clinic.programs.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': formData.get('_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '{{ route("clinic.programs.index") }}';
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    });
});
</script>
@endsection


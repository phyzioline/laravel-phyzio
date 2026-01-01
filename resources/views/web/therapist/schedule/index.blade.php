@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid px-4 py-5" style="background-color: #f4f7f6;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 font-weight-bold text-gray-800 mb-1" style="color: #2c3e50;">{{ __('My Availability') }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">{{ __('Manage your weekly schedule and working hours') }}</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm d-flex align-items-center" type="button" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal" style="background-color: #02767F; border-color: #02767F; border-radius: 8px;">
            <i class="las la-plus-circle mr-2" style="font-size: 1.2rem;"></i> 
            <span class="font-weight-bold">{{ __('Add New Slot') }}</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-white shadow-sm border-0 h-100" style="border-radius: 12px; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">{{ __('Total Slots') }}</div>
                        <div class="icon-shape bg-light text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; color: #02767F !important;">
                            <i class="las la-clock" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 font-weight-bold text-dark">{{ $availableSlots }}</h2>
                    <p class="text-muted small mb-0">{{ __('Active slots this week') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-white shadow-sm border-0 h-100" style="border-radius: 12px; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">{{ __('Bookings') }}</div>
                        <div class="icon-shape bg-light text-success rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="las la-calendar-check" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 font-weight-bold text-dark">{{ $bookedSlots }}</h2>
                    <p class="text-muted small mb-0">{{ __('Confirmed appointments') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-white shadow-sm border-0 h-100" style="border-radius: 12px; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">{{ __('Blocked') }}</div>
                        <div class="icon-shape bg-light text-warning rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="las la-ban" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 font-weight-bold text-dark">{{ $blockedSlots }}</h2>
                    <p class="text-muted small mb-0">{{ __('Unavailable slots') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-white shadow-sm border-0 h-100" style="border-radius: 12px; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">{{ __('Utilization') }}</div>
                        <div class="icon-shape bg-light text-info rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="las la-chart-pie" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-1 font-weight-bold text-dark">{{ $utilizationRate }}%</h2>
                    <p class="text-muted small mb-0">{{ __('Schedule efficiency') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Grid -->
    <div class="card shadow-sm border-0 bg-white" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 12px 12px 0 0;">
            <h5 class="mb-0 font-weight-bold" style="color: #2c3e50;">{{ __('Weekly Schedule') }}</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                @endphp

                @foreach($days as $day)
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 rounded h-100" style="background-color: #f8f9fc; border: 1px solid #edf2f9;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary px-2 py-1 mr-2" style="background-color: #02767F !important;">{{ substr(ucfirst($day), 0, 3) }}</span>
                            <h6 class="mb-0 text-capitalize font-weight-bold text-dark">{{ ucfirst($day) }}</h6>
                        </div>
                        
                        <div class="schedule-list">
                            @php
                                $daySchedules = $schedules->where('day_of_week', $day);
                            @endphp

                            @forelse($daySchedules as $schedule)
                                <div class="bg-white p-2 mb-2 rounded border-left shadow-sm d-flex justify-content-between align-items-center" style="border-left: 3px solid #02767F !important;">
                                    <div>
                                        <div class="small fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                        </div>
                                        <div class="text-xs text-muted">{{ $schedule->slot_duration }} {{ __('min') }}</div>
                                    </div>
                                    <i class="las la-check-circle text-success ms-2"></i>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted border border-dashed rounded bg-white">
                                    <i class="las la-coffee mb-2" style="font-size: 1.5rem; opacity: 0.5;"></i>
                                    <p class="small mb-0">{{ __('No slots') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Professional Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1" aria-labelledby="addAvailabilityModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #02767F 0%, #039ba7 100%);">
                <h5 class="modal-title font-weight-bold" id="addAvailabilityModalLabel">
                    <i class="las la-calendar-plus mr-2"></i> {{ __('Set Availability') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('therapist.availability.update') }}" method="POST" id="availabilityForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light">
                    <!-- Validation Errors -->
                    <div id="form-errors" class="alert alert-danger shadow-sm d-none mb-4" role="alert">
                        <i class="las la-exclamation-circle mr-2"></i>
                        <span id="error-message"></span>
                    </div>

                    <!-- Step 1: Days -->
                    <div class="bg-white p-3 rounded shadow-sm mb-4">
                        <label class="form-label font-weight-bold text-secondary text-uppercase small mb-3">{{ __('Select Working Days') }} <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="col-6 col-sm-4">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input day-checkbox" type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}" style="cursor: pointer;">
                                    <label class="form-check-label text-capitalize small" for="day_{{ $day }}" style="cursor: pointer; user-select: none;">
                                        {{ ucfirst($day) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 2: Time -->
                    <div class="bg-white p-3 rounded shadow-sm mb-4">
                        <label class="form-label font-weight-bold text-secondary text-uppercase small mb-3">{{ __('Define Time Slots') }} <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="small text-muted mb-1">{{ __('Start Time') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-clock"></i></span>
                                    <input type="time" name="start_time" class="form-control border-left-0" value="09:00" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted mb-1">{{ __('End Time') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-history"></i></span>
                                    <input type="time" name="end_time" class="form-control border-left-0" value="17:00" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Date Range & Duration -->
                    <div class="bg-white p-3 rounded shadow-sm">
                         <div class="mb-3">
                            <label class="form-label font-weight-bold text-secondary text-uppercase small">{{ __('Effective Period') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                <span class="input-group-text bg-light text-muted">{{ __('to') }}</span>
                                <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                            </div>
                        </div>
                        
                        <div>
                             <label class="form-label font-weight-bold text-secondary text-uppercase small">{{ __('Session Duration') }}</label>
                             <select name="slot_duration" class="form-select form-select-sm">
                                 <option value="15">15 {{ __('Minutes') }}</option>
                                 <option value="30" selected>30 {{ __('Minutes') }}</option>
                                 <option value="45">45 {{ __('Minutes') }}</option>
                                 <option value="60">60 {{ __('Minutes') }}</option>
                             </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white py-3 px-4">
                    <button type="button" class="btn btn-light text-muted fw-bold" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="background-color: #02767F; border-color: #02767F;">
                         <i class="las la-save mr-1"></i> {{ __('Save Schedule') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fix for Modal Layering Issue - Move modal to body
        var modalEl = document.getElementById('addAvailabilityModal');
        if (modalEl) {
            document.body.appendChild(modalEl);
        }

        // Form Validation Logic
        const form = document.getElementById('availabilityForm');
        const errorContainer = document.getElementById('form-errors');
        const errorMessage = document.getElementById('error-message');
        
        form.addEventListener('submit', function(event) {
            let isValid = true;
            let errorMsg = "";
            
            // Validate Days
            const checkedDays = document.querySelectorAll('.day-checkbox:checked');
            if (checkedDays.length === 0) {
                errorMsg = "{{ __('Please select at least one working day.') }}";
                isValid = false;
            }
            
            // Validate Time
            const startTime = form.querySelector('input[name="start_time"]').value;
            const endTime = form.querySelector('input[name="end_time"]').value;
            
            if (isValid && startTime && endTime && startTime >= endTime) {
                errorMsg = "{{ __('End time must be after start time.') }}";
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
                errorMessage.textContent = errorMsg;
                errorContainer.classList.remove('d-none');
                
                // Scroll toggle modal to top to see error if needed
                // But modal is fixed, so errors are visible. 
                // Shake effect?
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 500);
            } else {
                errorContainer.classList.add('d-none');
            }
        });
        
        // Hide error on interaction
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                errorContainer.classList.add('d-none');
            });
        });
    });
</script>

<style>
    /* Custom Animations for Professional Feel */
    @keyframes shake {
      0% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      75% { transform: translateX(-5px); }
      100% { transform: translateX(0); }
    }
    .shake {
      animation: shake 0.3s ease-in-out;
    }
    
    .form-check-input:checked {
        background-color: #02767F;
        border-color: #02767F;
    }
    
    /* Ensure modal backdrops are correct if moved to body */
    .modal-backdrop {
        z-index: 1040 !important;
    }
    .modal {
        z-index: 1050 !important;
    }
</style>
@endpush
@endsection

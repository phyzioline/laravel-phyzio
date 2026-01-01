@extends('therapist.layouts.app')

@section('content')
<style>
    /* Fix modal z-index - must be higher than header (999) and everything else */
    #addSlotModal {
        z-index: 9999 !important;
    }
    #addSlotModal .modal-dialog {
        z-index: 10000 !important;
        pointer-events: auto !important;
    }
    #addSlotModal .modal-content {
        z-index: 10001 !important;
        pointer-events: auto !important;
        position: relative;
    }
    .modal-backdrop {
        z-index: 9998 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
        pointer-events: auto !important;
    }
    .modal.show {
        display: block !important;
        pointer-events: auto !important;
    }
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    /* Hide overlay when modal is open */
    body.modal-open .overlay {
        display: none !important;
        z-index: 1 !important;
    }
    body.toggled.modal-open .overlay {
        display: none !important;
    }
</style>
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('My Schedule') }}</h2>
            <p class="text-muted">{{ __('Manage your recurring weekly availability') }}</p>
        </div>
        <div>
             <!-- Toggle Modal -->
             <button class="btn btn-primary shadow-sm" type="button" id="openAvailabilityModal">
                <i class="las la-plus"></i> {{ __('Add Availability') }}
             </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-clock text-primary mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $availableSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Total Weekly Slots') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-calendar-check text-success mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $bookedSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Bookings this Month') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-ban text-warning mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $blockedSlots }}</h3>
                    <p class="text-muted small mb-0">{{ __('Blocked') }}</p>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 text-center py-3">
                 <div class="card-body">
                    <i class="las la-percent text-info mb-2" style="font-size: 32px;"></i>
                    <h3 class="font-weight-bold text-dark mb-0">{{ $utilizationRate }}%</h3>
                    <p class="text-muted small mb-0">{{ __('Utilization') }}</p>
                </div>
             </div>
        </div>
    </div>

    <!-- Weekly Schedule View -->
    <div class="row">
        @php
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        @endphp

        @foreach($days as $day)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <h5 class="text-capitalize font-weight-bold text-primary">{{ $day }}</h5>
                </div>
                <div class="card-body">
                    @php
                        // Filter schedules for this day
                        $daySchedules = $schedules->where('day_of_week', $day);
                    @endphp

                    @forelse($daySchedules as $schedule)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <i class="las la-clock text-muted"></i> 
                                <strong>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}</strong> - 
                                <strong>{{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</strong>
                            </div>
                            <!-- Future: Add delete button here -->
                        </div>
                    @empty
                        <p class="text-muted small text-center mb-0 p-3 bg-light rounded">No slots available</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Availability Modal -->
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true" role="dialog" aria-labelledby="addSlotModalLabel" aria-modal="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSlotModalLabel">SET AVAILABILITY</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('therapist.availability.update') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Days Selection -->
            <div class="mb-3">
                <label class="form-label font-weight-bold">Select Days <span class="text-danger">*</span></label>
                <div class="row">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input day-checkbox" type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}">
                            <label class="form-check-label text-capitalize" for="day_{{ $day }}">
                                {{ ucfirst($day) }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <small class="text-danger" id="days-error" style="display: none;">Please select at least one day.</small>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" value="09:00" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" value="17:00" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Date Range (Optional Validity)</label>
                <div class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Slot Duration (Minutes)</label>
                 <select name="slot_duration" class="form-control">
                     <option value="15">15 Minutes</option>
                     <option value="30" selected>30 Minutes</option>
                     <option value="45">45 Minutes</option>
                     <option value="60">60 Minutes</option>
                 </select>
            </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Availability</button>
          </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var modalElement = $('#addSlotModal');
    var modalInstance = null;
    
    // Check for Bootstrap 5
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        modalInstance = new bootstrap.Modal(modalElement[0], {
            backdrop: true,
            keyboard: true,
            focus: true
        });
    }
    
    // Open modal button
    $('#openAvailabilityModal').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Hide overlay immediately
        $('.overlay').hide();
        $('body').removeClass('toggled');
        
        // Remove any existing backdrops
        $('.modal-backdrop').remove();
        
        // Reset form
        modalElement.find('form')[0].reset();
        $('#days-error').hide();
        modalElement.find('.form-control').removeClass('is-invalid');
        
        // Show modal
        if (modalInstance) {
            modalInstance.show();
        } else {
            // Fallback for Bootstrap 4 or manual
            modalElement.modal('show');
        }
        
        // Force hide overlay after a short delay
        setTimeout(function() {
            $('.overlay').hide();
            $('body').removeClass('toggled');
        }, 100);
    });
    
    // Close modal handlers
    modalElement.find('.btn-close, [data-bs-dismiss="modal"], button.btn-secondary').on('click', function() {
        if (modalInstance) {
            modalInstance.hide();
        } else {
            modalElement.modal('hide');
        }
    });
    
    // Close on backdrop click
    modalElement.on('click', function(e) {
        if ($(e.target).is(modalElement)) {
            if (modalInstance) {
                modalInstance.hide();
            } else {
                modalElement.modal('hide');
            }
        }
    });
    
    // Ensure overlay is hidden when modal is shown
    modalElement.on('shown.bs.modal show.bs.modal', function() {
        $('.overlay').hide();
        $('body').removeClass('toggled');
        // Force z-index
        $(this).css('z-index', '9999');
        $(this).find('.modal-dialog').css('z-index', '10000');
        $(this).find('.modal-content').css('z-index', '10001');
    });
    
    // Clean up on hide
    modalElement.on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
    
    // Form validation
    modalElement.find('form').on('submit', function(e) {
        var daysChecked = $('.day-checkbox:checked').length;
        var startTime = $('input[name="start_time"]').val();
        var endTime = $('input[name="end_time"]').val();
        var isValid = true;
        
        if (daysChecked === 0) {
            $('#days-error').show();
            isValid = false;
        } else {
            $('#days-error').hide();
        }
        
        if (!startTime) {
            $('input[name="start_time"]').addClass('is-invalid');
            isValid = false;
        } else {
            $('input[name="start_time"]').removeClass('is-invalid');
        }
        
        if (!endTime) {
            $('input[name="end_time"]').addClass('is-invalid');
            isValid = false;
        } else {
            $('input[name="end_time"]').removeClass('is-invalid');
        }
        
        if (startTime && endTime && startTime >= endTime) {
            alert("{{ __('End time must be after start time.') }}");
            $('input[name="end_time"]').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
    
    // Remove error on day selection
    $('.day-checkbox').on('change', function() {
        if ($('.day-checkbox:checked').length > 0) {
            $('#days-error').hide();
        }
    });
});
</script>
@endpush
@endsection

@extends('therapist.layouts.app')

@section('content')
<style>
    /* Fix modal z-index and pointer events */
    #addSlotModal {
        z-index: 1055 !important;
    }
    .modal-backdrop {
        z-index: 1054 !important;
        pointer-events: auto !important;
    }
    .modal {
        pointer-events: auto !important;
    }
    .modal-content {
        pointer-events: auto !important;
    }
    /* Ensure modal is clickable */
    .modal.show {
        display: block !important;
        pointer-events: auto !important;
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
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="z-index: 1056;">
      <div class="modal-header">
        <h5 class="modal-title">Set Availability</h5>
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
        // Get modal element
        var modalElement = document.getElementById('addSlotModal');
        var addSlotModal = null;
        
        // Initialize Bootstrap modal
        if (typeof bootstrap !== 'undefined') {
            addSlotModal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
        } else if (typeof $.fn.modal !== 'undefined') {
            // Fallback to jQuery Bootstrap modal (Bootstrap 4)
            $(modalElement).modal({
                backdrop: true,
                keyboard: true,
                show: false
            });
        }
        
        // Handle button click to open modal
        $('#openAvailabilityModal').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Remove any blocking overlays
            $('.overlay').hide();
            $('body').removeClass('toggled');
            
            // Reset form and errors
            $('#addSlotModal form')[0].reset();
            $('#days-error').hide();
            $('.form-control').removeClass('is-invalid');
            
            // Show modal
            if (addSlotModal) {
                addSlotModal.show();
            } else if (typeof $.fn.modal !== 'undefined') {
                $(modalElement).modal('show');
            } else {
                // Fallback: show modal manually
                $(modalElement).addClass('show').css('display', 'block');
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }
        });
        
        // Handle modal open event
        $(modalElement).on('show.bs.modal', function () {
            // Remove any blocking overlays
            $('.overlay').hide();
            $('body').removeClass('toggled');
            // Reset form and errors
            $('#addSlotModal form')[0].reset();
            $('#days-error').hide();
            $('.form-control').removeClass('is-invalid');
        });
        
        // Ensure modal content is clickable
        $(modalElement).on('shown.bs.modal', function () {
            $(this).css('pointer-events', 'auto');
            $('.modal-content', this).css('pointer-events', 'auto');
        });
        
        // Handle close button
        $(modalElement).find('.btn-close, [data-bs-dismiss="modal"], button[type="button"].btn-secondary').on('click', function() {
            if (addSlotModal) {
                addSlotModal.hide();
            } else if (typeof $.fn.modal !== 'undefined') {
                $(modalElement).modal('hide');
            } else {
                $(modalElement).removeClass('show').css('display', 'none');
                $('.modal-backdrop').remove();
            }
        });
        
        // Client-side form validation
        $('#addSlotModal form').on('submit', function(e) {
            var daysChecked = $('.day-checkbox:checked').length;
            var startTime = $('input[name="start_time"]').val();
            var endTime = $('input[name="end_time"]').val();
            var isValid = true;
            
            // Validate days
            if (daysChecked === 0) {
                $('#days-error').show();
                isValid = false;
            } else {
                $('#days-error').hide();
            }
            
            // Validate times
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
            
            // Validate end time is after start time
            if (startTime && endTime && startTime >= endTime) {
                alert('End time must be after start time.');
                $('input[name="end_time"]').addClass('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
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

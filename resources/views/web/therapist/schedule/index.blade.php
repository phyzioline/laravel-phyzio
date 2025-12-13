@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('My Schedule') }}</h2>
            <p class="text-muted">{{ __('Manage your recurring weekly availability') }}</p>
        </div>
        <div>
             <!-- Toggle Modal -->
             <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addSlotModal">
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
<div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Set Availability</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('therapist.availability.update') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <!-- Days Selection -->
            <div class="mb-3">
                <label class="form-label font-weight-bold">Select Days</label>
                <div class="row">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}">
                            <label class="form-check-label text-capitalize" for="day_{{ $day }}">
                                {{ $day }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                     <label class="form-label">End Time</label>
                     <input type="time" name="end_time" class="form-control" required>
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
@endsection

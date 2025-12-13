@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Schedule Management') }}</h2>
            <p class="text-muted">{{ __('Manage your availability and time slots') }}</p>
        </div>
        <div>
             <button class="btn btn-outline-secondary mr-2 shadow-sm"><i class="las la-file-export"></i> {{ __('Export Schedule') }}</button>
             <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#availabilityModal"><i class="las la-plus"></i> {{ __('Set Availability') }}</button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-4">
             <div class="card shadow-sm border-0 h-100 py-3">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="las la-clock fa-2x"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark mb-1">{{ $availableSlots ?? 32 }}</h3>
                    <div class="text-muted">{{ __('Available Slots') }}</div>
                </div>
             </div>
        </div>
         <div class="col-md-4">
             <div class="card shadow-sm border-0 h-100 py-3">
                <div class="card-body text-center">
                     <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="las la-ban fa-2x"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark mb-1">{{ $blockedSlots ?? 6 }}</h3>
                    <div class="text-muted">{{ __('Blocked Slots') }}</div>
                </div>
             </div>
        </div>
         <div class="col-md-4">
             <div class="card shadow-sm border-0 h-100 py-3">
                <div class="card-body text-center">
                     <div class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="las la-percent fa-2x"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark mb-1">{{ $utilizationRate ?? 75 }}%</h3>
                    <div class="text-muted">{{ __('Utilization Rate') }}</div>
                </div>
             </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
             <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm mr-2"><i class="las la-chevron-left"></i></button>
                <h5 class="m-0 font-weight-bold text-dark">{{ now()->format('F Y') }}</h5>
                <button class="btn btn-outline-secondary btn-sm ml-2"><i class="las la-chevron-right"></i></button>
             </div>
             <div class="btn-group btn-group-sm">
                 <button class="btn btn-outline-secondary">Week</button>
                 <button class="btn btn-primary">Month</button>
                 <button class="btn btn-outline-secondary">Day</button>
             </div>
        </div>
        <div class="card-body p-0">
             <!-- Simplified Calendar Grid -->
             <div class="table-responsive">
                 <table class="table table-bordered mb-0" style="table-layout: fixed;">
                    <thead class="bg-light">
                        <tr class="text-center text-muted small text-uppercase">
                            <th class="py-3">Sun</th>
                            <th class="py-3">Mon</th>
                            <th class="py-3">Tue</th>
                            <th class="py-3">Wed</th>
                            <th class="py-3">Thu</th>
                            <th class="py-3">Fri</th>
                            <th class="py-3">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $daysInMonth = now()->daysInMonth;
                            $startDay = now()->startOfMonth()->dayOfWeek;
                            $currentDay = 1;
                        @endphp
                        
                        @for ($row = 0; $row < 6; $row++)
                            <tr style="height: 120px;">
                                @for ($col = 0; $col < 7; $col++)
                                    @if (($row == 0 && $col < $startDay) || $currentDay > $daysInMonth)
                                        <td class="bg-light"></td>
                                    @else
                                        <td class="p-2 align-top position-relative">
                                            <span class="font-weight-bold {{ now()->day == $currentDay ? 'text-primary' : 'text-dark' }}">{{ $currentDay }}</span>
                                            
                                            <!-- Check schedules for this day -->
                                            @php
                                                $date = now()->startOfMonth()->addDays($currentDay - 1);
                                                $dayName = $date->format('l');
                                                $daySchedules = $schedules->filter(function($s) use ($dayName, $date) {
                                                    return $s->day_of_week == $dayName && 
                                                           ($s->start_date <= $date && $s->end_date >= $date);
                                                });
                                            @endphp

                                            @foreach($daySchedules as $schedule)
                                                <div class="mt-1 small px-2 py-1 rounded bg-success text-white" style="font-size: 0.75rem;">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </div>
                                            @endforeach

                                            @php $currentDay++; @endphp
                                        </td>
                                    @endif
                                @endfor
                            </tr>
                            @if ($currentDay > $daysInMonth) @break @endif
                        @endfor
                    </tbody>
                 </table>
             </div>
        </div>
    </div>
</div>

<!-- Set Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 rounded-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title font-weight-bold">{{ __('Set Availability') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('therapist.availability.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <!-- Date Range -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2">Date Range</label>
                        <div class="row">
                             <div class="col-6">
                                <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                             </div>
                             <div class="col-6">
                                <input type="date" name="end_date" class="form-control" value="{{ now()->addMonth()->format('Y-m-d') }}" required>
                             </div>
                        </div>
                    </div>

                    <!-- Days -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold small text-muted text-uppercase mb-2">Days of Week</label>
                        <div class="d-flex flex-wrap">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="custom-control custom-checkbox mr-4 mb-2">
                                <input type="checkbox" class="custom-control-input" id="day-{{ $day }}" name="days[]" value="{{ $day }}">
                                <label class="custom-control-label" for="day-{{ $day }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div class="form-group mb-4">
                         <label class="font-weight-bold small text-muted text-uppercase mb-2">Time Slots</label>
                         <div class="row">
                            <div class="col-6">
                                <label class="small text-muted">Start Time</label>
                                <input type="time" name="start_time" class="form-control" value="09:00" required>
                            </div>
                             <div class="col-6">
                                 <label class="small text-muted">End Time</label>
                                <input type="time" name="end_time" class="form-control" value="17:00" required>
                            </div>
                         </div>
                    </div>

                    <!-- Durations -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                             <label class="font-weight-bold small text-muted text-uppercase mb-2">Slot Duration</label>
                             <select name="slot_duration" class="form-control custom-select">
                                 <option value="15">15 minutes</option>
                                 <option value="30" selected>30 minutes</option>
                                 <option value="45">45 minutes</option>
                                 <option value="60">60 minutes</option>
                             </select>
                        </div>
                         <div class="col-md-6 form-group">
                             <label class="font-weight-bold small text-muted text-uppercase mb-2">Break Between Slots</label>
                             <select name="break_duration" class="form-control custom-select">
                                 <option value="0">None</option>
                                 <option value="5">5 minutes</option>
                                 <option value="10" selected>10 minutes</option>
                                 <option value="15">15 minutes</option>
                             </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Availability</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

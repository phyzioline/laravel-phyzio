@extends('web.layouts.dashboard_master')

@section('title', 'Appointment Calendar')
@section('header_title', 'Schedule')

@section('content')
<div class="row">
    <!-- Sidebar / Create -->
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h5 class="font-weight-bold mb-3">{{ __('Quick Book') }}</h5>
                <form action="{{ route('clinic.appointments.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('Patient') }}</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">Select...</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Doctor') }}</label>
                        <select name="doctor_id" class="form-control">
                            <option value="">Any Available</option>
                            @foreach($therapists as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="form-group">
                        <label>{{ __('Date') }}</label>
                        <input type="date" name="appointment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                     <div class="form-group">
                        <label>{{ __('Time') }}</label>
                        <input type="time" name="appointment_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Type') }}</label>
                        <select name="type" class="form-control">
                            <option value="session">{{ __('Therapy Session') }}</option>
                            <option value="evaluation">{{ __('Initial Evaluation') }}</option>
                            <option value="followup">{{ __('Follow-up') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="background-color: #00897b; border-color: #00897b;">
                        {{ __('Confirm Booking') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; min-height: 600px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                <a href="{{ route('clinic.appointments.index', ['week' => request('week', 0) - 1]) }}" class="btn btn-sm btn-light">
                    <i class="las la-chevron-left"></i>
                </a>
                <h5 class="font-weight-bold mb-0">
                    {{ $startOfWeek->format('M d') }} - {{ $startOfWeek->copy()->addDays(6)->format('M d, Y') }}
                </h5>
                <a href="{{ route('clinic.appointments.index', ['week' => request('week', 0) + 1]) }}" class="btn btn-sm btn-light">
                    <i class="las la-chevron-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-fixed" style="table-layout: fixed;">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-muted small" style="width: 60px;">Time</th>
                                @for($i = 0; $i < 7; $i++)
                                    <th>
                                        <div class="font-weight-bold">{{ $startOfWeek->copy()->addDays($i)->format('D') }}</div>
                                        <div class="small text-muted">{{ $startOfWeek->copy()->addDays($i)->format('d') }}</div>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @for($hour = 8; $hour <= 18; $hour++)
                                <tr>
                                    <td class="align-middle text-muted small py-3">{{ $hour }}:00</td>
                                    @for($day = 0; $day < 7; $day++)
                                        @php
                                            $currentSlot = $startOfWeek->copy()->addDays($day)->setHour($hour)->setMinute(0)->setSecond(0);
                                            // Find appointment in this slot (check if appointment time matches this hour)
                                            $appt = $appointments->filter(function($a) use ($currentSlot) {
                                                $apptDate = \Carbon\Carbon::parse($a->appointment_date);
                                                return $apptDate->format('Y-m-d H') == $currentSlot->format('Y-m-d H');
                                            })->first();
                                        @endphp
                                        <td class="p-1">
                                            @if($appt)
                                                <div class="rounded p-1 small text-left text-white shadow-sm" 
                                                     style="background-color: {{ ($appt->visit_type ?? $appt->type ?? 'followup') == 'evaluation' ? '#fb8c00' : '#00897b' }}; font-size: 0.75rem; cursor: pointer;"
                                                     title="{{ $appt->patient->first_name }} {{ $appt->patient->last_name }} - {{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }}">
                                                    <div class="font-weight-bold text-truncate">{{ $appt->patient->first_name }}</div>
                                                    <div class="text-truncate">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }}</div>
                                                </div>
                                            @else
                                                <div style="height: 40px;"></div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

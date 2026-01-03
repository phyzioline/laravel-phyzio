@extends('web.layouts.dashboard_master')

@section('title', 'My Schedule')
@section('header_title', 'Hourly Schedule')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bold mb-1">{{ __('My Hourly Schedule') }}</h5>
                        <p class="text-muted mb-0">
                            <strong>{{ __('Date') }}:</strong> {{ $selectedDate->format('l, M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <form method="GET" action="{{ route('clinic.doctor.schedule') }}" class="d-inline">
                            <input type="date" name="date" value="{{ $selectedDate->toDateString() }}" 
                                   class="form-control" onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-clock fa-2x text-primary mb-2"></i>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalHoursToday, 1) }}</h3>
                <small class="text-muted">{{ __('Hours Today') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-dollar-sign fa-2x text-success mb-2"></i>
                <h3 class="font-weight-bold mb-0">${{ number_format($totalEarningsToday, 2) }}</h3>
                <small class="text-muted">{{ __('Earnings Today') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-calendar-week fa-2x text-info mb-2"></i>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalHoursThisWeek, 1) }}</h3>
                <small class="text-muted">{{ __('Hours This Week') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-money-bill-wave fa-2x text-warning mb-2"></i>
                <h3 class="font-weight-bold mb-0">${{ number_format($totalEarningsThisWeek, 2) }}</h3>
                <small class="text-muted">{{ __('Earnings This Week') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Hourly Schedule Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">
                    <i class="las la-list-ul"></i> {{ __('Schedule Timeline') }}
                </h5>
                @if($hourlyRate)
                <p class="text-muted small mb-0">
                    {{ __('Hourly Rate') }}: ${{ number_format($hourlyRate->hourly_rate, 2) }}/hour
                </p>
                @endif
            </div>
            <div class="card-body px-4">
                @if(count($hourlySchedule) > 0)
                <div class="timeline">
                    @foreach($hourlySchedule as $item)
                    <div class="timeline-item mb-4 p-3 border rounded" 
                         style="background: {{ $item['type'] === 'intensive' ? '#e8f5e9' : '#fff3e0' }};">
                        <div class="d-flex align-items-start">
                            <div class="time-badge mr-3 text-center" style="min-width: 80px;">
                                <div class="font-weight-bold text-primary">
                                    {{ $item['time']->format('h:i A') }}
                                </div>
                                <div class="small text-muted">
                                    {{ $item['end_time']->format('h:i A') }}
                                </div>
                                <div class="badge badge-sm mt-1 {{ $item['type'] === 'intensive' ? 'badge-success' : 'badge-info' }}">
                                    {{ $item['session_type'] }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="font-weight-bold mb-1">
                                    {{ $item['patient_name'] }}
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted mr-3">
                                        <i class="las la-clock"></i> {{ $item['duration_minutes'] }} {{ __('minutes') }}
                                    </small>
                                    @if($item['type'] === 'intensive')
                                    <small class="text-muted">
                                        <i class="las la-layer-group"></i> {{ __('Slot') }} {{ $item['slot_number'] }}
                                    </small>
                                    @endif
                                </div>
                                @if($item['type'] === 'intensive' && isset($item['appointment']))
                                <div class="alert alert-info small mb-0 py-2">
                                    <strong>{{ __('Intensive Session') }}:</strong> 
                                    {{ $item['appointment']->total_hours }} {{ __('hours total') }} | 
                                    {{ __('This is slot') }} {{ $item['slot_number'] }} {{ __('of') }} {{ $item['appointment']->total_hours }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="las la-calendar-times fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No appointments scheduled') }}</h5>
                    <p class="text-muted">{{ __('You have no appointments for this date.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline-item {
    transition: transform 0.2s, box-shadow 0.2s;
}
.timeline-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.time-badge {
    border-right: 2px solid #e0e0e0;
    padding-right: 15px;
}
</style>
@endpush
@endsection


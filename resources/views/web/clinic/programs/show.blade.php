@extends('web.layouts.dashboard_master')

@section('title', $program->program_name)
@section('header_title', 'Program Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('clinic.programs.index') }}" class="btn btn-secondary mb-3">
            <i class="las la-arrow-left"></i> {{ __('Back to Programs') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Program Information -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Program Information') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Program Name') }}</small>
                    <h5 class="mb-0">{{ $program->program_name }}</h5>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Patient') }}</small>
                    <h6 class="mb-0">{{ $program->patient->first_name ?? 'N/A' }} {{ $program->patient->last_name ?? '' }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Specialty') }}</small>
                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $program->specialty)) }}</span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Status') }}</small>
                    @if($program->status == 'active')
                        <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($program->status == 'completed')
                        <span class="badge badge-primary">{{ __('Completed') }}</span>
                    @elseif($program->status == 'draft')
                        <span class="badge badge-secondary">{{ __('Draft') }}</span>
                    @elseif($program->status == 'cancelled')
                        <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                    @elseif($program->status == 'paused')
                        <span class="badge badge-warning">{{ __('Paused') }}</span>
                    @endif
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Sessions per Week') }}</small>
                    <h6 class="mb-0">{{ $program->sessions_per_week }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Total Weeks') }}</small>
                    <h6 class="mb-0">{{ $program->total_weeks }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Total Sessions') }}</small>
                    <h6 class="mb-0">{{ $program->total_sessions }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Start Date') }}</small>
                    <h6 class="mb-0">{{ $program->start_date->format('M d, Y') }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('End Date') }}</small>
                    <h6 class="mb-0">{{ $program->end_date ? $program->end_date->format('M d, Y') : 'N/A' }}</h6>
                </div>
                
                @if($program->therapist)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Primary Therapist') }}</small>
                    <h6 class="mb-0">{{ $program->therapist->name ?? 'N/A' }}</h6>
                </div>
                @endif
                
                @if($program->status == 'draft')
                <form action="{{ route('clinic.programs.activate', $program->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block" 
                            onclick="return confirm('Are you sure you want to activate this program? Sessions will be auto-booked.')">
                        <i class="las la-play"></i> {{ __('Activate Program') }}
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Progress Card -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Progress') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Completed Sessions') }}</span>
                        <strong>{{ $program->getCompletedSessionsCount() }} / {{ $program->total_sessions }}</strong>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $program->getCompletionPercentage() }}%"
                             aria-valuenow="{{ $program->getCompletionPercentage() }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($program->getCompletionPercentage(), 1) }}%
                        </div>
                    </div>
                </div>
                
                <div class="mb-2">
                    <small class="text-muted d-block">{{ __('Remaining Sessions') }}</small>
                    <h5 class="mb-0">{{ $program->getRemainingSessionsCount() }}</h5>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Payment Information') }}</h5>
            </div>
            <div class="card-body px-4">
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Total Price') }}</small>
                    <h4 class="mb-0 text-primary">${{ number_format($program->total_price, 2) }}</h4>
                </div>
                
                @if($program->discount_percentage > 0)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Discount') }}</small>
                    <span class="badge badge-success">{{ $program->discount_percentage }}% OFF</span>
                </div>
                @endif
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Payment Plan') }}</small>
                    <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $program->payment_plan)) }}</h6>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">{{ __('Paid Amount') }}</small>
                    <h6 class="mb-0">${{ number_format($program->paid_amount, 2) }}</h6>
                </div>
                
                <div class="mb-0">
                    <small class="text-muted d-block">{{ __('Remaining Balance') }}</small>
                    <h5 class="mb-0">${{ number_format($program->remaining_balance, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sessions Calendar -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Program Sessions') }}</h5>
            </div>
            <div class="card-body px-4">
                @if($sessionsByWeek->count() > 0)
                    @foreach($sessionsByWeek as $weekNumber => $sessions)
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-3">
                            <i class="las la-calendar-week"></i> {{ __('Week') }} {{ $weekNumber }}
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>{{ __('Session') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                    <tr>
                                        <td>#{{ $session->session_in_program }}</td>
                                        <td>{{ $session->scheduled_date->format('M d, Y') }}</td>
                                        <td>{{ $session->scheduled_time ? \Carbon\Carbon::parse($session->scheduled_time)->format('h:i A') : 'TBD' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst(str_replace('_', ' ', $session->session_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($session->status == 'completed')
                                                <span class="badge badge-success">{{ __('Completed') }}</span>
                                            @elseif($session->status == 'booked')
                                                <span class="badge badge-primary">{{ __('Booked') }}</span>
                                            @elseif($session->status == 'scheduled')
                                                <span class="badge badge-secondary">{{ __('Scheduled') }}</span>
                                            @elseif($session->status == 'cancelled')
                                                <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                                            @elseif($session->status == 'no_show')
                                                <span class="badge badge-warning">{{ __('No Show') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($session->appointment)
                                                <a href="{{ route('clinic.appointments.index') }}" class="btn btn-sm btn-info">
                                                    <i class="las la-eye"></i> {{ __('View Appointment') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="las la-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No sessions found') }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        @if($program->goals || $program->notes)
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Goals & Notes') }}</h5>
            </div>
            <div class="card-body px-4">
                @if($program->goals)
                <div class="mb-3">
                    <small class="text-muted d-block mb-2">{{ __('Program Goals') }}</small>
                    <p class="mb-0">{{ $program->goals }}</p>
                </div>
                @endif
                
                @if($program->notes)
                <div>
                    <small class="text-muted d-block mb-2">{{ __('Notes') }}</small>
                    <p class="mb-0">{{ $program->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


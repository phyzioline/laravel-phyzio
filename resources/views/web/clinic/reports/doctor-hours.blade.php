@extends('web.layouts.dashboard_master')

@section('title', 'Doctor Hours Report')
@section('header_title', 'Doctor Hours & Payroll Report')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">{{ __('Doctor Hours Report') }}</h5>
                    <div>
                        <a href="{{ route('clinic.reports.doctorHours.export', array_merge(request()->all(), ['format' => 'csv'])) }}" 
                           class="btn btn-sm btn-success">
                            <i class="las la-file-excel"></i> {{ __('Export CSV') }}
                        </a>
                        <a href="{{ route('clinic.reports.doctorHours.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" 
                           class="btn btn-sm btn-danger">
                            <i class="las la-file-pdf"></i> {{ __('Export PDF') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-4">
                <!-- Filters -->
                <form method="GET" action="{{ route('clinic.reports.doctorHours') }}" class="row mb-4">
                    <div class="col-md-3">
                        <label>{{ __('Period') }}</label>
                        <select name="period" class="form-control" onchange="this.form.submit()">
                            <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                            <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                            <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('Date') }}</label>
                        <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" 
                               class="form-control" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('Doctor') }}</label>
                        <select name="doctor_id" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ __('All Doctors') }}</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Filter') }}</button>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                            <div class="card-body">
                                <i class="las la-clock fa-2x text-primary mb-2"></i>
                                <h3 class="font-weight-bold mb-0">{{ number_format($totalHours, 1) }}</h3>
                                <small class="text-muted">{{ __('Total Hours') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                            <div class="card-body">
                                <i class="las la-dollar-sign fa-2x text-success mb-2"></i>
                                <h3 class="font-weight-bold mb-0">${{ number_format($totalEarnings, 2) }}</h3>
                                <small class="text-muted">{{ __('Total Earnings') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
                            <div class="card-body">
                                <i class="las la-calendar-check fa-2x text-info mb-2"></i>
                                <h3 class="font-weight-bold mb-0">{{ $totalSessions }}</h3>
                                <small class="text-muted">{{ __('Total Sessions') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary by Doctor -->
                @if($summaryByDoctor->count() > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="font-weight-bold mb-0">{{ __('Summary by Doctor') }}</h5>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Doctor') }}</th>
                                        <th>{{ __('Sessions') }}</th>
                                        <th>{{ __('Total Hours') }}</th>
                                        <th>{{ __('Avg Rate/Hour') }}</th>
                                        <th>{{ __('Total Earnings') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summaryByDoctor as $summary)
                                    <tr>
                                        <td><strong>{{ $summary['doctor']->name }}</strong></td>
                                        <td>{{ $summary['session_count'] }}</td>
                                        <td>{{ number_format($summary['total_hours'], 1) }} {{ __('hours') }}</td>
                                        <td>${{ number_format($summary['avg_hourly_rate'], 2) }}</td>
                                        <td><strong class="text-success">${{ number_format($summary['total_earnings'], 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Detailed Logs -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="font-weight-bold mb-0">{{ __('Detailed Work Logs') }}</h5>
                        <p class="text-muted small mb-0">
                            {{ __('Period') }}: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="card-body px-4">
                        @if($workLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Doctor') }}</th>
                                        <th>{{ __('Patient') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Hours') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Type') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workLogs as $log)
                                    <tr>
                                        <td>{{ $log->work_date->format('M d, Y') }}</td>
                                        <td>{{ $log->doctor->name ?? 'N/A' }}</td>
                                        <td>{{ $log->patient->first_name ?? '' }} {{ $log->patient->last_name ?? '' }}</td>
                                        <td>
                                            {{ $log->start_time->format('h:i A') }} - 
                                            {{ $log->end_time->format('h:i A') }}
                                        </td>
                                        <td>{{ number_format($log->hours_worked, 2) }}</td>
                                        <td>${{ number_format($log->hourly_rate, 2) }}</td>
                                        <td><strong>${{ number_format($log->total_amount, 2) }}</strong></td>
                                        <td>
                                            <span class="badge {{ $log->session_type === 'intensive' ? 'badge-success' : 'badge-info' }}">
                                                {{ ucfirst($log->session_type ?? 'regular') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="las la-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No work logs found') }}</h5>
                            <p class="text-muted">{{ __('No doctor hours recorded for the selected period.') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


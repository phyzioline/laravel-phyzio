@extends('web.layouts.dashboard_master')

@section('title', 'Weekly Programs')
@section('header_title', 'Treatment Programs Management')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 style="color: #00897b; font-weight: 700;">{{ __('Weekly Treatment Programs') }}</h3>
            <p class="text-muted mb-0">{{ __('Manage structured treatment programs for patients') }}</p>
        </div>
        <a href="{{ route('clinic.programs.create') }}" class="btn btn-primary">
            <i class="las la-plus-circle"></i> {{ __('Create New Program') }}
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('clinic.programs.index') }}" class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>{{ __('Paused') }}</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('Specialty') }}</label>
                <select name="specialty" class="form-control">
                    <option value="">{{ __('All Specialties') }}</option>
                    @foreach(['orthopedic', 'pediatric', 'neurological', 'sports', 'geriatric', 'womens_health', 'cardiorespiratory', 'home_care'] as $spec)
                        <option value="{{ $spec }}" {{ request('specialty') == $spec ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $spec)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('Patient') }}</label>
                <select name="patient_id" class="form-control">
                    <option value="">{{ __('All Patients') }}</option>
                    <!-- Populate with patients -->
                </select>
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="las la-filter"></i> {{ __('Filter') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card h-100">
            <div class="icon-box icon-primary">
                <i class="las la-clipboard-list"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $programs->total() }}</h3>
                <small class="text-muted">{{ __('Total Programs') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card h-100">
            <div class="icon-box icon-success">
                <i class="las la-play-circle"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $programs->where('status', 'active')->count() }}</h3>
                <small class="text-muted">{{ __('Active Programs') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card h-100">
            <div class="icon-box icon-info">
                <i class="las la-check-circle"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $programs->where('status', 'completed')->count() }}</h3>
                <small class="text-muted">{{ __('Completed') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card h-100">
            <div class="icon-box icon-warning">
                <i class="las la-file-alt"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $programs->where('status', 'draft')->count() }}</h3>
                <small class="text-muted">{{ __('Draft') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Programs Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="card-title font-weight-bold mb-0">{{ __('Programs List') }}</h5>
    </div>
    <div class="card-body px-4">
        @if($programs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Program Name') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Specialty') }}</th>
                        <th>{{ __('Sessions') }}</th>
                        <th>{{ __('Progress') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr>
                        <td>
                            <strong>{{ $program->program_name }}</strong>
                            <br>
                            <small class="text-muted">{{ $program->sessions_per_week }}x/week × {{ $program->total_weeks }} weeks</small>
                        </td>
                        <td>
                            {{ $program->patient->first_name ?? 'N/A' }} {{ $program->patient->last_name ?? '' }}
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $program->specialty)) }}</span>
                        </td>
                        <td>
                            {{ $program->getCompletedSessionsCount() }} / {{ $program->total_sessions }}
                        </td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $program->getCompletionPercentage() }}%"
                                     aria-valuenow="{{ $program->getCompletionPercentage() }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($program->getCompletionPercentage(), 0) }}%
                                </div>
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td>{{ $program->start_date->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('clinic.programs.show', $program->id) }}" 
                                   class="btn btn-sm btn-info" title="{{ __('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                @if($program->status == 'draft')
                                <form action="{{ route('clinic.programs.activate', $program->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            title="{{ __('Activate') }}"
                                            onclick="return confirm('Are you sure you want to activate this program? Sessions will be auto-booked.')">
                                        <i class="las la-play"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $programs->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="las la-clipboard-list fa-3x text-muted mb-3"></i>
            <p class="text-muted">{{ __('No programs found') }}</p>
            <a href="{{ route('clinic.programs.create') }}" class="btn btn-primary">
                <i class="las la-plus-circle"></i> {{ __('Create Your First Program') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection


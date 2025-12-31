@extends('web.layouts.dashboard_master')

@section('title', __('Company Dashboard'))
@section('header_title', __('Company Dashboard'))

@push('styles')
<style>
    .stat-card-premium {
        border: none;
        border-radius: 12px;
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .stat-card-premium .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .bg-light-primary { background-color: rgba(0, 137, 123, 0.1); color: #00897b; }
    .bg-light-success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .bg-light-info { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }

    .action-tile {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #eee;
        transition: 0.2s;
        text-decoration: none !important;
        color: #333;
    }
    .action-tile:hover {
        border-color: #00897b;
        background: rgba(0, 137, 123, 0.02);
        color: #00897b;
    }
    .action-tile i {
        font-size: 20px;
        margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;
        color: #00897b;
    }
    
    .table-premium thead th {
        background-color: #f8f9fa;
        border-bottom: none;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table-premium tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
        padding: 15px 12px;
        font-weight: 500;
        color: #333;
    }
    .table-premium tr:last-child td {
        border-bottom: none;
    }
    .card-title-premium {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #2c3e50;
    }
</style>
@endpush

@section('content')

{{-- Verification Alert --}}
@if(Auth::user()->status !== 'active' || Auth::user()->verification_status !== 'approved')
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert" style="border-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 5px solid #ffc107;">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <i class="las la-exclamation-triangle fa-2x {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}" style="color: #ffc107;"></i>
                    <div>
                        <h5 class="alert-heading font-weight-bold mb-1">{{ __('Account Verification Required') }}</h5>
                        <p class="mb-0 text-muted">
                            {{ __('Your account is currently') }} <strong>{{ ucfirst(Auth::user()->verification_status ?? 'pending') }}</strong>. 
                            {{ __('You must complete the verification process to post jobs and search for candidates.') }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('verification.verification-center.' . app()->getLocale()) }}" class="btn btn-warning font-weight-bold text-dark mt-2 mt-md-0 shadow-sm">
                    <i class="las la-file-upload"></i> {{ __('Complete Verification') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Statistics Row --}}
<div class="row">
    <!-- Total Jobs -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-premium shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="letter-spacing: 0.5px;">
                            {{ __('Total Jobs') }}
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $totalJobs }}</div>
                    </div>
                    <div class="icon-circle bg-light-primary">
                        <i class="las la-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Jobs -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-premium shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="letter-spacing: 0.5px;">
                            {{ __('Active Jobs') }}
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $activeJobs }}</div>
                    </div>
                    <div class="icon-circle bg-light-success">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Applications -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-premium shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="letter-spacing: 0.5px;">
                            {{ __('Total Applications') }}
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $totalApplications }}</div>
                    </div>
                    <div class="icon-circle bg-light-info">
                        <i class="las la-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card-premium shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="letter-spacing: 0.5px;">
                            {{ __('Pending Reviews') }}
                        </div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $pendingApplications }}</div>
                    </div>
                    <div class="icon-circle bg-light-warning">
                        <i class="las la-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="card-title-premium mb-3">{{ __('Quick Actions') }}</h5>
                <div class="d-flex flex-wrap" style="gap: 15px;">
                    <a href="{{ route('company.jobs.create') }}" class="action-tile shadow-sm">
                        <i class="las la-plus-circle"></i>
                        <span class="font-weight-bold">{{ __('Post New Job') }}</span>
                    </a>
                    <a href="{{ route('company.jobs.index') }}" class="action-tile shadow-sm">
                        <i class="las la-briefcase"></i>
                        <span class="font-weight-bold">{{ __('Manage Jobs') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Reviews / Recent Jobs Grid -->
<div class="row">
    <!-- Recent Jobs -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title-premium mb-0 text-primary">{{ __('Recent Jobs') }}</h5>
                @if($recentJobs->count() > 0)
                <a href="{{ route('company.jobs.index') }}" class="btn btn-sm btn-light text-primary font-weight-bold">{{ __('View All') }}</a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($recentJobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-premium table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Applicants') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentJobs as $job)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $job->title }}</div>
                                    <small class="text-muted">{{ $job->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    @if($job->applications_count > 0)
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-primary rounded-circle p-2 {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}" style="min-width: 25px;">{{ $job->applications_count }}</span>
                                            <small>{{ __('Applicants') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted small">0 {{ __('Applicants') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($job->is_active)
                                        <span class="badge badge-pill badge-light-success text-success px-3 py-1">{{ __('Active') ?? 'Active' }}</span>
                                    @else
                                        <span class="badge badge-pill badge-light-secondary text-secondary px-3 py-1">{{ __('Inactive') ?? 'Inactive' }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('company.jobs.applicants', $job->id) }}" class="btn btn-sm btn-light text-primary" data-toggle="tooltip" title="{{ __('View Details') }}">
                                        <i class="las la-eye" style="font-size: 18px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <img src="{{ asset('web/assets/images/no-data.svg') }}" alt="No Jobs" style="max-width: 120px; opacity: 0.5;" class="mb-3">
                    <p class="text-muted mb-3">{{ __('No jobs posted yet.') }}</p>
                    <a href="{{ route('company.jobs.create') }}" class="btn btn-primary shadow-sm">{{ __('Post your first job') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title-premium mb-0 text-primary">{{ __('Recent Applications') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($recentApplications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-premium table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Therapist') }}</th>
                                <th>{{ __('Match Score') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $app)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                         <div class="avatar-circle {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}" style="width: 35px; height: 35px; background: #e0f2f1; color: #00897b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                            {{ substr($app->therapist->name ?? 'T', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $app->therapist->name ?? __('Unknown') }}</div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 150px;">{{ $app->job->title }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $score = $app->match_score ?? 0;
                                        $color = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress" style="height: 6px; width: 80px; border-radius: 10px;">
                                        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $score }}%" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-{{ $color }} font-weight-bold mt-1 d-block">{{ $score }}%</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('company.jobs.applicants', $app->job->id) }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold">
                                        {{ __('Review') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <p class="text-muted">{{ __('No applications yet.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endpush


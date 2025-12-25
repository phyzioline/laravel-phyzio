@extends('web.layouts.dashboard_master')

@section('title', 'Company Dashboard')
@section('header_title', 'Company Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jobs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJobs }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="las la-briefcase fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Jobs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeJobs }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="las la-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Applications</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalApplications }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="las la-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Reviews</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingApplications }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="las la-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('company.jobs.create') }}" class="btn btn-primary">
                        <i class="las la-plus"></i> Post New Job
                    </a>
                    <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-primary">
                        <i class="las la-briefcase"></i> Manage Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Jobs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Jobs</h5>
            </div>
            <div class="card-body">
                @if($recentJobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Applicants</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentJobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $job->type == 'job' ? 'success' : 'info' }}">
                                        {{ ucfirst($job->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($job->applications_count > 0)
                                        <a href="{{ route('company.jobs.applicants', $job->id) }}" class="badge badge-primary">
                                            {{ $job->applications_count }} Applicants
                                        </a>
                                    @else
                                        <span class="badge badge-secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $job->is_active ? 'success' : 'secondary' }}">
                                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('company.jobs.applicants', $job->id) }}" class="btn btn-sm btn-info">
                                        <i class="las la-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-4">No jobs posted yet. <a href="{{ route('company.jobs.create') }}">Post your first job</a></p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
@if($recentApplications->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Applications</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Therapist</th>
                                <th>Job Title</th>
                                <th>Match Score</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplications as $app)
                            <tr>
                                <td>{{ $app->therapist->name ?? 'N/A' }}</td>
                                <td>{{ $app->job->title }}</td>
                                <td>
                                    @if($app->match_score >= 80)
                                        <span class="badge badge-success">{{ $app->match_score }}%</span>
                                    @elseif($app->match_score >= 50)
                                        <span class="badge badge-warning">{{ $app->match_score }}%</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $app->match_score ?? 'N/A' }}%</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ ucfirst($app->status) }}</span>
                                </td>
                                <td>{{ $app->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('company.jobs.applicants', $app->job->id) }}" class="btn btn-sm btn-info">
                                        <i class="las la-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection


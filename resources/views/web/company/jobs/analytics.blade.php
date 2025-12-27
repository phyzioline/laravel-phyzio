@extends('web.layouts.dashboard_master')

@section('title', 'Job Analytics')
@section('header_title', 'Job Analytics')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Jobs</h6>
                <h3 class="mb-0">{{ $totalJobs }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-2">Active Jobs</h6>
                <h3 class="mb-0">{{ $activeJobs }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Applications</h6>
                <h3 class="mb-0">{{ $totalApplications }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-2">Hired</h6>
                <h3 class="mb-0">{{ $applicationsByStatus['hired'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Applications by Status</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Jobs by Applications</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($topJobs as $job)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $job->title }}</h6>
                                <small class="text-muted">{{ $job->applications_count }} applications</small>
                            </div>
                            <a href="{{ route('company.jobs.applicants', $job->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3">No jobs with applications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($applicationsByStatus);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection


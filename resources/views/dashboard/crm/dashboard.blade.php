@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* AI & Modules Pages - Full Width (100%) ignoring header and sidebar */
    .page-wrapper {
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-top: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .main-content {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Content area should account for header and sidebar */
    .ai-modules-content {
        margin-left: 250px; /* Sidebar width */
        margin-top: 70px; /* Header height */
        padding: 20px;
        width: calc(100% - 250px);
        min-height: calc(100vh - 70px);
    }
    
    /* RTL Support */
    [dir="rtl"] .ai-modules-content {
        margin-left: 0;
        margin-right: 250px;
    }
    
    /* When sidebar is toggled */
    body.toggled .ai-modules-content {
        margin-left: 0;
        width: 100%;
    }
    
    [dir="rtl"] body.toggled .ai-modules-content {
        margin-right: 0;
    }
</style>
@endpush

@section('content')
<div class="ai-modules-content">
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-uppercase fw-bold text-secondary">CRM Dashboard</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export Report</button>
                <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Contact</button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Contacts</p>
                            <h4 class="my-1 text-info">{{ number_format($totalUsers) }}</h4>
                            <p class="mb-0 font-13">+{{ $newUsers }} new (30 days)</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Form Submissions</p>
                            <h4 class="my-1 text-danger">{{ number_format($feedbackCount) }}</h4>
                            <p class="mb-0 font-13">Recent activity</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class="bi bi-chat-left-text-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Active Users</p>
                            <h4 class="my-1 text-success">{{ number_format($activeUsers) }}</h4>
                            <p class="mb-0 font-13">Currently active</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Visitor Sessions</p>
                            <h4 class="my-1 text-warning">{{ number_format($totalSessions) }}</h4>
                            <p class="mb-0 font-13">System traffic</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Lists -->
    <div class="row">
        <!-- New Members List -->
        <div class="col-12 col-lg-8">
            <div class="card radius-10">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Contacts Most Recently Created</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newMembers as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="recent-product-img">
                                                <img src="{{ $member->profile_photo_url }}" alt="user">
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $member->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($member->type) }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.users.show', $member->id) }}" class="btn btn-sm btn-light border">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent members found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Feedback -->
            <div class="card radius-10 mt-4">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Recent Form Submissions</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentFeedbacks as $feedback)
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h6 class="mb-0">{{ $feedback->subject }}</h6>
                                <small class="text-secondary">{{ $feedback->first_name }} {{ $feedback->last_name }} ({{ $feedback->email }})</small>
                                <p class="mb-0 text-muted small">{{ Str::limit($feedback->message, 80) }}</p>
                            </div>
                            <span class="badge bg-light text-dark">{{ $feedback->created_at->diffForHumans() }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center">No submissions yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- User Distribution Chart -->
        <div class="col-12 col-lg-4">
            <div class="card radius-10">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Contact Distribution</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container-2">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                    <div class="text-center mt-3">
                        <p class="mb-0 font-13 text-secondary">Breakdown by User Type</p>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($userDistribution as $type => $count)
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        {{ ucfirst($type) }} <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // User Distribution Pie Chart
        const ctx = document.getElementById('userDistributionChart').getContext('2d');
        const labels = @json(array_keys($userDistribution->toArray()));
        const data = @json(array_values($userDistribution->toArray()));
        
        // Colors generator
        const colors = ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107', '#198754'];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
</div>
</div>

<style>
    .widgets-icons-2 {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ededed;
        font-size: 27px;
        border-radius: 10px;
    }
    .bg-gradient-scooter {
        background: #17ead9;
        background: -webkit-linear-gradient(45deg, #17ead9, #6078ea);
        background: linear-gradient(45deg, #17ead9, #6078ea);
    }
    .bg-gradient-bloody {
        background: #f54ea2;
        background: -webkit-linear-gradient(45deg, #f54ea2, #ff7676);
        background: linear-gradient(45deg, #f54ea2, #ff7676);
    }
    .bg-gradient-ohhappiness {
        background: #00b09b;
        background: -webkit-linear-gradient(45deg, #00b09b, #96c93d);
        background: linear-gradient(45deg, #00b09b, #96c93d);
    }
    .bg-gradient-blooker {
        background: #ffdf40;
        background: -webkit-linear-gradient(45deg, #ffdf40, #ff8359);
        background: linear-gradient(45deg, #ffdf40, #ff8359);
    }
    .chart-container-2 {
        position: relative;
        height: 300px;
    }
</style>
@endsection

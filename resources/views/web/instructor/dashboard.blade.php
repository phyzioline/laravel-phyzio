@extends('web.layouts.dashboard_master')

@section('title', 'Instructor Dashboard')
@section('header_title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #00897b; font-weight: 700;">{{ __('Welcome back,') }} {{ Auth::user()->name }}</h3>
        <p class="text-muted">{{ __('Here is your instructor overview.') }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <!-- Active Courses -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon-box icon-teal">
                <i class="las la-book-open"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $totalCourses }}</h3>
                <small class="text-muted">{{ __('Active Courses') }}</small>
                @if($newCoursesThisMonth > 0)
                    <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ $newCoursesThisMonth }} {{ __('new this month') }}</div>
                @else
                    <div class="text-muted small mt-1">{{ __('No new courses this month') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- New Enrollments -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon-box icon-green">
                <i class="las la-calendar-check"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $newEnrollments }}</h3>
                <small class="text-muted">{{ __('New Enrollments') }}</small>
                @if($pendingEnrollments > 0)
                    <div class="text-info small mt-1"><i class="las la-circle"></i> {{ $pendingEnrollments }} {{ __('pending') }}</div>
                @else
                    <div class="text-muted small mt-1">{{ __('This month') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon-box icon-blue">
                <i class="las la-user-graduate"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ number_format($activeStudents) }}</h3>
                <small class="text-muted">{{ __('Total Students') }}</small>
                @if($studentsChange != 0)
                    <div class="text-{{ $studentsChange > 0 ? 'success' : 'danger' }} small mt-1">
                        <i class="las la-arrow-{{ $studentsChange > 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($studentsChange) }}% {{ __('from last month') }}
                    </div>
                @else
                    <div class="text-muted small mt-1">{{ __('No change from last month') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="icon-box icon-orange">
                <i class="las la-dollar-sign"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">${{ number_format($totalRevenue) }}</h3>
                <small class="text-muted">{{ __('Monthly Revenue') }}</small>
                @if($revenueChange != 0)
                    <div class="text-{{ $revenueChange > 0 ? 'success' : 'danger' }} small mt-1">
                        <i class="las la-arrow-{{ $revenueChange > 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($revenueChange) }}% {{ __('from last month') }}
                    </div>
                @else
                    <div class="text-muted small mt-1">{{ __('No change from last month') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Chart Section -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h5 class="card-title font-weight-bold mb-4" style="color: #333;">{{ __('Enrollments Overview') }}</h5>
                <!-- Chart Placeholder -->
                <canvas id="enrollmentChart" height="150"></canvas>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="card-title font-weight-bold mb-0">{{ __('Recent Activities') }}</h5>
                     <button class="btn btn-sm btn-outline-primary">{{ __('View All') }}</button>
                </div>
               
                <div class="list-group list-group-flush">
                    @foreach($activities as $activity)
                    <div class="list-group-item px-0 d-flex align-items-start border-0">
                        <div class="icon-box icon-teal mr-3" style="width: 40px; height: 40px; font-size: 20px;">
                            <i class="las la-bell"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">{{ $activity['title'] }}</h6>
                            <p class="mb-0 text-muted small">{{ $activity['desc'] }}</p>
                        </div>
                        <small class="text-muted">{{ $activity['time'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side Widgets -->
    <div class="col-lg-4">
        <!-- Top performing Courses -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
             <div class="card-body">
                <h5 class="card-title font-weight-bold mb-4">{{ __('Top Courses') }}</h5>
                
                @forelse($topCourses as $course)
                    <div class="mb-3 p-3 bg-light rounded d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded mr-3 text-primary">
                                <i class="las la-book"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold">{{ Str::limit($course->title, 25) }}</h6>
                                <small class="text-muted">{{ $course->enrollments_count }} {{ __('Students') }}</small>
                            </div>
                        </div>
                        <span class="badge badge-success bg-success text-white px-2 py-1">{{ __('Active') }}</span>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="las la-book-open fa-2x text-muted mb-2"></i>
                        <p class="text-muted small">{{ __('No courses yet') }}</p>
                    </div>
                @endforelse
             </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('enrollmentChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']),
            datasets: [{
                label: 'Scheduled',
                data: @json($chartData['scheduled'] ?? [0, 0, 0, 0, 0, 0, 0]),
                borderColor: '#00897b',
                backgroundColor: 'rgba(0, 137, 123, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            },
            {
                label: 'Completed',
                data: @json($chartData['completed'] ?? [0, 0, 0, 0, 0, 0, 0]),
                borderColor: '#43a047',
                borderWidth: 2,
                tension: 0.4,
                borderDash: [5, 5],
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush

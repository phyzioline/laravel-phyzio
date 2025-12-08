@extends('web.layouts.app')

@section('title', __('Instructor Dashboard'))

@section('content')
<div class="instructor-dashboard-wrapper bg-light py-4">
    <div class="container">
        <!-- Actions & Welcome -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="font-weight-bold text-dark">{{ __('Welcome back,') }} {{ Auth::user()->name }}</h2>
                <p class="text-muted">{{ __('Here is an overview of your teaching activity.') }}</p>
            </div>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-teal btn-lg text-white shadow-sm rounded-pill px-4" style="background-color: #0d9488;">
                <i class="las la-plus-circle"></i> {{ __('Create New Course') }}
            </a>
        </div>

        <!-- 1. Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-wrapper p-3 rounded-circle mr-3" style="background-color: #e0f2f1; color: #00695c;">
                            <i class="las la-wallet font-weight-bold" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">{{ __('Total Revenue') }}</p>
                            <h3 class="font-weight-bold mb-0 text-teal-700" style="color: #0d9488;">$1,250</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-wrapper p-3 rounded-circle mr-3" style="background-color: #e3f2fd; color: #1565c0;">
                            <i class="las la-user-graduate font-weight-bold" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">{{ __('Total Students') }}</p>
                            <h3 class="font-weight-bold mb-0">128</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-wrapper p-3 rounded-circle mr-3" style="background-color: #fff3e0; color: #ff9800;">
                            <i class="las la-play-circle font-weight-bold" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">{{ __('Active Courses') }}</p>
                            <h3 class="font-weight-bold mb-0">{{ $courses->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="icon-wrapper p-3 rounded-circle mr-3" style="background-color: #fce4ec; color: #c2185b;">
                            <i class="las la-star font-weight-bold" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">{{ __('Overall Rating') }}</p>
                            <h3 class="font-weight-bold mb-0">4.9</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Revenue Chart (Mock Data) & Recent Activity -->
        <div class="row mb-5">
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-4">{{ __('Revenue Analytics') }}</h5>
                        <div style="height: 300px; width: 100%;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-4">{{ __('Recent Notifications') }}</h5>
                        <ul class="list-unstyled">
                            <li class="media mb-3">
                                <div class="mr-3 rounded-circle bg-light p-2"><i class="las la-user-plus text-success"></i></div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1 font-weight-bold">{{ __('New Student Enrolled') }}</h6>
                                    <p class="text-muted small mb-0">Ahmed Ali enrolled in "Orthopedics 101"</p>
                                    <small class="text-muted">2 mins ago</small>
                                </div>
                            </li>
                            <li class="media mb-3">
                                <div class="mr-3 rounded-circle bg-light p-2"><i class="las la-comment text-info"></i></div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1 font-weight-bold">{{ __('New Comment') }}</h6>
                                    <p class="text-muted small mb-0">Sarah asked a question in Module 3</p>
                                    <small class="text-muted">1 hour ago</small>
                                </div>
                            </li>
                            <li class="media">
                                <div class="mr-3 rounded-circle bg-light p-2"><i class="las la-money-bill-wave text-warning"></i></div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1 font-weight-bold">{{ __('Payout Processed') }}</h6>
                                    <p class="text-muted small mb-0">$500 sent to your bank account</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </li>
                        </ul>
                         <a href="#" class="btn btn-link text-teal-700 p-0 mt-2" style="color: #0d9488;">{{ __('View All Notifications') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Your Courses List -->
        <h4 class="font-weight-bold mb-3">{{ __('Your Courses') }}</h4>
        <div class="table-responsive bg-white shadow-sm rounded">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">{{ __('Course') }}</th>
                        <th class="border-0">{{ __('Status') }}</th>
                        <th class="border-0">{{ __('Enrollments') }}</th>
                        <th class="border-0 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('web/assets/images/course-placeholder.jpg') }}" alt="" class="rounded mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 font-weight-bold">{{ $course->title }}</h6>
                                    <small class="text-muted">{{ $course->category->name ?? 'Category' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @if($course->status == 'published')
                                <span class="badge badge-success px-3 py-2">{{ __('Published') }}</span>
                            @elseif($course->status == 'draft')
                                <span class="badge badge-secondary px-3 py-2">{{ __('Draft') }}</span>
                            @else
                                <span class="badge badge-warning px-3 py-2">{{ __('Under Review') }}</span>
                            @endif
                        </td>
                        <td class="align-middle">{{ $course->enrollments_count ?? 0 }}</td>
                        <td class="align-middle text-right">
                            <a href="#" class="btn btn-sm btn-outline-secondary mr-2"><i class="las la-chart-bar"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="las la-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <h5 class="text-muted">{{ __('No courses found.') }}</h5>
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-teal btn-sm text-white" style="background-color: #0d9488;">{{ __('Create Your First Course') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: '{{ __('Monthly Revenue ($)') }}',
                    data: [150, 300, 250, 600, 800, 1250],
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0d9488',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) { return '$' + value; }
                        }
                    }]
                },
                legend: {
                    display: false
                }
            }
        });
    });
</script>
<style>
    .btn-teal:hover { background-color: #0f766e !important; }
    .text-teal-700 { color: #0d9488 !important; }
</style>
@endpush
@endsection

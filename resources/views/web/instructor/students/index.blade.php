@extends('web.layouts.dashboard_master')

@section('title', 'My Students')
@section('header_title', 'My Students')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h3 style="color: #00897b; font-weight: 700;">{{ __('My Students') }}</h3>
        <p class="text-muted">{{ __('View and manage all students enrolled in your courses') }}</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon-box icon-blue">
                <i class="las la-user-graduate"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalStudents) }}</h3>
                <small class="text-muted">{{ __('Total Students') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon-box icon-green">
                <i class="las la-check-circle"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ number_format($activeEnrollments) }}</h3>
                <small class="text-muted">{{ __('Active Enrollments') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon-box icon-teal">
                <i class="las la-certificate"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ number_format($completedEnrollments) }}</h3>
                <small class="text-muted">{{ __('Completed') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="icon-box icon-orange">
                <i class="las la-book"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0">{{ $courses->count() }}</h3>
                <small class="text-muted">{{ __('Total Courses') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('instructor.students.index') }}" class="row align-items-end">
            <div class="col-md-4 mb-3">
                <label class="form-label">{{ __('Search Student') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('Name or Email') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('Filter by Course') }}</label>
                <select name="course_id" class="form-control">
                    <option value="">{{ __('All Courses') }}</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('Filter by Status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Students Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body">
        @if($enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Course') }}</th>
                            <th>{{ __('Enrolled Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Amount Paid') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($enrollment->student->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $enrollment->student->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $enrollment->student->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $enrollment->course->title ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'completed' => 'primary',
                                            'cancelled' => 'danger',
                                            'refunded' => 'warning'
                                        ];
                                        $color = $statusColors[$enrollment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $color }}">{{ ucfirst($enrollment->status ?? 'active') }}</span>
                                </td>
                                <td>
                                    <strong>${{ number_format($enrollment->paid_amount ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    <a href="{{ route('instructor.students.show', $enrollment->student->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="las la-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $enrollments->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="las la-user-graduate fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No Students Found') }}</h5>
                <p class="text-muted">{{ __('No students have enrolled in your courses yet.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection


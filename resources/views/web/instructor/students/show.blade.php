@extends('web.layouts.dashboard_master')

@section('title', 'Student Details')
@section('header_title', 'Student Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('instructor.students.index') }}" class="btn btn-light mb-3">
            <i class="las la-arrow-left"></i> {{ __('Back to Students') }}
        </a>
    </div>
</div>

<div class="row">
    <!-- Student Info Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                    {{ strtoupper(substr($student->name ?? 'U', 0, 1)) }}
                </div>
                <h4 class="font-weight-bold mb-1">{{ $student->name ?? 'N/A' }}</h4>
                <p class="text-muted mb-3">{{ $student->email ?? 'N/A' }}</p>
                
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-0">{{ $totalCourses }}</h5>
                        <small class="text-muted">{{ __('Courses') }}</small>
                    </div>
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-0">{{ $completedCourses }}</h5>
                        <small class="text-muted">{{ __('Completed') }}</small>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <h5 class="font-weight-bold text-primary mb-0">${{ number_format($totalSpent, 2) }}</h5>
                    <small class="text-muted">{{ __('Total Spent') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enrollments List -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Course Enrollments') }}</h5>
            </div>
            <div class="card-body">
                @if($enrollments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($enrollments as $enrollment)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="font-weight-bold mb-1">{{ $enrollment->course->title ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            <i class="las la-calendar"></i> 
                                            {{ __('Enrolled') }}: {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}
                                        </small>
                                        @if($enrollment->completed_at)
                                            <br>
                                            <small class="text-muted">
                                                <i class="las la-check-circle"></i> 
                                                {{ __('Completed') }}: {{ \Carbon\Carbon::parse($enrollment->completed_at)->format('M d, Y') }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'completed' => 'primary',
                                                'cancelled' => 'danger',
                                                'refunded' => 'warning'
                                            ];
                                            $color = $statusColors[$enrollment->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $color }} mb-2">{{ ucfirst($enrollment->status ?? 'active') }}</span>
                                        <br>
                                        <strong class="text-primary">${{ number_format($enrollment->paid_amount ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="las la-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('No enrollments found.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


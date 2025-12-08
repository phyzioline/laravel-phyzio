@extends('web.therapist.layout')

@section('header_title', 'Instructor Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>My Courses</h2>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary"><i class="las la-plus"></i> Create New Course</a>
        </div>
    </div>
</div>

<div class="row">
    @forelse($courses as $course)
        <div class="col-md-6 mb-3">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="font-weight-bold mb-2">{{ $course->title }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($course->description, 80) }}</p>
                        <span class="badge badge-{{ $course->status === 'published' ? 'success' : 'warning' }}">{{ ucfirst($course->status) }}</span>
                    </div>
                    <div class="text-right">
                        <h4 class="text-success mb-1">${{ number_format($course->price, 2) }}</h4>
                        <p class="text-muted small">{{ $course->enrollments_count }} students</p>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="las la-chalkboard-teacher display-1 text-muted"></i>
            <p class="lead text-muted">You haven't created any courses yet.</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary btn-lg mt-3">Create Your First Course</a>
        </div>
    @endforelse
</div>
@endsection

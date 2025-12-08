@extends('web.layouts.app')

@section('title', 'Learning Hub - Courses')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 300px; display: flex; align-items: center; text-align: center; color: white;">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Learning Hub</h1>
        <p class="lead">Advance your career with professional courses from expert instructors.</p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            @forelse($courses as $course)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100" style="transition: transform 0.3s;">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                        @else
                            <div class="bg-primary" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-graduation-cap text-white" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold">{{ $course->title }}</h5>
                            <p class="text-muted small">By {{ $course->instructor->name }}</p>
                            <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h4 text-success mb-0">${{ number_format($course->price, 2) }}</span>
                                <a href="{{ route('web.courses.show', $course->id) }}" class="btn btn-primary btn-sm">View Course</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="las la-book-open display-1 text-muted"></i>
                    <p class="lead text-muted">No courses available yet. Check back soon!</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>
</section>

<style>
    .card:hover { transform: translateY(-5px); }
</style>
@endsection

@extends('web.layouts.app')

@section('title', $course->title . ' - Course Details')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow border-0 mb-4">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                    @endif
                    <div class="card-body p-4">
                        <h1 class="display-5 font-weight-bold mb-3">{{ $course->title }}</h1>
                        <p class="text-muted mb-4"><i class="las la-user-tie"></i> Instructor: <strong>{{ $course->instructor->name }}</strong></p>
                        <p class="lead">{{ $course->description }}</p>
                    </div>
                </div>

                <h4 class="mb-3">Course Curriculum</h4>
                @forelse($course->lessons as $index => $lesson)
                    <div class="card mb-2 shadow-sm border-0">
<div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $index + 1 }}. {{ $lesson->title }}</strong>
                                @if($lesson->is_free)
                                    <span class="badge badge-success ml-2">Free Preview</span>
                                @endif
                                <p class="text-muted small mb-0">{{ $lesson->duration_minutes }} minutes</p>
                            </div>
                            @if($lesson->is_free)
                                <button class="btn btn-sm btn-outline-primary">Play</button>
                            @else
                                <i class="las la-lock text-muted"></i>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No lessons available yet.</p>
                @endforelse
            </div>

            <div class="col-lg-4">
                <div class="card shadow-lg border-0 sticky-top" style="top: 20px;">
                    <div class="card-body text-center p-4">
                        <h2 class="display-4 text-success font-weight-bold">${{ number_format($course->price, 2) }}</h2>
                        <button class="btn btn-success btn-block btn-lg mt-3 mb-3">Enroll Now</button>
                        <ul class="list-unstyled text-left">
                            <li class="mb-2"><i class="las la-video text-primary"></i> {{ $course->lessons->count() }} Lessons</li>
                            <li class="mb-2"><i class="las la-clock text-primary"></i> {{ $course->duration_minutes }} minutes total</li>
                            <li class="mb-2"><i class="las la-infinity text-primary"></i> Lifetime access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

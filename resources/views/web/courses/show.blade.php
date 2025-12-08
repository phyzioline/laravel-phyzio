@extends('web.layouts.app')

@section('title', $course->title)

@section('content')
<div class="course-details-wrapper py-5 bg-light">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0">
                <li class="breadcrumb-item"><a href="{{ route('web.courses.index') }}" class="text-teal-700" style="color: #0d9488;">{{ __('Courses') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($course->title, 20) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content (Left) -->
            <div class="col-lg-8">
                
                <!-- Course Hero Info -->
                <div class="mb-4">
                    <h1 class="font-weight-bold text-dark">{{ $course->title }}</h1>
                    <p class="lead text-muted">{{ $course->description }}</p>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-warning mr-2"><i class="las la-star"></i> 4.8</span>
                        <span class="text-muted mr-3">{{ __('(240 ratings)') }}</span>
                        <span class="text-muted"><i class="las la-user-graduate"></i> {{ $course->enrollments->count() ?? 0 }} {{ __('Students') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                         <span class="mr-2">{{ __('Created by') }}</span>
                         <a href="#" class="font-weight-bold text-teal-700" style="color: #0d9488;">{{ $course->instructor->name ?? __('Unknown Instructor') }}</a>
                    </div>
                </div>

                <!-- Cover Image (Mobile Only/Inline) -->
                <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('web/assets/images/course-placeholder.jpg') }}" class="img-fluid rounded shadow-sm mb-4 w-100" alt="{{ $course->title }}">

                <!-- What you'll learn -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-3">{{ __('What you\'ll learn') }}</h4>
                        <div class="row">
                            <div class="col-md-6 mb-2"><i class="las la-check text-teal-600 mr-2" style="color:#0d9488;"></i> {{ __('Comprehensive understanding of techniques') }}</div>
                            <div class="col-md-6 mb-2"><i class="las la-check text-teal-600 mr-2" style="color:#0d9488;"></i> {{ __('Latest evidence-based practices') }}</div>
                            <div class="col-md-6 mb-2"><i class="las la-check text-teal-600 mr-2" style="color:#0d9488;"></i> {{ __('Patient assessment protocols') }}</div>
                            <div class="col-md-6 mb-2"><i class="las la-check text-teal-600 mr-2" style="color:#0d9488;"></i> {{ __('Treatment planning strategies') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Curriculum -->
                <div class="mb-5">
                    <h4 class="font-weight-bold mb-3">{{ __('Course Content') }}</h4>
                    <div class="accordion" id="curriculumAccordion">
                        <!-- Mock Modules since we only have flat lessons usually, but structure is requested -->
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-header bg-white" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-dark font-weight-bold d-flex justify-content-between text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span>{{ __('Module 1: Introduction') }}</span>
                                        <small class="text-muted">3 {{ __('Lessons') }}</small>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#curriculumAccordion">
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @forelse($course->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="las la-play-circle text-muted mr-2"></i> {{ $lesson->title }}
                                            </div>
                                            @if($isEnrolled)
                                                <a href="#" class="btn btn-sm btn-light text-teal-700"><i class="las la-play"></i></a>
                                            @else
                                                <i class="las la-lock text-muted"></i>
                                            @endif
                                        </li>
                                        @empty
                                        <li class="list-group-item text-muted">{{ __('No lessons yet.') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructor -->
                <div class="mb-4">
                    <h4 class="font-weight-bold mb-3">{{ __('Instructor') }}</h4>
                     <div class="card shadow-sm border-0 bg-white">
                        <div class="card-body">
                            <h5 class="font-weight-bold">{{ $course->instructor->name ?? 'Instructor Name' }}</h5>
                            <p class="text-muted mb-2">Senior Physiotherapist</p>
                            <div class="d-flex mb-3">
                                <small class="mr-3"><i class="las la-user-friends"></i> 500 Students</small>
                                <small class="mr-3"><i class="las la-play-circle"></i> 12 Courses</small>
                            </div>
                            <p>{{ __('Experienced physiotherapist with over 10 years of clinical practice specializing in orthopedic rehabilitation.') }}</p>
                        </div>
                     </div>
                </div>

            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h2 class="font-weight-bold text-teal-700 mb-3" style="color: #0d9488;">{{ $course->price > 0 ? number_format($course->price) . ' EGP' : __('Free') }}</h2>
                        
                        @if($isEnrolled)
                            <button class="btn btn-success btn-lg btn-block mb-3" disabled>{{ __('Enrolled') }} <i class="las la-check"></i></button>
                            <button class="btn btn-outline-teal btn-lg btn-block" style="color: #0d9488; border-color: #0d9488;">{{ __('Go to Course Dashboard') }}</button>
                        @else
                            <button class="btn btn-teal btn-lg btn-block text-white mb-3" style="background-color: #0d9488;">{{ __('Enroll Now') }}</button>
                            <p class="text-center text-muted small">{{ __('30-Day Money-Back Guarantee') }}</p>
                        @endif

                        <h6 class="font-weight-bold mt-4 mb-2">{{ __('This course includes:') }}</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="las la-video mr-2 text-muted"></i> 12 {{ __('hours on-demand video') }}</li>
                            <li class="mb-2"><i class="las la-file-download mr-2 text-muted"></i> 5 {{ __('downloadable resources') }}</li>
                            <li class="mb-2"><i class="las la-infinity mr-2 text-muted"></i> {{ __('Full lifetime access') }}</li>
                            <li class="mb-2"><i class="las la-mobile-alt mr-2 text-muted"></i> {{ __('Access on mobile and TV') }}</li>
                            <li class="mb-2"><i class="las la-certificate mr-2 text-muted"></i> {{ __('Certificate of completion') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

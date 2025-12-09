@extends('web.layouts.app')

@section('title', $course->title . ' - Phyzioline Academy')

@section('content')
<!-- Hero Section -->
<div class="bg-dark text-white position-relative" style="background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('{{ $course->thumbnail ? Storage::url($course->thumbnail) : 'https://via.placeholder.com/1920x600' }}') no-repeat center center/cover;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 py-5">
                <span class="badge badge-warning mb-2">{{ $course->level }}</span>
                <span class="badge badge-light mb-2 ml-2">{{ $course->category_id ?? 'General' }}</span>
                <h1 class="display-4 font-weight-bold">{{ $course->title }}</h1>
                <p class="lead mb-4">{{ Str::limit(strip_tags($course->description), 150) }}</p>
                
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $course->instructor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name) }}" class="rounded-circle mr-3" width="50" height="50" style="border: 2px solid #fff;">
                    <div>
                        <div class="small text-white-50">{{ __('Created by') }}</div>
                        <a href="#" class="text-white font-weight-bold">{{ $course->instructor->name }}</a>
                    </div>
                    <div class="ml-5">
                        <div class="small text-white-50">{{ __('Last Updated') }}</div>
                        <div class="font-weight-bold">{{ $course->updated_at->format('M Y') }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <i class="las la-star text-warning"></i>
                        <span class="font-weight-bold">4.8</span>
                        <span class="small text-white-50">(1,240 ratings)</span>
                    </div>
                    <div>
                         <i class="las la-user-graduate text-light"></i>
                        <span class="font-weight-bold">5,400</span>
                        <span class="small text-white-50">students</span>
                    </div>
                    <div class="ml-4">
                         <i class="las la-globe text-light"></i>
                        <span class="font-weight-bold">{{ $course->language }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Learning Outcomes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="font-weight-bold mb-4">{{ __('What you\'ll learn') }}</h4>
                     <div class="row">
                         <!-- Mock Outcomes if JSON is empty/null for mockup -->
                         @php 
                            $outcomes = $course->outcomes ?? [
                                'Master the fundamentals of physical therapy assessment.',
                                'Understand advanced manual therapy techniques.',
                                'Develop comprehensive treatment plans.',
                                'Identify red flags and contraindications.'
                            ];
                         @endphp
                         @foreach($outcomes as $outcome)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex">
                                    <i class="las la-check text-success mr-2 mt-1"></i>
                                    <span>{{ $outcome }}</span>
                                </div>
                            </div>
                         @endforeach
                     </div>
                </div>
            </div>

            <!-- Course Content / Curriculum -->
            <div class="mb-5">
                <h4 class="font-weight-bold mb-3">{{ __('Course Content') }}</h4>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>{{ $course->lessons->count() }} lessons</span>
                    <span>{{ $course->lessons->sum('duration_minutes') }}m total length</span>
                </div>
                
                <div class="accordion" id="accordionCurriculum">
                    <!-- Grouping by simplistic approach for now, usually modules -->
                    <div class="card">
                        <div class="card-header bg-light" id="headingOne">
                             <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne">
                                    {{ __('Module 1: Introduction & Basics') }}
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#accordionCurriculum">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @forelse($course->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="las la-play-circle text-muted mr-2"></i>
                                                {{ $lesson->title }}
                                                @if($lesson->is_preview) 
                                                    <span class="badge badge-light text-primary ml-2">{{ __('Preview') }}</span>
                                                @endif
                                            </div>
                                            <span class="text-muted small">{{ $lesson->duration_minutes }}m</span>
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

            <!-- Description -->
             <div class="mb-5">
                <h4 class="font-weight-bold mb-3">{{ __('Description') }}</h4>
                <div class="prose text-muted">
                    {!! nl2br(e($course->description)) !!}
                </div>
             </div>

            <!-- Instructor -->
            <div class="mb-5">
                 <h4 class="font-weight-bold mb-3">{{ __('Instructor') }}</h4>
                 <div class="card bg-light border-0 p-4">
                     <div class="d-flex align-items-start">
                         <img src="{{ $course->instructor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name) }}" class="rounded-circle mr-4" width="80" height="80">
                         <div>
                             <h5 class="font-weight-bold mb-1"><a href="#" class="text-dark">{{ $course->instructor->name }}</a></h5>
                             <div class="text-muted small mb-3">{{ $course->instructor->specialty ?? 'Specialist Physical Therapist' }}</div>
                             <p class="small text-muted mb-0">
                                 Dr. {{ $course->instructor->name }} is a highly experienced therapist with over 10 years in the field. They specialize in {{ $course->instructor->specialty ?? 'general therapy' }} and have taught thousands of students worldwide.
                             </p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Sidebar / Pricing -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 sticky-top" style="top: 100px; z-index: 10;">
                <div class="position-relative">
                    @if($course->trailer_url)
                        <!-- If trailer exists, show play button overlay -->
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px; cursor: pointer;">
                            <i class="las la-play-circle text-white display-3"></i>
                        </div>
                    @else
                         <img src="{{ $course->thumbnail ? Storage::url($course->thumbnail) : 'https://via.placeholder.com/600x400' }}" class="card-img-top">
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        @if($course->discount_price)
                            <h2 class="font-weight-bold mb-0 text-primary">{{ $course->discount_price }} EGP</h2>
                            <span class="text-muted"><del>{{ $course->price }} EGP</del></span>
                            <span class="text-danger ml-2">{{ round((($course->price - $course->discount_price) / $course->price) * 100) }}% off</span>
                        @elseif($course->price > 0)
                            <h2 class="font-weight-bold mb-0 text-primary">{{ $course->price }} EGP</h2>
                        @else
                            <h2 class="font-weight-bold mb-0 text-success">{{ __('Free') }}</h2>
                        @endif
                    </div>
                    
                    @if($isEnrolled)
                         <a href="#" class="btn btn-success btn-block btn-lg mb-3 shadow-sm">{{ __('Go to Course') }}</a>
                    @else
                         <form action="#" method="POST">
                             @csrf
                             <button type="submit" class="btn btn-primary btn-block btn-lg mb-3 shadow-sm" style="background-color: #00897b; border-color: #00897b;">
                                 {{ $course->price > 0 ? __('Buy Now') : __('Enroll for Free') }}
                             </button>
                         </form>
                         <button class="btn btn-outline-secondary btn-block mb-4">{{ __('Add to Cart') }}</button>
                    @endif

                    <div class="small">
                        <p class="mb-2"><i class="las la-video mr-2"></i> {{ $course->lessons->sum('duration_minutes') }} mins on-demand video</p>
                        <p class="mb-2"><i class="las la-file-download mr-2"></i> 5 downloadable resources</p>
                        <p class="mb-2"><i class="las la-infinity mr-2"></i> Full lifetime access</p>
                        <p class="mb-2"><i class="las la-mobile mr-2"></i> Access on mobile and TV</p>
                        <p class="mb-0"><i class="las la-certificate mr-2"></i> Certificate of completion</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

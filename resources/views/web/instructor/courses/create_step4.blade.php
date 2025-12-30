@extends('web.layouts.dashboard_master')

@section('title', 'Review & Submit')
@section('header_title', 'Review & Submit')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Wizard Progress -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                 <div class="d-flex justify-content-between position-relative">
                    <div class="text-center text-muted opacity-50">
                         <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">1</div>
                        <div class="small">{{ __('Basic Info') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">2</div>
                        <div class="small">{{ __('Curriculum') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">3</div>
                         <div class="small">{{ __('Pricing') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="btn btn-primary rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">4</div>
                        <div class="font-weight-bold text-primary">{{ __('Review') }}</div>
                    </div>
                     <div style="position: absolute; top: 20px; left: 50px; right: 50px; height: 2px; background: #eee; z-index: -1;"></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 font-weight-bold">{{ __('Step 4: Review Your Course') }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="las la-exclamation-triangle mr-1"></i> {{ __('Please double-check all details before submitting. Once submitted, the course will be locked for review.') }}
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                         @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded w-100 shadow-sm">
                        @else
                            <div class="bg-light rounded w-100 d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                                {{ __('No Image') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3 class="font-weight-bold">{{ $course->title }}</h3>
                        <p class="mb-1"><strong>{{ __('Category:') }}</strong> {{ $course->category_id }} </p> 
                        <p class="mb-1"><strong>{{ __('Level:') }}</strong> {{ $course->level }}</p>
                         <p class="mb-3"><strong>{{ __('Price:') }}</strong> 
                            @if($course->price > 0)
                                {{ number_format($course->price, 2) }} EGP
                            @else
                                <span class="badge badge-success">{{ __('Free') }}</span>
                            @endif
                         </p>
                        <p class="text-muted">{{ Str::limit($course->description, 200) }}</p>
                    </div>
                </div>

                 <hr>

                 <div class="d-flex justify-content-between align-items-center">
                     <div>
                         <h6 class="mb-0 font-weight-bold">{{ $course->lessons->count() }} {{ __('Lessons') }}</h6>
                         <span class="small text-muted">{{ $course->lessons->sum('duration_minutes') }} {{ __('Minutes Total') }}</span>
                     </div>
                 </div>

                 <form action="{{ route('instructor.' . app()->getLocale() . '.courses.update.' . app()->getLocale(), ['course' => $course->id, 'step' => 4]) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('instructor.' . app()->getLocale() . '.courses.edit.' . app()->getLocale(), ['course' => $course->id, 'step' => 3]) }}" class="btn btn-light px-4 btn-lg">
                            <i class="las la-arrow-left mr-2"></i> {{ __('Back') }}
                        </a>
                        <button type="submit" class="btn btn-success px-5 btn-lg">
                            <i class="las la-check-circle mr-2"></i> {{ __('Submit for Review') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

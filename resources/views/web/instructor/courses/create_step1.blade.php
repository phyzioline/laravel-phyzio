@extends('web.layouts.dashboard_master')

@section('title', 'Create New Course')
@section('header_title', 'Create New Course')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Wizard Progress -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between position-relative">
                    <div class="text-center">
                        <div class="btn btn-primary rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">1</div>
                        <div class="font-weight-bold text-primary">{{ __('Basic Info') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">2</div>
                        <div class="small">{{ __('Curriculum') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">3</div>
                        <div class="small">{{ __('Pricing') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">4</div>
                        <div class="small">{{ __('Review') }}</div>
                    </div>
                    
                    <!-- Connector Lines (Visual only) -->
                    <div style="position: absolute; top: 20px; left: 50px; right: 50px; height: 2px; background: #eee; z-index: -1;"></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 font-weight-bold">{{ __('Step 1: Basic Information') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($course) ? route('instructor.' . app()->getLocale() . '.courses.update.' . app()->getLocale(), ['course' => $course->id, 'step' => 1]) : route('instructor.' . app()->getLocale() . '.courses.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($course)) @method('PUT') @endif
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold">{{ __('Course Title') }}</label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Advanced Manual Therapy Techniques" value="{{ $course->title ?? old('title') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">{{ __('Category') }}</label>
                            <select name="category_id" class="form-control">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (isset($course) && $course->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">{{ __('Level') }}</label>
                            <select name="level" class="form-control">
                                <option value="Beginner" {{ (isset($course) && $course->level == 'Beginner') ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ (isset($course) && $course->level == 'Intermediate') ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ (isset($course) && $course->level == 'Advanced') ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">{{ __('Language') }}</label>
                            <select name="language" class="form-control">
                                <option value="English" {{ (isset($course) && $course->language == 'English') ? 'selected' : '' }}>English</option>
                                <option value="Arabic" {{ (isset($course) && $course->language == 'Arabic') ? 'selected' : '' }}>Arabic</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold">{{ __('Short Description') }}</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe what students will learn...">{{ $course->description ?? old('description') }}</textarea>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="font-weight-bold">{{ __('Course Thumbnail') }}</label>
                            <div class="custom-file">
                                <input type="file" name="thumbnail" class="custom-file-input" id="thumbnailInput">
                                <label class="custom-file-label" for="thumbnailInput">{{ __('Choose file') }}</label>
                            </div>
                            <small class="text-muted">{{ __('Recommended size: 1280x720 pixels (JPEG/PNG)') }}</small>
                            @if(isset($course) && $course->thumbnail)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($course->thumbnail) }}" width="150" class="rounded border">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="submit" class="btn btn-primary px-4 btn-lg">
                            {{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

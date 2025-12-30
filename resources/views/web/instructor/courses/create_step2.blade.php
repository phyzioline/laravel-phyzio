@extends('web.layouts.dashboard_master')

@section('title', 'Course Curriculum')
@section('header_title', 'Course Curriculum')

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
                    <div class="text-center">
                        <div class="btn btn-primary rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">2</div>
                        <div class="font-weight-bold text-primary">{{ __('Curriculum') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">3</div>
                        <div class="small">{{ __('Pricing') }}</div>
                    </div>
                    <div class="text-center text-muted opacity-50">
                        <div class="btn btn-light rounded-circle mb-2" style="width: 40px; height: 40px; line-height: 28px;">4</div>
                        <div class="small">{{ __('Review') }}</div>
                    </div>
                     <div style="position: absolute; top: 20px; left: 50px; right: 50px; height: 2px; background: #eee; z-index: -1;"></div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold">{{ __('Step 2: Curriculum Builder') }}</h5>
                <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addLessonModal">
                    <i class="las la-plus"></i> {{ __('Add New Lesson') }}
                </button>
            </div>
            <div class="card-body">
                
                @if($course->lessons->isEmpty())
                    <div class="text-center py-5">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-folder-2947172-2463456.png" width="150" class="opacity-50">
                        <p class="text-muted mt-3">{{ __('No lessons added yet. Start by adding your first lesson!') }}</p>
                    </div>
                @else
                    <div class="accordion" id="curriculumAccordion">
                        @foreach($course->lessons as $lesson)
                            <div class="card mb-2 border">
                                <div class="card-header bg-white" id="heading{{ $lesson->id }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapse{{ $lesson->id }}">
                                            <span>
                                                <i class="las la-grip-lines mr-2 text-muted"></i> 
                                                <i class="las {{ $lesson->type == 'video' ? 'la-video' : ($lesson->type == 'quiz' ? 'la-question-circle' : 'la-file-alt') }} mr-2 text-primary"></i>
                                                {{ $lesson->title }}
                                            </span>
                                            <div>
                                                 <span class="badge badge-light border mr-2">{{ $lesson->duration_minutes }} min</span>
                                                 <i class="las la-angle-down"></i>
                                            </div>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse{{ $lesson->id }}" class="collapse" aria-labelledby="heading{{ $lesson->id }}" data-parent="#curriculumAccordion">
                                    <div class="card-body">
                                        <p class="text-muted small">{{ $lesson->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                 @if($lesson->is_free) <span class="badge badge-success">{{ __('Free Preview') }}</span> @endif
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-light text-danger"><i class="las la-trash"></i> {{ __('Delete') }}</button>
                                                <button class="btn btn-sm btn-light text-primary"><i class="las la-edit"></i> {{ __('Edit') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="d-flex justify-content-between border-top pt-3 mt-4">
                    <a href="{{ route('instructor.' . app()->getLocale() . '.courses.edit.' . app()->getLocale(), ['course' => $course->id, 'step' => 1]) }}" class="btn btn-light px-4 btn-lg">
                        <i class="las la-arrow-left mr-2"></i> {{ __('Back') }}
                    </a>
                    <a href="{{ route('instructor.' . app()->getLocale() . '.courses.edit.' . app()->getLocale(), ['course' => $course->id, 'step' => 3]) }}" class="btn btn-primary px-4 btn-lg">
                        {{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">{{ __('Add New Lesson') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Placeholder Form -->
                <form>
                    <div class="form-group">
                        <label>{{ __('Lesson Title') }}</label>
                        <input type="text" class="form-control" placeholder="e.g. Introduction to Anatomy">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Lesson Type') }}</label>
                            <select class="form-control">
                                <option value="video">{{ __('Video Lesson') }}</option>
                                <option value="document">{{ __('PDF Document') }}</option>
                                <option value="quiz">{{ __('Quiz') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Duration (minutes)') }}</label>
                            <input type="number" class="form-control" placeholder="10">
                        </div>
                    </div>
                    <div class="form-group">
                         <label>{{ __('Video URL / File Upload') }}</label>
                         <input type="file" class="form-control-file border p-2">
                    </div>
                    <div class="custom-control custom-checkbox my-3">
                        <input type="checkbox" class="custom-control-input" id="isFree">
                        <label class="custom-control-label" for="isFree">{{ __('Enable as Free Preview') }}</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Add Lesson') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

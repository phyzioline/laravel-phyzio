@extends('web.layouts.app')

@section('title', __('Create New Course'))

@section('content')
<div class="create-course-wrapper py-5 bg-light">
    <div class="container">
        
        <!-- Wizard Header -->
        <div class="text-center mb-5">
            <h2 class="font-weight-bold text-teal-700" style="color: #0d9488;">{{ __('Create New Course') }}</h2>
            <p class="text-muted">{{ __('Follow the steps to publish your course on PhyzioLine.') }}</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Wizard Steps Indicator -->
                <div class="wizard-steps d-flex justify-content-between mb-4 position-relative">
                    <div class="step-indicator active" id="indicator-1">
                        <div class="step-circle bg-teal text-white">1</div>
                        <span class="step-label font-weight-bold mt-2">{{ __('Basic Info') }}</span>
                    </div>
                    <div class="step-indicator" id="indicator-2">
                        <div class="step-circle">2</div>
                        <span class="step-label mt-2">{{ __('Curriculum') }}</span>
                    </div>
                    <div class="step-indicator" id="indicator-3">
                        <div class="step-circle">3</div>
                        <span class="step-label mt-2">{{ __('Pricing') }}</span>
                    </div>
                    <div class="step-indicator" id="indicator-4">
                        <div class="step-circle">4</div>
                        <span class="step-label mt-2">{{ __('Review') }}</span>
                    </div>
                    <div class="progress-line bg-secondary opacity-25" style="position: absolute; top: 20px; left: 0; width: 100%; height: 2px; z-index: -1;"></div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data" id="courseForm">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- STEP 1: Basic Info -->
                            <div class="wizard-step" id="step-1">
                                <h4 class="font-weight-bold mb-4">{{ __('Step 1: Course Information') }}</h4>
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('Course Title') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="{{ __('e.g., Advanced Manual Therapy Techniques') }}" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">{{ __('Category') }} <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-control">
                                            <option value="">{{ __('Select Category') }}</option>
                                            <option value="1">Orthopedic</option>
                                            <option value="2">Neurology</option>
                                            <option value="3">Sports</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">{{ __('Level') }}</label>
                                        <select name="level" class="form-control">
                                            <option value="Beginner">{{ __('Beginner') }}</option>
                                            <option value="Intermediate">{{ __('Intermediate') }}</option>
                                            <option value="Advanced">{{ __('Advanced') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('Description') }} <span class="text-danger">*</span></label>
                                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('Detailed description of what students will learn...') }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ __('Cover Image') }}</label>
                                    <div class="custom-file">
                                        <input type="file" name="thumbnail" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="button" class="btn btn-teal text-white px-4 next-step" data-next="2" style="background-color: #0d9488;">{{ __('Next: Curriculum') }} <i class="las la-arrow-right"></i></button>
                                </div>
                            </div>

                            <!-- STEP 2: Curriculum -->
                            <div class="wizard-step d-none" id="step-2">
                                <h4 class="font-weight-bold mb-4">{{ __('Step 2: Operations') }} ({{ __('Curriculum Builder') }})</h4>
                                <div class="alert alert-info bg-light-teal border-teal text-teal-700" style="background-color: #e0f2f1; border-color: #0d9488;">
                                    <i class="las la-info-circle"></i> {{ __('Start by adding a module, then add lessons inside it.') }}
                                </div>
                                
                                <div id="curriculum-builder">
                                    <!-- Dynamic JS Builder here -->
                                    <div class="module-item border rounded mb-3 bg-white p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="font-weight-bold m-0"><i class="las la-folder mr-2"></i> {{ __('Module 1: Introduction') }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger"><i class="las la-trash"></i></button>
                                        </div>
                                        <div class="pl-4">
                                            <div class="lesson-item d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                                <span><i class="las la-file-video mr-2"></i> {{ __('Lesson 1.1: Welcome') }}</span>
                                                <button type="button" class="btn btn-sm text-muted"><i class="las la-edit"></i></button>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-link text-teal-700 p-0 mt-2" style="color: #0d9488;">+ {{ __('Add New Lesson') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-outline-secondary btn-block border-dashed mb-4">+ {{ __('Add New Module') }}</button>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4 prev-step" data-prev="1"><i class="las la-arrow-left"></i> {{ __('Previous') }}</button>
                                    <button type="button" class="btn btn-teal text-white px-4 next-step" data-next="3" style="background-color: #0d9488;">{{ __('Next: Pricing') }} <i class="las la-arrow-right"></i></button>
                                </div>
                            </div>

                            <!-- STEP 3: Pricing -->
                            <div class="wizard-step d-none" id="step-3">
                                <h4 class="font-weight-bold mb-4">{{ __('Step 3: Pricing & Policy') }}</h4>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">{{ __('Price (EGP)') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">EGP</span>
                                            </div>
                                            <input type="number" name="price" class="form-control" placeholder="0.00">
                                        </div>
                                        <small class="text-muted">{{ __('Leave 0 for Free courses.') }}</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold">{{ __('Discount Price (Optional)') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">EGP</span>
                                            </div>
                                            <input type="number" name="discount" class="form-control" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="policyCheck" required>
                                        <label class="custom-control-label" for="policyCheck">{{ __('I agree to the Instructor Revenue Share & Refund Policy.') }}</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4 prev-step" data-prev="2"><i class="las la-arrow-left"></i> {{ __('Previous') }}</button>
                                    <button type="button" class="btn btn-teal text-white px-4 next-step" data-next="4" style="background-color: #0d9488;">{{ __('Next: Review') }} <i class="las la-arrow-right"></i></button>
                                </div>
                            </div>

                             <!-- STEP 4: Review -->
                            <div class="wizard-step d-none" id="step-4">
                                <h4 class="font-weight-bold mb-4">{{ __('Step 4: Review & Submit') }}</h4>
                                <div class="text-center py-4">
                                    <i class="las la-check-circle text-success mb-3" style="font-size: 4rem;"></i>
                                    <h5>{{ __('All set!') }}</h5>
                                    <p class="text-muted">{{ __('Your course "Advanced Manual Therapy Techniques" is ready for submission.') }}</p>
                                    <p class="small text-muted">{{ __('It will be sent to the Phyzioline team for approval (approx. 24-48 hours).') }}</p>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4 prev-step" data-prev="3"><i class="las la-arrow-left"></i> {{ __('Previous') }}</button>
                                    <button type="submit" class="btn btn-teal text-white px-5 rounded-pill shadow" id="finalSubmitBtn" style="background-color: #0d9488;">{{ __('Submit for Review') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.wizard-step');
        const indicators = document.querySelectorAll('.step-indicator');

        function showStep(stepNum) {
            steps.forEach(step => step.classList.add('d-none'));
            document.getElementById('step-' + stepNum).classList.remove('d-none');

            indicators.forEach(ind => {
                ind.classList.remove('active');
                ind.querySelector('.step-circle').classList.remove('bg-teal', 'text-white');
            });

            for(let i = 1; i <= stepNum; i++) {
                const ind = document.getElementById('indicator-' + i);
                ind.classList.add('active');
                ind.querySelector('.step-circle').classList.add('bg-teal', 'text-white');
            }
        }

        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const currentStep = this.closest('.wizard-step').id.replace('step-', '');
                const next = this.getAttribute('data-next');
                
                // Validate current step before proceeding
                const currentStepElement = document.getElementById('step-' + currentStep);
                const requiredFields = currentStepElement.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (isValid) {
                    showStep(next);
                } else {
                    alert('Please fill in all required fields before proceeding.');
                }
            });
        });

        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', function() {
                const prev = this.getAttribute('data-prev');
                showStep(prev);
            });
        });
        
        // Handle final form submission
        const form = document.getElementById('courseForm');
        const finalSubmitBtn = document.getElementById('finalSubmitBtn');
        
        if (form && finalSubmitBtn) {
            form.addEventListener('submit', function(e) {
                // Don't prevent default - let form submit normally
                finalSubmitBtn.disabled = true;
                finalSubmitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> {{ __('Submitting...") }}';
            });
        }
    });
</script>
<style>
    .step-circle {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto;
        color: #6c757d;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    .step-indicator.active .step-circle {
        border-color: #0d9488;
        box-shadow: 0 0 0 2px #0d9488;
    }
    .bg-teal { background-color: #0d9488 !important; }
    .text-teal-700 { color: #0d9488 !important; }
</style>
@endpush
@endsection

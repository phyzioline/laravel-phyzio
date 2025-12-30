@extends('web.layouts.dashboard_master')

@section('title', 'Post New Job')
@section('header_title', 'Post New Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ __('Please fix the following errors:') }}</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('company.jobs.store') }}" method="POST" id="jobForm">
                    @csrf
                    
                    <h5 class="text-primary mb-3">{{ __('1. Job Basics') }}</h5>
                    <div class="form-group mb-3">
                        <label>{{ __('Job Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="{{ __('e.g. Senior Physiotherapist') }}" required value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="job" {{ old('type') == 'job' ? 'selected' : '' }}>{{ __('Full-time Job') }}</option>
                                    <option value="training" {{ old('type') == 'training' ? 'selected' : '' }}>{{ __('Training / Internship') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>{{ __('Urgency') }} <span class="text-danger">*</span></label>
                                <select name="urgency_level" class="form-control @error('urgency_level') is-invalid @enderror" required>
                                    <option value="normal" {{ old('urgency_level') == 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                                    <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>{{ __('Urgent Hiring') }}</option>
                                </select>
                                @error('urgency_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Location') }}</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="{{ __('e.g. Cairo, Maadi') }}" value="{{ old('location') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required placeholder="{{ __('Describe the role responsibilities...') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">{{ __('2. Clinical Requirements') }}</h5>

                    <div class="form-group mb-3">
                        <label>{{ __('Specialties Required (Select all that apply)') }}</label>
                        <div class="row">
                            @foreach($specialties as $specialty)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="specialty[]" value="{{ $specialty }}" class="custom-control-input" id="spec_{{ $loop->index }}" {{ in_array($specialty, old('specialty', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="spec_{{ $loop->index }}">{{ $specialty }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Techniques / Skills Needed') }}</label>
                        <div class="row">
                            @foreach($techniques as $technique)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="techniques[]" value="{{ $technique }}" class="custom-control-input" id="tech_{{ $loop->index }}" {{ in_array($technique, old('techniques', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tech_{{ $loop->index }}">{{ $technique }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Equipment Experience') }}</label>
                        <div class="row">
                            @foreach($equipment as $item)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="equipment[]" value="{{ $item }}" class="custom-control-input" id="eq_{{ $loop->index }}" {{ in_array($item, old('equipment', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="eq_{{ $loop->index }}">{{ $item }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>{{ __('Minimum Experience (Years)') }}</label>
                                <input type="number" name="min_years_experience" class="form-control @error('min_years_experience') is-invalid @enderror" min="0" value="{{ old('min_years_experience', 0) }}">
                                @error('min_years_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>{{ __('Experience Level') }} <span class="text-danger">*</span></label>
                                <select name="experience_level" class="form-control @error('experience_level') is-invalid @enderror" required>
                                    <option value="student" {{ old('experience_level') == 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                                    <option value="fresh" {{ old('experience_level') == 'fresh' ? 'selected' : '' }}>{{ __('Fresh Graduate') }}</option>
                                    <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>{{ __('Junior (1-3 Years)') }}</option>
                                    <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>{{ __('Senior (3-5+ Years)') }}</option>
                                    <option value="consultant" {{ old('experience_level') == 'consultant' ? 'selected' : '' }}>{{ __('Consultant') }}</option>
                                </select>
                                @error('experience_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="licenseRequired" name="license_required" {{ old('license_required', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="licenseRequired">{{ __('Professional License Required') }}</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Gender Preference') }}</label>
                        <select name="gender_preference" class="form-control @error('gender_preference') is-invalid @enderror">
                            <option value="">{{ __('No Preference') }}</option>
                            <option value="male" {{ old('gender_preference') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ old('gender_preference') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                        </select>
                        @error('gender_preference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">{{ __('3. Compensation & Benefits') }}</h5>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>{{ __('Salary Type') }} <span class="text-danger">*</span></label>
                                <select name="salary_type" class="form-control @error('salary_type') is-invalid @enderror" required>
                                    <option value="fixed" {{ old('salary_type') == 'fixed' ? 'selected' : '' }}>{{ __('Fixed Monthly Salary') }}</option>
                                    <option value="per_session" {{ old('salary_type') == 'per_session' ? 'selected' : '' }}>{{ __('Per Session / Case') }}</option>
                                    <option value="commission" {{ old('salary_type') == 'commission' ? 'selected' : '' }}>{{ __('Commission Based') }}</option>
                                    <option value="negotiable" {{ old('salary_type') == 'negotiable' ? 'selected' : '' }}>{{ __('Negotiable') }}</option>
                                </select>
                                @error('salary_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label>{{ __('Salary Range / Rate Description') }}</label>
                                <input type="text" name="salary_range" class="form-control @error('salary_range') is-invalid @enderror" placeholder="{{ __('e.g. 6000 - 8000 EGP or 100 EGP/Session') }}" value="{{ old('salary_range') }}">
                                @error('salary_range')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ __('Number of Openings') }}</label>
                        <input type="number" name="openings_count" class="form-control @error('openings_count') is-invalid @enderror" value="{{ old('openings_count', 1) }}" min="1" style="max-width: 150px;">
                        @error('openings_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('company.jobs.index') }}" class="btn btn-light mr-2">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary btn-lg" id="publishBtn">{{ __('Publish Job') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('jobForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('publishBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('Publishing...') }}';
    });
</script>
@endpush
            </div>
        </div>
    </div>
</div>
@endsection


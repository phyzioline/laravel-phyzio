@extends('web.layouts.dashboard_master')

@section('title', 'Edit Job')
@section('header_title', 'Edit Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('company.jobs.update', $job->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="text-primary mb-3">1. Job Basics</h5>
                    <div class="form-group mb-3">
                        <label>Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Senior Physiotherapist" required value="{{ old('title', $job->title) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="job" {{ old('type', $job->type) == 'job' ? 'selected' : '' }}>Full-time Job</option>
                                    <option value="training" {{ old('type', $job->type) == 'training' ? 'selected' : '' }}>Training / Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Urgency <span class="text-danger">*</span></label>
                                <select name="urgency_level" class="form-control" required>
                                    <option value="normal" {{ old('urgency_level', $job->urgency_level) == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="urgent" {{ old('urgency_level', $job->urgency_level) == 'urgent' ? 'selected' : '' }}>Urgent Hiring</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Cairo, Maadi" value="{{ old('location', $job->location) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required placeholder="Describe the role responsibilities...">{{ old('description', $job->description) }}</textarea>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">2. Clinical Requirements</h5>

                    <div class="form-group mb-3">
                        <label>Specialties Required (Select all that apply)</label>
                        <div class="row">
                            @foreach($specialties as $specialty)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="specialty[]" value="{{ $specialty }}" class="custom-control-input" id="spec_{{ $loop->index }}" {{ in_array($specialty, old('specialty', $job->specialty ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="spec_{{ $loop->index }}">{{ $specialty }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Techniques / Skills Needed</label>
                        <div class="row">
                            @foreach($techniques as $technique)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="techniques[]" value="{{ $technique }}" class="custom-control-input" id="tech_{{ $loop->index }}" {{ in_array($technique, old('techniques', $job->techniques ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tech_{{ $loop->index }}">{{ $technique }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Equipment Experience</label>
                        <div class="row">
                            @foreach($equipment as $item)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="equipment[]" value="{{ $item }}" class="custom-control-input" id="eq_{{ $loop->index }}" {{ in_array($item, old('equipment', $job->equipment ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="eq_{{ $loop->index }}">{{ $item }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Minimum Experience (Years)</label>
                                <input type="number" name="min_years_experience" class="form-control" min="0" value="{{ old('min_years_experience', $job->requirements->min_years_experience ?? 0) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Experience Level <span class="text-danger">*</span></label>
                                <select name="experience_level" class="form-control" required>
                                    <option value="student" {{ old('experience_level', $job->experience_level) == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="fresh" {{ old('experience_level', $job->experience_level) == 'fresh' ? 'selected' : '' }}>Fresh Graduate</option>
                                    <option value="junior" {{ old('experience_level', $job->experience_level) == 'junior' ? 'selected' : '' }}>Junior (1-3 Years)</option>
                                    <option value="senior" {{ old('experience_level', $job->experience_level) == 'senior' ? 'selected' : '' }}>Senior (3-5+ Years)</option>
                                    <option value="consultant" {{ old('experience_level', $job->experience_level) == 'consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="licenseRequired" name="license_required" {{ old('license_required', $job->requirements->license_required ?? true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="licenseRequired">Professional License Required</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Gender Preference</label>
                        <select name="gender_preference" class="form-control">
                            <option value="">No Preference</option>
                            <option value="male" {{ old('gender_preference', $job->requirements->gender_preference ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender_preference', $job->requirements->gender_preference ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">3. Compensation & Benefits</h5>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Salary Type <span class="text-danger">*</span></label>
                                <select name="salary_type" class="form-control" required>
                                    <option value="fixed" {{ old('salary_type', $job->salary_type) == 'fixed' ? 'selected' : '' }}>Fixed Monthly Salary</option>
                                    <option value="per_session" {{ old('salary_type', $job->salary_type) == 'per_session' ? 'selected' : '' }}>Per Session / Case</option>
                                    <option value="commission" {{ old('salary_type', $job->salary_type) == 'commission' ? 'selected' : '' }}>Commission Based</option>
                                    <option value="negotiable" {{ old('salary_type', $job->salary_type) == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label>Salary Range / Rate Description</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="e.g. 6000 - 8000 EGP or 100 EGP/Session" value="{{ old('salary_range', $job->salary_range) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Number of Openings</label>
                        <input type="number" name="openings_count" class="form-control" value="{{ old('openings_count', $job->openings_count ?? 1) }}" min="1" style="max-width: 150px;">
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" {{ old('is_active', $job->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isActive">Job is Active</label>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('company.jobs.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('web.layouts.dashboard_master')

@section('title', 'Post New Job')
@section('header_title', 'Post New Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('clinic.jobs.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-primary mb-3">1. Job Basics</h5>
                    <div class="form-group mb-3">
                        <label>Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Senior Physiotherapist" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control">
                                    <option value="job">Full-time Job</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Per-session / Contract</option>
                                    <option value="training">Training / Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Urgency <span class="text-danger">*</span></label>
                                <select name="urgency_level" class="form-control">
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgent Hiring</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Cairo, Maadi" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required placeholder="Describe the role responsibilities..."></textarea>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">2. Clinical Requirements</h5>

                    <div class="form-group mb-3">
                        <label>Specialties Required (Select all that apply)</label>
                        <div class="row">
                            @foreach($specialties as $specialty)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="specialty[]" value="{{ $specialty }}" class="custom-control-input" id="spec_{{ $loop->index }}">
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
                                    <input type="checkbox" name="techniques[]" value="{{ $technique }}" class="custom-control-input" id="tech_{{ $loop->index }}">
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
                                    <input type="checkbox" name="equipment[]" value="{{ $item }}" class="custom-control-input" id="eq_{{ $loop->index }}">
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
                                <input type="number" name="min_years_experience" class="form-control" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Experience Level</label>
                                <select name="experience_level" class="form-control">
                                    <option value="student">Student</option>
                                    <option value="fresh">Fresh Graduate</option>
                                    <option value="junior">Junior (1-3 Years)</option>
                                    <option value="senior">Senior (3-5+ Years)</option>
                                    <option value="consultant">Consultant</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                         <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="licenseRequired" name="license_required" checked>
                            <label class="custom-control-label" for="licenseRequired">Professional License Required</label>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3">3. Compensation & Benefits</h5>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Salary Type</label>
                                <select name="salary_type" class="form-control">
                                    <option value="fixed">Fixed Monthly Salary</option>
                                    <option value="per_session">Per Session / Case</option>
                                    <option value="commission">Commission Based</option>
                                    <option value="negotiable">Negotiable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label>Salary Range / Rate Description</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="e.g. 6000 - 8000 EGP or 100 EGP/Session">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Number of Openings</label>
                        <input type="number" name="openings_count" class="form-control" value="1" min="1" style="max-width: 150px;">
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.jobs.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Publish Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.dashboard_master')

@section('title', 'Job Templates')
@section('header_title', 'Job Templates')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Job Templates</h4>
                <p class="text-muted">Save and reuse job posting templates</p>
            </div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createTemplateModal">
                <i class="las la-plus"></i> Create Template
            </button>
        </div>

        <div class="row">
            @forelse($templates as $template)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $template->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($template->description, 100) }}</p>
                        <div class="mt-3">
                            <span class="badge badge-info">{{ ucfirst($template->type) }}</span>
                            @if($template->location)
                                <span class="badge badge-secondary">{{ $template->location }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('company.jobs.createFromTemplate', $template->id) }}" class="btn btn-sm btn-primary">
                            <i class="las la-plus"></i> Create Job
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="editTemplate({{ $template->id }})">
                            <i class="las la-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <i class="las la-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No templates yet. Create your first template to get started!</p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createTemplateModal">
                            <i class="las la-plus"></i> Create Template
                        </button>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Job Template</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('company.jobs.createTemplate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Template Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g., Senior Physical Therapist">
                    </div>
                    <div class="form-group">
                        <label>Job Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Job Type *</label>
                                <select name="type" class="form-control" required>
                                    <option value="job">Job</option>
                                    <option value="training">Training</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Salary Type</label>
                                <input type="text" name="salary_type" class="form-control" placeholder="e.g., Monthly, Hourly">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Salary Range</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="e.g., 5000-8000 EGP">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Experience Level</label>
                        <select name="experience_level" class="form-control">
                            <option value="">Select...</option>
                            <option value="entry">Entry Level</option>
                            <option value="mid">Mid Level</option>
                            <option value="senior">Senior Level</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


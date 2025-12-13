@extends('web.layouts.dashboard_master')

@section('title', 'Post New Job')
@section('header_title', 'Post New Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('clinic.jobs.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label>Job Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Senior Physiotherapist" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="job">Full-time Job</option>
                            <option value="training">Training / Internship</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Cairo, Maadi" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Salary Range / Stipend</label>
                        <input type="text" name="salary_range" class="form-control" placeholder="e.g. 5000 - 8000 EGP">
                    </div>

                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="5" required placeholder="Describe the role, requirements, and benefits..."></textarea>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('clinic.jobs.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Post Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

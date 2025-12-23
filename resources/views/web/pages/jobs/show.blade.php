@extends('web.layouts.app')

@section('title', $job->title)

@section('content')
<div class="page-header" style="background: #f8f9fa; padding: 40px 0;">
    <div class="container">
        <a href="{{ route('web.jobs.index') }}" class="text-muted mb-3 d-inline-block"><i class="las la-arrow-left"></i> Back to Jobs</a>
        <h1 class="font-weight-bold text-dark">{{ $job->title }}</h1>
        <div class="mt-3">
             <span class="badge badge-{{ $job->type == 'job' ? 'success' : 'info' }} mr-2 px-3 py-2">
                {{ ucfirst($job->type) }}
            </span>
            <span class="text-muted mr-3"><i class="las la-building"></i> {{ $job->clinic->name ?? 'Clinic' }}</span>
            <span class="text-muted mr-3"><i class="las la-map-marker"></i> {{ $job->location ?? 'Remote' }}</span>
            <span class="text-muted"><i class="las la-wallet"></i> {{ $job->salary_range ?? 'Negotiable' }}</span>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="font-weight-bold mb-4">Job Description</h4>
                    <div class="job-description text-justify" style="line-height: 1.8;">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-4">Apply Now</h5>
                    
                    @auth
                        @if(auth()->user()->type === 'therapist')
                            @if(isset($hasApplied) && $hasApplied)
                                <div class="alert alert-success">
                                    <i class="las la-check-circle"></i> You have applied for this job.
                                </div>
                            @else
                                @if(isset($matchScore) && $matchScore < 50)
                                    <div class="alert alert-warning small">
                                        Your profile match score is low ({{ round($matchScore) }}%). Consider updating your profile skills before applying.
                                    </div>
                                @endif
                                <form action="{{ route('web.jobs.apply', $job->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="cover_letter" class="form-control" placeholder="Optional Cover Letter..." rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                                        <i class="las la-paper-plane"></i> Submit Application
                                    </button>
                                </form>
                            @endif
                        @else
                             <p class="text-muted">Login as a therapist to apply directly.</p>
                             <a href="{{ route('web.jobs.index') }}" class="btn btn-outline-primary btn-block">Find Matching Jobs</a>
                        @endif
                    @else
                        <p class="text-muted">Please login to apply for this position.</p>
                        <a href="{{ route('view_login.' . app()->getLocale()) }}" class="btn btn-primary btn-block">Login to Apply</a>
                    @endauth

                    <hr>
                    <p class="text-muted small mb-2 text-center">Or contact directly</p>
                    
                    @if($job->clinic && $job->clinic->email)
                        <a href="mailto:{{ $job->clinic->email }}?subject=Application for {{ $job->title }}" class="btn btn-outline-secondary btn-block mb-2">
                            <i class="las la-envelope"></i> Email Clinic
                        </a>
                    @endif
                    
                    @if($job->clinic && $job->clinic->phone)
                        <a href="tel:{{ $job->clinic->phone }}" class="btn btn-outline-success btn-block">
                            <i class="las la-phone"></i> Call Clinic
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-3">About the Clinic</h5>
                    <p class="mb-0">{{ $job->clinic->name ?? 'Healthcare Provider' }}</p>
                    @if($job->clinic && $job->clinic->address)
                        <p class="text-muted small mt-2"><i class="las la-map-pin"></i> {{ $job->clinic->address }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

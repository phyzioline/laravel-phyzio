@extends('web.layouts.app')

@section('title', $job->title)

@section('content')
<style>
    /* Shared styles should ideally be in a CSS file, but strictly scoping here for now */
    :root {
        --primary-color: #00897b;
        --secondary-color: #00695c;
        --text-dark: #2d3748;
        --text-muted: #718096;
        --bg-light: #f7fafc;
    }

    .job-header {
        background: linear-gradient(to right, #f8f9fa, #e2e8f0);
        padding: 60px 0;
        margin-top: 80px;
        border-bottom: 1px solid #cbd5e0;
    }

    .job-content-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .sticky-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
    }

    .icon-box {
        width: 40px;
        height: 40px;
        background: rgba(0, 137, 123, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .job-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #edf2f7;
    }

    .bullet-list li {
        margin-bottom: 10px;
        position: relative;
        padding-left: 20px;
    }

    .bullet-list li::before {
        content: "•";
        color: var(--primary-color);
        font-weight: bold;
        position: absolute;
        left: 0;
    }
</style>

<div class="job-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="text-muted font-weight-bold mb-3 d-inline-block hover-primary">
                    <i class="las la-arrow-left"></i> Back to Jobs
                </a>
                <h1 class="font-weight-bold text-dark display-5 mb-3">{{ $job->title }}</h1>
                <div class="d-flex flex-wrap align-items-center text-muted">
                    <span class="mr-4"><i class="las la-building text-primary mr-1"></i> {{ $job->clinic->name ?? 'Confidential' }}</span>
                    <span class="mr-4"><i class="las la-map-marker text-primary mr-1"></i> {{ $job->location ?? 'Remote' }}</span>
                    <span><i class="las la-clock text-primary mr-1"></i> {{ $job->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-right mt-4 mt-lg-0">
                @if($job->clinic && $job->clinic->image_url)
                    <img src="{{ $job->clinic->image_url }}" alt="Logo" class="rounded-circle shadow-sm border" width="100" height="100">
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card job-content-card bg-white p-5 mb-4">
                <h4 class="job-section-title">Job Description</h4>
                <div class="job-description text-secondary" style="line-height: 1.9; font-size: 1.05rem;">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <!-- Apply Card -->
                <div class="card job-content-card border-0 shadow-lg mb-4">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4">Job Snapshot</h5>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box"><i class="las la-wallet"></i></div>
                            <div>
                                <small class="text-muted d-block">Salary</small>
                                <span class="font-weight-bold">{{ $job->salary_range ?? 'Negotiable' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box"><i class="las la-briefcase"></i></div>
                            <div>
                                <small class="text-muted d-block">Job Type</small>
                                <span class="font-weight-bold">{{ ucfirst($job->type) }}</span>
                            </div>
                        </div>
                         <div class="d-flex align-items-center mb-4">
                            <div class="icon-box"><i class="las la-map"></i></div>
                            <div>
                                <small class="text-muted d-block">Location</small>
                                <span class="font-weight-bold">{{ $job->location ?? 'Remote' }}</span>
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->type === 'therapist')
                                @if(isset($hasApplied) && $hasApplied)
                                    <div class="alert alert-success rounded-pill text-center">
                                        <i class="las la-check-circle"></i> Applied
                                    </div>
                                @else
                                    @if(isset($matchScore) && $matchScore < 50)
                                        <div class="alert alert-warning small">
                                            Match Score {{ round($matchScore) }}%. Review skills before applying.
                                        </div>
                                    @endif
                                    <form action="{{ route('web.jobs.apply.' . app()->getLocale(), $job->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <textarea name="cover_letter" class="form-control bg-light border-0" placeholder="Optional Cover Letter..." rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm rounded-pill">
                                            Submit Application
                                        </button>
                                    </form>
                                @endif
                            @else
                                 <div class="text-center p-3 bg-light rounded">
                                     <p class="mb-2 text-muted">Login as a therapist to apply.</p>
                                     <a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="btn btn-outline-primary btn-sm rounded-pill">Browse More</a>
                                 </div>
                            @endif
                        @else
                            <div class="text-center">
                                <p class="text-muted mb-3">Log in to apply for this position.</p>
                                <a href="{{ route('view_login.' . app()->getLocale()) }}" class="btn btn-primary btn-block rounded-pill">Login / Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
                
                <!-- Contact Card -->
                <div class="card job-content-card border-0 bg-light">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3">About {{ $job->clinic->name ?? 'Employer' }}</h6>
                        @if($job->clinic && $job->clinic->address)
                            <p class="text-muted small mb-3"><i class="las la-map-pin"></i> {{ $job->clinic->address }}</p>
                        @endif
                        
                         <div class="row no-gutters">
                            @if($job->clinic && $job->clinic->email)
                                <div class="col-12 mb-2">
                                     <a href="mailto:{{ $job->clinic->email }}" class="btn btn-white border btn-block text-left text-muted">
                                        <i class="las la-envelope text-primary mr-2"></i> Email
                                    </a>
                                </div>
                            @endif
                             @if($job->clinic && $job->clinic->phone)
                                <div class="col-12">
                                     <a href="tel:{{ $job->clinic->phone }}" class="btn btn-white border btn-block text-left text-muted">
                                        <i class="las la-phone text-primary mr-2"></i> Call
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('jobs');
@endphp

@section('title', $pageMeta['title'])

@push('meta')
    <meta name="description" content="{{ $pageMeta['description'] }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $pageMeta['title'] }}">
    <meta property="og:description" content="{{ $pageMeta['description'] }}">
    <meta property="og:type" content="website">
@endpush

@push('structured-data')
<script type="application/ld+json">
@json(\App\Services\SEO\SEOService::jobsSchema())
</script>
@endpush

@section('content')
<style>
    /* Professional Job Board Custom Styles */
    :root {
        --primary-color: #00897b;
        --secondary-color: #00695c;
        --accent-color: #ff9800;
        --text-dark: #2d3748;
        --text-muted: #718096;
        --bg-light: #f7fafc;
    }

    .jobs-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 80px 0 100px;
        color: white;
        margin-top: 80px; /* Adjust based on navbar height */
        position: relative;
        overflow: hidden;
    }

    .jobs-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: white;
        clip-path: polygon(0 100%, 100% 100%, 100% 0);
    }

    .job-card-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .job-card-row:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.08); /* Soft shadow */
        border-color: #cbd5e0;
    }

    .job-card-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: transparent;
        transition: background-color 0.3s;
    }

    .job-card-row:hover::before {
        background: var(--primary-color);
    }

    .company-logo-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #edf2f7;
    }

    .company-logo-img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
        object-fit: contain;
    }

    .badge-soft-primary {
        background-color: rgba(0, 137, 123, 0.1);
        color: var(--primary-color);
    }

    .badge-soft-info {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .meta-item {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
    }

    .meta-item i {
        margin-right: 6px;
        font-size: 1.1rem;
    }

    .job-title-link {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.2rem;
        text-decoration: none;
        transition: color 0.2s;
    }

    .job-title-link:hover {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .filter-bar {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        margin-top: -50px; /* Overlap hero */
        position: relative;
        z-index: 10;
        border: 1px solid #edf2f7;
    }

</style>

<!-- Hero Section -->
<div class="jobs-hero">
    <div class="container text-center">
        <h1 class="font-weight-bold display-4 mb-3">Find Your Ideal Healthcare Role</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 600px;">
            Connect with top clinics, hospitals, and medical centers offering the best opportunities for therapists and specialists.
        </p>
    </div>
</div>

<div class="container pb-5">
    
    <!-- Search Placeholder (Visual only for now) -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="filter-bar d-flex flex-wrap align-items-center justify-content-between">
                <div class="flex-grow-1 mr-3 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="las la-search text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Search by job title or keyword...">
                    </div>
                </div>
                <div class="d-none d-md-block border-left pl-3 mr-3">
                     <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0"><i class="las la-map-marker text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Location">
                    </div>
                </div>
                <button class="btn btn-primary px-4 rounded-pill font-weight-bold shadow-sm">Search Jobs</button>
            </div>
        </div>
    </div>

    <!-- Job List -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold mb-0 text-dark">Latest Opportunities</h4>
                <span class="text-muted small">{{ $jobs instanceof \Illuminate\Pagination\LengthAwarePaginator ? $jobs->total() : $jobs->count() }} jobs found</span>
            </div>

            @forelse($jobs as $job)
                <div class="job-card-row p-4 d-md-flex align-items-center justify-content-between">
                    <div class="d-md-flex align-items-center mb-3 mb-md-0">
                        <!-- Company Logo -->
                        <div class="company-logo-wrapper mr-4 mb-3 mb-md-0 flex-shrink-0">
                            @if($job->clinic && $job->clinic->image_url)
                                <img src="{{ $job->clinic->image_url }}" alt="{{ $job->clinic->name }}" class="company-logo-img">
                            @else
                                <i class="las la-hospital text-muted fa-2x"></i>
                            @endif
                        </div>
                        
                        <!-- Job Info -->
                        <div>
                            <h5 class="mb-1">
                                <a href="{{ route('web.jobs.show', $job->id) }}" class="job-title-link">{{ $job->title }}</a>
                            </h5>
                            <div class="mb-2">
                                <span class="font-weight-bold text-dark mr-2">
                                    <i class="las la-building text-muted"></i> {{ $job->clinic->name ?? 'Confidential' }}
                                </span>
                            </div>
                            
                            <div class="d-flex flex-wrap">
                                <span class="meta-item mr-3">
                                    <i class="las la-map-marker"></i> {{ $job->location ?? 'Remote' }}
                                </span>
                                <span class="meta-item mr-3">
                                    <i class="las la-clock"></i> {{ $job->employment_type ?? 'Full Time' }}
                                </span>
                                @if(isset($job->salary_range))
                                    <span class="meta-item">
                                        <i class="las la-wallet"></i> {{ $job->salary_range }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions / Tags -->
                    <div class="text-md-right mt-3 mt-md-0 flex-shrink-0 ml-md-4">
                        <div class="mb-2">
                            <span class="badge {{ $job->type == 'job' ? 'badge-soft-primary' : 'badge-soft-info' }} px-3 py-2 rounded-pill">
                                {{ ucfirst($job->type) }}
                            </span>
                            @if(isset($job->match_score) && $job->match_score > 0)
                                <span class="badge badge-warning text-white px-2 py-1 rounded ml-1" title="Match Score">
                                    <i class="las la-star"></i> {{ round($job->match_score) }}%
                                </span>
                            @endif
                        </div>
                        <small class="text-muted d-block mb-2">{{ $job->created_at->diffForHumans() }}</small>
                        <a href="{{ route('web.jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">See Details</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-light rounded-lg">
                    <img src="{{ asset('web/assets/images/empty-state.svg') }}" alt="No Data" style="max-width: 150px; filter: grayscale(100%) opacity(0.5);" class="mb-3">
                    <h5 class="text-muted">No active job listings found.</h5>
                    <p class="text-muted small">Please check back later or update your search criteria.</p>
                </div>
            @endforelse

            <div class="mt-5 d-flex justify-content-center">
                 @if($jobs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $jobs->links() }}
                 @endif
            </div>
        </div>
    </div>
</div>
@endsection

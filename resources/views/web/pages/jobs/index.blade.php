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
        --primary-color: #02767F;
        --secondary-color: #10b8c4;
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
        border-radius: 20px; /* More rounded corners */
        background: white;
        margin-bottom: 16px; /* Reduced spacing */
        position: relative;
        overflow: hidden;
        padding: 20px; /* Reduced from p-4 */
    }

    .job-card-row:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px -6px rgba(2, 118, 127, 0.15); /* Enhanced shadow with brand color */
        border-color: var(--primary-color);
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
        border-radius: 20px 0 0 20px; /* Match card radius */
    }

    .job-card-row:hover::before {
        background: var(--primary-color);
    }

    .company-logo-wrapper {
        width: 60px; /* Reduced from 80px */
        height: 60px; /* Reduced from 80px */
        border-radius: 16px; /* More rounded */
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #edf2f7;
        flex-shrink: 0;
    }

    .company-logo-img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
        object-fit: contain;
    }

    .badge-soft-primary {
        background-color: rgba(2, 118, 127, 0.1);
        color: var(--primary-color);
    }

    .badge-soft-info {
        background-color: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }

    .meta-item {
        font-size: 0.85rem; /* Reduced from 0.9rem */
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
    }

    .meta-item i {
        margin-right: 4px; /* Reduced from 6px */
        font-size: 0.9rem; /* Reduced from 1.1rem */
    }
    
    .badge-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .job-title-link {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.1rem; /* Reduced from 1.2rem */
        text-decoration: none;
        transition: color 0.2s;
        line-height: 1.4;
    }

    .job-title-link:hover {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .filter-bar {
        background: white;
        padding: 25px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(2, 118, 127, 0.15);
        margin-top: -60px; /* Overlap hero */
        position: relative;
        z-index: 10;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
    }
    
    .filter-bar:hover {
        box-shadow: 0 15px 40px rgba(2, 118, 127, 0.2);
    }
    
    .filter-bar .form-control {
        font-size: 1rem;
        padding: 12px 15px;
    }
    
    .filter-bar .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(2, 118, 127, 0.1);
    }
    
    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(255,255,255,0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255,255,255,0.2) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .stat-box {
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    
    .stat-box:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .benefits-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 60px 0;
        margin: 40px 0;
    }
    
    .benefit-card {
        text-align: center;
        padding: 30px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #f0f0f0;
    }
    
    .benefit-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(2, 118, 127, 0.15);
        border-color: var(--primary-color);
    }
    
    .benefit-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
    }
    
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }
    
    .empty-state-icon {
        width: 200px;
        height: 200px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        color: var(--text-muted);
    }
    
    .cta-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 60px 0;
        margin: 60px 0;
        border-radius: 20px;
        color: white;
        text-align: center;
    }
    
    .animate-fade-in {
        animation: fadeIn 1s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .search-btn {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        padding: 12px 35px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 118, 127, 0.3);
    }

</style>

<!-- Hero Section -->
<div class="jobs-hero">
    <div class="container text-center position-relative">
        <!-- Decorative Elements -->
        <div class="hero-pattern"></div>
        
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto">
                <div class="hero-content">
                    <h1 class="font-weight-bold display-4 mb-4 animate-fade-in">
                        {{ __('Find Your Ideal Healthcare Role') }}
                    </h1>
                    <p class="lead text-white-50 mx-auto mb-5" style="max-width: 700px; font-size: 1.25rem;">
                        {{ __('Connect with top clinics, hospitals, and medical centers offering the best opportunities for therapists and specialists.') }}
                    </p>
                    
                    <!-- Trust Indicators -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <div class="stat-box">
                                <div class="stat-number text-white font-weight-bold" style="font-size: 2rem;">{{ $jobs->total() ?? 0 }}+</div>
                                <div class="stat-label text-white-50 small">{{ __('Active Jobs') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <div class="stat-box">
                                <div class="stat-number text-white font-weight-bold" style="font-size: 2rem;">100+</div>
                                <div class="stat-label text-white-50 small">{{ __('Verified Clinics') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-box">
                                <div class="stat-number text-white font-weight-bold" style="font-size: 2rem;">24/7</div>
                                <div class="stat-label text-white-50 small">{{ __('Support') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-box">
                                <div class="stat-number text-white font-weight-bold" style="font-size: 2rem;">100%</div>
                                <div class="stat-label text-white-50 small">{{ __('Free to Apply') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    
    <!-- Enhanced Search Bar -->
    <div class="row mb-5">
        <div class="col-lg-11 mx-auto">
            <form action="{{ route('web.jobs.index') }}" method="GET" class="filter-bar d-flex flex-wrap align-items-center justify-content-between">
                <div class="flex-grow-1 mr-3 mb-2 mb-md-0">
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="las la-search text-primary"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="{{ __('Search by job title or keyword...') }}" 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="d-none d-md-block border-left pl-3 mr-3" style="min-width: 200px;">
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="las la-map-marker text-primary"></i></span>
                        </div>
                        <input type="text" name="location" class="form-control border-left-0" 
                               placeholder="{{ __('Location') }}" 
                               value="{{ request('location') }}">
                    </div>
                </div>
                <button type="submit" class="btn search-btn px-4 rounded-pill font-weight-bold text-white shadow-lg">
                    <i class="las la-search mr-2"></i>{{ __('Search Jobs') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Job List -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="font-weight-bold mb-0 text-dark">{{ __('Latest Opportunities') }}</h4>
                <span class="text-muted small">{{ $jobs instanceof \Illuminate\Pagination\LengthAwarePaginator ? $jobs->total() : $jobs->count() }} jobs found</span>
            </div>

            @forelse($jobs as $job)
                <div class="job-card-row d-md-flex align-items-center justify-content-between">
                    <div class="d-md-flex align-items-center mb-3 mb-md-0 flex-grow-1">
                        <!-- Company Logo -->
                        <div class="company-logo-wrapper mr-3 mb-3 mb-md-0 flex-shrink-0">
                            @if($job->clinic && $job->clinic->image_url)
                                <img src="{{ $job->clinic->image_url }}" alt="{{ $job->clinic->name }}" class="company-logo-img">
                            @else
                                <i class="las la-hospital text-muted" style="font-size: 1.5rem;"></i>
                            @endif
                        </div>
                        
                        <!-- Job Info -->
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        <a href="{{ route('web.jobs.show', $job->id) }}" class="job-title-link">{{ $job->title }}</a>
                                    </h5>
                                    <div class="mb-2">
                                        <span class="font-weight-semibold text-dark" style="font-size: 0.95rem;">
                                            <i class="las la-building text-muted" style="font-size: 0.9rem;"></i> {{ $job->clinic->name ?? __('Confidential') }}
                                        </span>
                                    </div>
                                </div>
                                @if($job->urgency_level == 'urgent')
                                    <span class="badge badge-danger ml-2 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="las la-fire"></i> {{ __('Urgent') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <span class="meta-item mr-3 mb-1">
                                    <i class="las la-map-marker"></i> <span style="font-size: 0.85rem;">{{ $job->location ?? __('Remote') }}</span>
                                </span>
                                <span class="meta-item mr-3 mb-1">
                                    <i class="las la-clock"></i> <span style="font-size: 0.85rem;">{{ ucfirst($job->type ?? 'Full Time') }}</span>
                                </span>
                                @if($job->salary_range)
                                    <span class="meta-item mb-1">
                                        <i class="las la-wallet"></i> <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary-color);">{{ $job->salary_range }}</span>
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="d-flex flex-wrap align-items-center">
                                @if($job->experience_level)
                                    <span class="badge badge-light mr-2 mb-1 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="las la-user-graduate"></i> {{ ucfirst($job->experience_level) }}
                                    </span>
                                @endif
                                @if($job->specialty && is_array($job->specialty) && count($job->specialty) > 0)
                                    @foreach(array_slice($job->specialty, 0, 2) as $spec)
                                        <span class="badge badge-soft-primary mr-2 mb-1 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                            {{ ucfirst(str_replace('_', ' ', $spec)) }}
                                        </span>
                                    @endforeach
                                @endif
                                <small class="text-muted ml-auto" style="font-size: 0.8rem;">
                                    <i class="las la-calendar"></i> {{ $job->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions / Tags -->
                    <div class="text-md-right mt-3 mt-md-0 flex-shrink-0 ml-md-3" style="min-width: 140px;">
                        <div class="mb-2 d-flex flex-wrap justify-content-md-end">
                            <span class="badge {{ $job->type == 'job' ? 'badge-soft-primary' : ($job->type == 'part-time' ? 'badge-soft-info' : 'badge-secondary') }} px-2 py-1 rounded-pill mb-1" style="font-size: 0.75rem;">
                                {{ ucfirst(str_replace('-', ' ', $job->type ?? 'job')) }}
                            </span>
                            @if(isset($job->match_score) && $job->match_score > 0)
                                <span class="badge badge-warning text-white px-2 py-1 rounded-pill ml-1 mb-1" style="font-size: 0.75rem;" title="{{ __('Match Score') }}">
                                    <i class="las la-star"></i> {{ round($job->match_score) }}%
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('web.jobs.show', $job->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size: 0.85rem; background-color: var(--primary-color); border-color: var(--primary-color);">
                            <i class="las la-eye mr-1"></i>{{ __('View') }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="las la-briefcase"></i>
                    </div>
                    <h3 class="font-weight-bold text-dark mb-3">{{ __('No Job Listings Found') }}</h3>
                    <p class="text-muted mb-4" style="font-size: 1.1rem;">
                        {{ __('We couldn\'t find any active job listings matching your criteria.') }}
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('web.jobs.index') }}" class="btn btn-outline-primary px-4 rounded-pill">
                            <i class="las la-redo mr-2"></i>{{ __('View All Jobs') }}
                        </a>
                        @auth
                            @if(auth()->user()->type === 'company' || auth()->user()->type === 'clinic')
                                <a href="{{ route('clinic.jobs.create') }}" class="btn btn-primary px-4 rounded-pill">
                                    <i class="las la-plus mr-2"></i>{{ __('Post a Job') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforelse

            <div class="mt-5 d-flex justify-content-center">
                 @if($jobs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $jobs->links() }}
                 @endif
            </div>
        </div>
    </div>
    
    <!-- Benefits Section -->
    <div class="benefits-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-dark mb-3">{{ __('Why Choose Phyzioline Jobs?') }}</h2>
                <p class="text-muted lead">{{ __('Your career growth starts here') }}</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="las la-shield-alt"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">{{ __('Verified Employers') }}</h5>
                        <p class="text-muted small mb-0">{{ __('All clinics and hospitals are verified and trusted') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="las la-bolt"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">{{ __('Quick Matching') }}</h5>
                        <p class="text-muted small mb-0">{{ __('AI-powered matching connects you with perfect opportunities') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="las la-lock"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">{{ __('Secure & Private') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Your information is protected and confidential') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="las la-headset"></i>
                        </div>
                        <h5 class="font-weight-bold mb-3">{{ __('24/7 Support') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Our team is always here to help you succeed') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action Section -->
    @if($jobs->count() > 0)
    <div class="container">
        <div class="cta-section">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="font-weight-bold mb-3 text-white">{{ __('Ready to Start Your Career Journey?') }}</h2>
                    <p class="lead text-white-50 mb-4">
                        {{ __('Join thousands of healthcare professionals who found their dream job through Phyzioline') }}
                    </p>
                    @auth
                        @if(auth()->user()->type === 'therapist')
                            <a href="{{ route('therapist.profile.edit') }}" class="btn btn-light btn-lg px-5 rounded-pill font-weight-bold shadow-lg">
                                <i class="las la-user-edit mr-2"></i>{{ __('Complete Your Profile') }}
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 rounded-pill font-weight-bold shadow-lg">
                                <i class="las la-user-plus mr-2"></i>{{ __('Create Account') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 rounded-pill font-weight-bold shadow-lg">
                            <i class="las la-user-plus mr-2"></i>{{ __('Get Started Free') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection


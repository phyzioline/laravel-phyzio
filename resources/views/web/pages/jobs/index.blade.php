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
        padding: 25px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 137, 123, 0.15);
        margin-top: -60px; /* Overlap hero */
        position: relative;
        z-index: 10;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
    }
    
    .filter-bar:hover {
        box-shadow: 0 15px 40px rgba(0, 137, 123, 0.2);
    }
    
    .filter-bar .form-control {
        font-size: 1rem;
        padding: 12px 15px;
    }
    
    .filter-bar .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 137, 123, 0.1);
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
        box-shadow: 0 12px 30px rgba(0, 137, 123, 0.15);
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
        box-shadow: 0 8px 20px rgba(0, 137, 123, 0.3);
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


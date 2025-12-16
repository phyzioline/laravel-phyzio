@extends('web.layouts.app')

@section('title', 'Career Opportunities')

@push('meta')
    <meta name="description" content="{{ __('Find the best physical therapy jobs and medical career opportunities. Connect with top clinics and hospitals.') }}">
    <meta name="keywords" content="Medical Jobs, Physical Therapy Jobs, وظائف طبية, توظيف, Physiotherapist Jobs, وظائف علاج طبيعي, Healthcare Careers">
@endpush

@section('content')
<div class="page-header" style="background: #00897b; padding: 60px 0; margin-top: 130px;">
    <div class="container">
        <h1 class="text-white font-weight-bold display-4">Career Opportunities</h1>
        <p class="text-white-50 lead">Find your next job or training program in healthcare.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @forelse($jobs as $job)
                <div class="card shadow-sm border-0 mb-4 job-card" style="transition: transform 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="font-weight-bold text-dark mb-2">{{ $job->title }}</h3>
                                <div class="mb-3">
                                    <span class="badge badge-{{ $job->type == 'job' ? 'success' : 'info' }} mr-2 px-3 py-2">
                                        {{ ucfirst($job->type) }}
                                    </span>
                                    @if(isset($job->match_score) && $job->match_score > 0)
                                        <span class="badge badge-warning mr-2 px-3 py-2" title="Match Score based on your profile">
                                            <i class="las la-star"></i> {{ round($job->match_score) }}% Match
                                        </span>
                                    @endif
                                    <span class="text-muted mr-3"><i class="las la-map-marker"></i> {{ $job->location ?? 'Remote' }}</span>
                                    <span class="text-muted"><i class="las la-wallet"></i> {{ $job->salary_range ?? 'Negotiable' }}</span>
                                </div>
                            </div>
                            @if($job->clinic && $job->clinic->image_url)
                                <img src="{{ $job->clinic->image_url }}" alt="Clinic Logo" class="rounded-circle" width="60" height="60">
                            @endif
                        </div>
                        
                        <p class="text-muted mb-4">
                            {{ Str::limit($job->description, 200) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Posted {{ $job->created_at->diffForHumans() }} by {{ $job->clinic->name ?? 'A Clinic' }}</small>
                            <a href="{{ route('web.jobs.show', $job->id) }}" class="btn btn-outline-primary">{{ __('View Details') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <img src="{{ asset('web/assets/images/empty-state.svg') }}" alt="No Jobs" class="mb-4" style="max-width: 200px; opacity: 0.5;">
                    <h3>No opportunities available right now.</h3>
                    <p class="text-muted">Check back later for new postings.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

@extends('web.layouts.app')

@section('title', 'Phyzioline Academy - Browse Courses')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section-courses py-5 position-relative" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); padding-top: 180px !important; padding-bottom: 100px !important;">
        <div class="container text-center text-white pt-5 pb-4">
            <div class="hero-badge mb-3">
                <span class="badge badge-light px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);">
                    <i class="las la-graduation-cap mr-1"></i> {{ __('Professional Development') }}
                </span>
            </div>
            <h1 class="font-weight-bold display-4 mb-4 animate__animated animate__fadeInDown">
                {{ __('Advance Your Physiotherapy Career') }}
            </h1>
            <p class="lead mx-auto mb-5 opacity-90 animate__animated animate__fadeInUp" style="max-width: 700px; font-size: 1.25rem;">
                {{ __('Learn from world-class experts. Video courses, certifications, and practical workshops to elevate your practice.') }}
            </p>
            
            <!-- Trust Indicators -->
            <div class="d-flex justify-content-center flex-wrap mb-5 animate__animated animate__fadeInUp animate__delay-1s">
                <div class="stat-item mx-3 mb-2">
                    <h4 class="font-weight-bold text-white mb-0">{{ $courses->total() ?? 0 }}+</h4>
                    <div class="stat-label text-white-50 small">{{ __('Expert Courses') }}</div>
                </div>
                <div class="stat-item mx-3 mb-2">
                    <h4 class="font-weight-bold text-white mb-0">100+</h4>
                    <div class="stat-label text-white-50 small">{{ __('Certified Instructors') }}</div>
                </div>
                <div class="stat-item mx-3 mb-2">
                    <h4 class="font-weight-bold text-white mb-0">10K+</h4>
                    <div class="stat-label text-white-50 small">{{ __('Students Enrolled') }}</div>
                </div>
                <div class="stat-item mx-3 mb-2">
                    <h4 class="font-weight-bold text-white mb-0">4.8★</h4>
                    <div class="stat-label text-white-50 small">{{ __('Average Rating') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Search Bar -->
        <div class="container search-bar-container animate__animated animate__fadeInUp animate__delay-1s">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('web.courses.index') }}" method="GET" class="search-form p-3 p-md-4 rounded-lg shadow-lg">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-0"><i class="las la-search text-primary"></i></span>
                            </div>
                            <input type="text" name="search" class="form-control border-0" 
                                   placeholder="{{ __('Search for courses, topic, or instructor...') }}" 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill font-weight-bold shadow-sm search-button">
                                    {{ __('Search Courses') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="font-weight-bold text-center mb-5" style="color: #02767F;">{{ __('Why Choose Phyzioline Academy?') }}</h2>
            <div class="row text-center">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-certificate la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Certified Courses') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Earn recognized certificates upon completion') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-user-tie la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Expert Instructors') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Learn from industry-leading professionals') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-video la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Video Content') }}</h5>
                        <p class="text-muted small mb-0">{{ __('High-quality video lessons and demonstrations') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-sm shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-clock la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Lifetime Access') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Learn at your own pace, anytime, anywhere') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="bg-light py-5">
        <div class="container">

        <!-- Featured Courses Section (Only if not searching) -->
        @if(!request()->has('search') && $featuredCourses->count() > 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="font-weight-bold mb-2" style="color: #02767F;">{{ __('Featured Courses') }}</h3>
                        <p class="text-muted mb-0">{{ __('Top rated courses by our community') }}</p>
                    </div>
                    <a href="{{ route('web.courses.index') }}" class="font-weight-bold text-primary">
                        {{ __('View All') }} <i class="las la-arrow-right"></i>
                    </a>
                </div>
                <div class="row">
                    @foreach($featuredCourses->take(3) as $feat)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 course-card-hover">
                                <div class="position-relative">
                                    <img src="{{ $feat->thumbnail ? Storage::url($feat->thumbnail) : 'https://via.placeholder.com/600x400?text=Course' }}" 
                                         class="card-img-top" alt="{{ $feat->title }}" 
                                         style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                                    @if($feat->discount_price)
                                        <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px; font-size: 0.85rem;">
                                            {{ __('Sale') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <span class="badge badge-light text-primary mb-2" style="background-color: rgba(2, 118, 127, 0.1); color: #02767F;">
                                        {{ ucfirst($feat->level ?? 'All Levels') }}
                                    </span>
                                    <h5 class="card-title font-weight-bold mb-2" style="min-height: 50px;">
                                        <a href="{{ route('web.courses.show', $feat->id) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($feat->title, 60) }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        <i class="las la-chalkboard-teacher"></i> {{ $feat->instructor->name ?? __('Instructor') }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <div class="text-warning small">
                                            <i class="las la-star"></i> 4.8 <span class="text-muted">(120)</span>
                                        </div>
                                        <h5 class="font-weight-bold mb-0" style="color: #02767F;">
                                            @if($feat->discount_price)
                                                {{ $feat->discount_price }} <small class="text-muted"><del>{{ $feat->price }}</del></small>
                                            @elseif($feat->price > 0)
                                                {{ $feat->price }} {{ __('EGP') }}
                                            @else
                                                {{ __('Free') }}
                                            @endif
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white font-weight-bold">
                        <i class="las la-filter mr-1"></i> {{ __('Filter Results') }}
                    </div>
                    <div class="card-body p-0">
                        <form action="{{ route('web.courses.index') }}" method="GET" id="filterForm">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            
                            <!-- Categories -->
                            <div class="p-3 border-bottom">
                                <h6 class="font-weight-bold mb-2">{{ __('Category') }}</h6>
                                @foreach($categories as $cat)
                                    <div class="custom-control custom-radio mb-1">
                                        <input type="radio" id="cat{{ $cat->id }}" name="category" value="{{ $cat->id }}" class="custom-control-input" onchange="this.form.submit()" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                        <label class="custom-control-label small" for="cat{{ $cat->id }}">{{ $cat->{'name_' . app()->getLocale()} ?? $cat->name_en }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Level -->
                            <div class="p-3 border-bottom">
                                <h6 class="font-weight-bold mb-2">{{ __('Level') }}</h6>
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" name="level" value="Beginner" class="custom-control-input" onchange="this.form.submit()" {{ request('level') == 'Beginner' ? 'checked' : '' }} id="lvl1">
                                    <label class="custom-control-label small" for="lvl1">{{ __('Beginner') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" name="level" value="Intermediate" class="custom-control-input" onchange="this.form.submit()" {{ request('level') == 'Intermediate' ? 'checked' : '' }} id="lvl2">
                                    <label class="custom-control-label small" for="lvl2">{{ __('Intermediate') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" name="level" value="Advanced" class="custom-control-input" onchange="this.form.submit()" {{ request('level') == 'Advanced' ? 'checked' : '' }} id="lvl3">
                                    <label class="custom-control-label small" for="lvl3">{{ __('Advanced') }}</label>
                                </div>
                            </div>
                            
                            <!-- Submit Button (Hidden but functional for reset) -->
                             <div class="p-3 text-center">
                                 <a href="{{ route('web.courses.index') }}" class="btn btn-sm btn-outline-secondary btn-block">{{ __('Clear Filters') }}</a>
                             </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Course Grid -->
            <div class="col-lg-9">
                <!-- Sorting Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0 text-muted">{{ $courses->total() }} {{ __('results found') }}</p>
                    <div>
                         <select class="custom-select custom-select-sm" style="width: 180px;" onchange="window.location.href=this.value">
                             <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                             <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                             <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                             <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('Highest Rated') }}</option>
                         </select>
                    </div>
                </div>

                <div class="row">
                    @forelse($courses as $course)
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                                <a href="{{ route('web.courses.show', $course->id) }}" class="text-decoration-none">
                                    <div class="position-relative">
                                        <img src="{{ $course->thumbnail ? Storage::url($course->thumbnail) : 'https://via.placeholder.com/600x400?text=Phyzioline' }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                                        <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); padding: 20px 15px 5px;">
                                             <small class="text-white"><i class="las la-clock"></i> {{ $course->duration_minutes ?? 60 }} mins</small>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-dark mb-1" style="line-height: 1.4; height: 42px; overflow: hidden;">{{ Str::limit($course->title, 50) }}</h6>
                                        <p class="text-muted small mb-2">{{ $course->instructor->name }}</p>
                                        
                                        <div class="d-flex align-items-center small mb-3">
                                            <span class="text-warning mr-1">4.5</span>
                                            <div class="text-warning">
                                                <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star-half-alt"></i>
                                            </div>
                                            <span class="text-muted ml-1">(42)</span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                            <h5 class="font-weight-bold text-primary mb-0">
                                                {{ $course->price > 0 ? $course->price . ' EGP' : 'Free' }}
                                            </h5>
                                            <span class="text-primary"><i class="las la-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                             <img src="https://cdni.iconscout.com/illustration/premium/thumb/no-search-result-2563364-2136005.png" width="200" class="opacity-50">
                             <h4 class="mt-3">{{ __('No courses found') }}</h4>
                             <p class="text-muted">{{ __('Try adjusting your filters or search query.') }}</p>
                             <a href="{{ route('web.courses.index') }}" class="btn btn-primary mt-2">{{ __('View All Courses') }}</a>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);">
        <div class="container text-center text-white">
            <h2 class="font-weight-bold mb-3">{{ __('Ready to Start Your Learning Journey?') }}</h2>
            <p class="lead mb-4 opacity-90">{{ __('Join thousands of therapists advancing their careers with our expert-led courses.') }}</p>
            <a href="{{ route('instructor.' . app()->getLocale() . '.courses.create.' . app()->getLocale()) }}" class="btn btn-light btn-lg px-5 rounded-pill font-weight-bold shadow-lg mr-3">
                {{ __('Become an Instructor') }}
            </a>
            <a href="{{ route('web.courses.index') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill font-weight-bold">
                {{ __('Browse All Courses') }}
            </a>
        </div>
    </section>
</main>

<style>
    /* Hero Section Styling */
    .hero-section-courses {
        position: relative;
        overflow: hidden;
    }

    .hero-section-courses::before {
        content: '';
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

    .stat-item {
        text-align: center;
    }

    .stat-label {
        font-size: 0.85rem;
    }

    /* Search Bar Styling */
    .search-bar-container {
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    .search-form {
        background: white;
        border: 1px solid rgba(2, 118, 127, 0.1);
    }

    .search-button {
        background-color: #02767F !important;
        border-color: #02767F !important;
        transition: all 0.3s ease;
    }

    .search-button:hover {
        background-color: #10b8c4 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(2, 118, 127, 0.3) !important;
    }

    /* Benefit Cards */
    .benefit-icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #02767F, #10b8c4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: white;
        transition: transform 0.3s ease;
    }

    .benefit-card:hover .benefit-icon-circle {
        transform: scale(1.1) rotate(5deg);
    }

    .benefit-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(2, 118, 127, 0.1);
    }

    .benefit-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(2, 118, 127, 0.15) !important;
        border-color: #02767F;
    }

    /* Course Cards */
    .course-card-hover {
        transition: all 0.3s ease;
        border: 1px solid rgba(2, 118, 127, 0.1);
    }

    .course-card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(2, 118, 127, 0.15) !important;
        border-color: #02767F;
    }

    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(2, 118, 127, 0.2) !important;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }

    /* Fix header overlap */
    body {
        padding-top: 120px !important;
    }

    @media (max-width: 991px) {
        body {
            padding-top: 100px !important;
        }
    }

    @media (max-width: 768px) {
        body {
            padding-top: 90px !important;
        }
    }

    header,
    .header-section {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        background: transparent !important;
        box-shadow: none !important;
        z-index: 9999;
    }

    .hero-section-courses {
        margin-top: 0 !important;
    }
</style>
@endsection

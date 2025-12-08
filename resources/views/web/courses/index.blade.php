@extends('web.layouts.app')

@section('title', __('Courses'))

@section('content')
<div class="courses-page-wrapper bg-light">
    
    <!-- 1. Header Section: Search & Filters -->
    <div class="courses-header bg-white shadow-sm py-4">
        <div class="container">
            <h2 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Explore Our Courses') }}</h2>
            
            <form action="{{ route('web.courses.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Search Bar -->
                    <div class="col-lg-5 col-md-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0" style="border-color: #0d9488;"><i class="las la-search text-teal-600"></i></span>
                            </div>
                            <input type="text" name="search" class="form-control border-left-0 border-teal" placeholder="{{ __('Search courses, specialties, instructors...') }}" value="{{ request('search') }}" style="border-color: #0d9488;">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-lg-3 col-md-6 mb-2">
                        <select name="category" class="form-control border-teal" style="border-color: #0d9488;">
                            <option value="">{{ __('All Categories') }}</option>
                            <!-- Dynamic Categories would go here -->
                            <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>Orthopedic</option>
                            <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>Neurology</option>
                            <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="4" {{ request('category') == '4' ? 'selected' : '' }}>Sports</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-lg-3 col-md-6 mb-2">
                        <select name="sort" class="form-control border-teal" style="border-color: #0d9488;">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="col-lg-1 col-md-12 mb-2">
                        <button type="submit" class="btn btn-teal w-100 text-white" style="background-color: #0d9488;">{{ __('Go') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Featured Courses Banner (Carousel) -->
    @if($featuredCourses->count() > 0)
    <div class="featured-section py-5">
        <div class="container">
            <h4 class="font-weight-bold mb-4 text-dark">{{ __('Featured Courses') }}</h4>
            <div id="featuredCarousel" class="carousel slide shadow rounded overflow-hidden" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach($featuredCourses as $key => $course)
                        <li data-target="#featuredCarousel" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($featuredCourses as $key => $course)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="d-flex bg-white" style="min-height: 300px;">
                            <div class="w-50 d-none d-md-block" style="background-image: url('{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('web/assets/images/course-placeholder.jpg') }}'); background-size: cover; background-position: center;"></div>
                            <div class="w-100 w-md-50 p-5 d-flex flex-column justify-content-center">
                                <span class="badge badge-teal mb-2 w-auto" style="background-color: #0d9488; color: white; width: fit-content;">{{ $course->category->name ?? __('General') }}</span>
                                <h2 class="font-weight-bold mb-2">{{ $course->title }}</h2>
                                <p class="text-muted mb-3">{{ Str::limit($course->description, 100) }}</p>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="mr-3">
                                        <i class="las la-user-circle text-muted" style="font-size: 1.5rem;"></i>
                                        <span class="font-weight-bold">{{ $course->instructor->name ?? __('Instructor') }}</span>
                                    </div>
                                    <h4 class="text-teal-700 m-0 font-weight-bold" style="color: #0d9488;">{{ $course->price > 0 ? $course->price . ' EGP' : __('Free') }}</h4>
                                </div>
                                <a href="{{ route('web.courses.show', $course->id) }}" class="btn btn-teal btn-lg text-white" style="background-color: #0d9488; width: fit-content;">{{ __('View Details') }}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#featuredCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#featuredCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. Courses Grid -->
    <div class="courses-grid py-5">
        <div class="container">
            <div class="row">
                @forelse($courses as $course)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="position-relative">
                             <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('web/assets/images/course-placeholder.jpg') }}" class="card-img-top" alt="{{ $course->title }}" style="height: 180px; object-fit: cover;">
                             <span class="badge badge-light position-absolute m-2" style="top:0; right:0;">{{ $course->level ?? 'All Levels' }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold mb-2">
                                <a href="{{ route('web.courses.show', $course->id) }}" class="text-dark text-decoration-none">{{ Str::limit($course->title, 50) }}</a>
                            </h5>
                            <small class="text-muted mb-2">
                                <i class="las la-user"></i> {{ $course->instructor->name ?? __('Unknown') }}
                            </small>
                            <div class="mb-3">
                                <i class="las la-star text-warning"></i> 4.5 <small class="text-muted">(120)</small>
                            </div>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="h5 m-0 font-weight-bold text-teal-700" style="color: #0d9488;">{{ $course->price > 0 ? $course->price . ' EGP' : __('Free') }}</span>
                                <a href="{{ route('web.courses.show', $course->id) }}" class="btn btn-outline-teal btn-sm rounded-pill" style="color: #0d9488; border-color: #0d9488;">{{ __('Enroll Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="las la-search text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                    <h3 class="text-muted">{{ __('No courses found matching your criteria.') }}</h3>
                    <a href="{{ route('web.courses.index') }}" class="btn btn-link text-teal-700" style="color: #0d9488;">{{ __('Clear Filters') }}</a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $courses->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>

<style>
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .btn-teal:hover {
        background-color: #0f766e !important;
    }
    .text-teal-600 { color: #0d9488 !important; }
</style>
@endsection

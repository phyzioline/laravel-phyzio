@extends('web.layouts.app')

@section('title', 'Phyzioline Academy - Browse Courses')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <!-- Header & Search -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6">
                <h1 class="font-weight-bold" style="color: #00897b;">{{ __('Start Your Learning Journey') }}</h1>
                <p class="lead text-muted">{{ __('Explore hundreds of expert-led courses in physical therapy.') }}</p>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('web.courses.index') }}" method="GET">
                    <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                        <input type="text" name="search" class="form-control border-0 pl-4" placeholder="{{ __('Search for courses, topic, or instructor...') }}" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4" type="submit" style="background-color: #00897b; border-color: #00897b;">
                                <i class="las la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Featured Carousel (Only if not searching) -->
        @if(!request()->has('search'))
            <div class="mb-5">
                <h4 class="font-weight-bold mb-3">{{ __('Featured Courses') }}</h4>
                <!-- Simple Row for now, replace with owl-carousel if JS available -->
                <div class="row flex-nowrap overflow-auto pb-3" style="scroll-behavior: smooth;">
                    @foreach($featuredCourses as $feat)
                        <div class="col-lg-4 col-md-6 mb-3" style="min-width: 300px;">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="position-relative">
                                    <img src="{{ $feat->thumbnail ? Storage::url($feat->thumbnail) : 'https://via.placeholder.com/600x400?text=Course' }}" class="card-img-top" alt="{{ $feat->title }}" style="height: 180px; object-fit: cover;">
                                    @if($feat->discount_price)
                                        <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px;">{{ __('Sale') }}</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <span class="badge badge-light text-primary mb-2">{{ $feat->level }}</span>
                                    <h5 class="card-title font-weight-bold">
                                        <a href="{{ route('web.courses.show', $feat->id) }}" class="text-dark text-decoration-none">{{ $feat->title }}</a>
                                    </h5>
                                    <p class="text-muted small mb-2"><i class="las la-chalkboard-teacher"></i> {{ $feat->instructor->name ?? 'Instructor' }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="text-warning small">
                                            <i class="las la-star"></i> 4.8 (120)
                                        </span>
                                        <h5 class="font-weight-bold mb-0 text-primary">
                                            @if($feat->discount_price)
                                                {{ $feat->discount_price }} <small class="text-muted"><del>{{ $feat->price }}</del></small>
                                            @elseif($feat->price > 0)
                                                {{ $feat->price }} EGP
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
                                        <label class="custom-control-label small" for="cat{{ $cat->id }}">{{ $cat->name }}</label>
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

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    /* Fix header overlap specifically for courses page */
     body .bg-light.py-5 {
    margin-top: 150px !important;
    }
 
</style>
@endsection

@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('home_visits');
@endphp

@section('title', $pageMeta['title'])

@push('meta')
    <meta name="description" content="{{ $pageMeta['description'] }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $pageMeta['title'] }}">
    <meta property="og:description" content="{{ $pageMeta['description'] }}">
    <meta property="og:type" content="service">
@endpush

@push('structured-data')
<script type="application/ld+json">
@json(\App\Services\SEO\SEOService::homeVisitsSchema())
</script>
@endpush

@section('content')
<main>
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero-section py-5 position-relative" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); padding-top: 180px !important; padding-bottom: 100px !important;">
        <div class="container">
            <div class="row align-items-start">
                <!-- Left: Title & Context -->
                <div class="col-lg-5 text-white mb-5 mb-lg-0 pt-lg-5">
                    <div class="hero-badge mb-3">
                        <span class="badge badge-light px-3 py-2" style="font-size: 0.9rem; background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(10px);">
                            <i class="las la-star mr-1"></i> {{ __('Trusted by 10,000+ Patients') }}
                        </span>
                    </div>
                    <h1 class="font-weight-bold mb-4 display-4" style="line-height: 1.2;">{{ __('Home Physical Therapy') }}</h1>
                    <p class="lead mb-4 opacity-90" style="font-size: 1.3rem; line-height: 1.6;">
                        {{ __('Expert physiotherapists at your doorstep. Urgent requests or scheduled appointments.') }}
                    </p>
                    
                    <!-- Trust Stats -->
                    <div class="row mb-4">
                        <div class="col-6 col-md-4 mb-3">
                            <div class="stat-item">
                                <div class="stat-number font-weight-bold" style="font-size: 2rem;">500+</div>
                                <div class="stat-label opacity-75 small">{{ __('Verified Therapists') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="stat-item">
                                <div class="stat-number font-weight-bold" style="font-size: 2rem;">24/7</div>
                                <div class="stat-label opacity-75 small">{{ __('Available') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="stat-item">
                                <div class="stat-number font-weight-bold" style="font-size: 2rem;">4.8★</div>
                                <div class="stat-label opacity-75 small">{{ __('Average Rating') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-none d-lg-block">
                        <div class="d-flex align-items-center mb-3 p-3 rounded" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="icon-box bg-white text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center shadow-lg" style="width: 60px; height: 60px;">
                                <i class="las la-check-circle la-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ __('Certified Specialists') }}</h5>
                                <small class="opacity-75">{{ __('Verified professionals') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-3 rounded" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="icon-box bg-white text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center shadow-lg" style="width: 60px; height: 60px;">
                                <i class="las la-shield-alt la-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ __('Safe & Secure') }}</h5>
                                <small class="opacity-75">{{ __('Health & Safety protocols') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Unified Request Box -->
                <div class="col-lg-7">
                    <div class="card shadow-2xl border-0 overflow-hidden" style="border-radius: 20px;">
                        <div class="card-header bg-white p-0 border-0">
                            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active py-3 font-weight-bold rounded-0" id="pills-request-tab" data-toggle="pill" href="#pills-request" role="tab" aria-controls="pills-request" aria-selected="true" style="font-size: 1.1rem;">
                                        <i class="las la-ambulance mr-2"></i> {{ __('Request Visit') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-3 font-weight-bold rounded-0" id="pills-search-tab" data-toggle="pill" href="#pills-search" role="tab" aria-controls="pills-search" aria-selected="false" style="font-size: 1.1rem;">
                                        <i class="las la-search mr-2"></i> {{ __('Search Therapist') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body p-4 bg-light">
                            <div class="tab-content" id="pills-tabContent">
                                
                                <!-- Tab 1: Direct Request (from visits/create) -->
                                <div class="tab-pane fade show active" id="pills-request" role="tabpanel">
                                    <form action="{{ route('patient.home_visits.store') }}" method="POST" id="visitForm">
                                        @csrf
                                        
                                        <!-- Condition -->
                                        <h6 class="text-primary font-weight-bold mb-3"><i class="las la-stethoscope"></i> {{ __('Condition Type') }}</h6>
                                        <div class="row no-gutters mb-3">
                                            @php
                                                $conditions = [
                                                    'Orthopedic' => ['icon' => 'bone', 'key' => 'Orthopedic'],
                                                    'Neurological' => ['icon' => 'brain', 'key' => 'Neurological'],
                                                    'Post-Surgery' => ['icon' => 'procedure', 'key' => 'Post-Surgery'],
                                                    'Elderly' => ['icon' => 'blind', 'key' => 'Elderly'],
                                                    'Pediatric' => ['icon' => 'baby', 'key' => 'Pediatric'],
                                                    'Sports' => ['icon' => 'running', 'key' => 'Sports']
                                                ];
                                            @endphp
                                            @foreach($conditions as $label => $data)
                                                <div class="col-4 col-md-4 p-1">
                                                    <label class="btn btn-outline-white bg-white text-dark btn-block border p-2 shadow-sm complain-option mb-0 h-100 d-flex flex-column align-items-center justify-content-center" style="cursor: pointer; transition: all 0.3s ease;">
                                                        <input type="radio" name="complain_type" value="{{ $label }}" class="d-none" required>
                                                        <i class="las la-{{ $data['icon'] }} la-2x mb-1 text-primary"></i>
                                                        <small class="font-weight-bold" style="font-size: 0.75rem; line-height: 1.1;">{{ __($data['key']) }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-danger" id="complain_type_error" style="display: none;">{{ __('Please select a condition type') }}</small>

                                        <!-- Address -->
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-lg shadow-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white border-0"><i class="las la-map-marker text-danger"></i></span>
                                                </div>
                                                <input type="text" name="address" class="form-control border-0" placeholder="{{ __('Address (Area, Street, Building...)') }}" required>
                                            </div>
                                            <!-- Hidden Lat/Lng defaults -->
                                            <input type="hidden" name="lat" value="30.0444">
                                            <input type="hidden" name="lng" value="31.2357">
                                        </div>

                                        <!-- Urgency -->
                                        <div class="form-group mb-4">
                                            <div class="btn-group btn-group-toggle w-100 shadow-sm" data-toggle="buttons">
                                                <label class="btn btn-white border active py-3">
                                                    <input type="radio" name="urgency" value="urgent" checked> 
                                                    <i class="las la-exclamation-circle text-danger"></i> <span class="font-weight-bold">{{ __('ASAP') }}</span>
                                                </label>
                                                <label class="btn btn-white border py-3">
                                                    <input type="radio" name="urgency" value="normal"> 
                                                    <i class="las la-calendar text-primary"></i> <span class="font-weight-bold">{{ __('Schedule Later') }}</span>
                                                </label>
                                            </div>
                                            <input type="datetime-local" name="scheduled_at" id="scheduleInput" class="form-control mt-2 shadow-sm border-0" style="display:none;">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-lg font-weight-bold" style="background-color: #02767F; border-color: #02767F;">
                                            {{ __('Request Now') }} <i class="las la-arrow-right ml-2"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Tab 2: Search (Existing) -->
                                <div class="tab-pane fade" id="pills-search" role="tabpanel">
                                    <form action="{{ url('/home_visits') }}" method="GET">
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted">{{ __('Specialization') }}</label>
                                            <select name="specialization" class="form-control form-control-lg border-0 shadow-sm">
                                                <option value="">{{ __('All Specializations') }}</option>
                                                @foreach($specializations as $spec)
                                                    <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted">{{ __('Area / Governance') }}</label>
                                            <select name="area" class="form-control form-control-lg border-0 shadow-sm">
                                                <option value="">{{ __('All Areas') }}</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-lg font-weight-bold" style="background-color: #02767F; border-color: #02767F;">
                                            {{ __('Find Therapist') }} <i class="las la-search ml-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold text-dark mb-3">{{ __('Why Choose Home Physical Therapy?') }}</h2>
                <p class="text-muted lead">{{ __('Experience the convenience of professional care at home') }}</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-home la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Comfort of Home') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Receive treatment in the comfort of your own home') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-clock la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Flexible Scheduling') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Book appointments that fit your schedule') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-user-md la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Expert Care') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Licensed and experienced therapists') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="benefit-card text-center p-4 bg-white rounded-lg shadow-sm h-100">
                        <div class="benefit-icon-wrapper mb-3">
                            <div class="benefit-icon-circle">
                                <i class="las la-dollar-sign la-3x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="font-weight-bold mb-2">{{ __('Transparent Pricing') }}</h5>
                        <p class="text-muted small mb-0">{{ __('No hidden fees, clear pricing upfront') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Therapists List -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h5 class="font-weight-bold mb-4">{{ __('Filters') }}</h5>
                                <form action="{{ url('/home_visits') }}" method="GET">
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Gender') }}</label>
                                <select name="gender" class="form-control">
                                    <option value="">{{ __('All Genders') }}</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Availability') }}</label>
                                <select name="availability" class="form-control">
                                    <option value="">{{ __('All Availability') }}</option>
                                    <option value="today" {{ request('availability') == 'today' ? 'selected' : '' }}>{{ __('Available Today') }}</option>
                                    <option value="tomorrow" {{ request('availability') == 'tomorrow' ? 'selected' : '' }}>{{ __('Available Tomorrow') }}</option>
                                    <option value="this_week" {{ request('availability') == 'this_week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Rating') }}</label>
                                <select name="min_rating" class="form-control">
                                    <option value="">{{ __('All Ratings') }}</option>
                                    <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ {{ __('Stars') }}</option>
                                    <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>4.5+ {{ __('Stars') }}</option>
                                    <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>5 {{ __('Stars') }}</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-block btn-primary mt-4" style="background-color: #02767F; border-color: #02767F;">{{ __('Apply Filters') }}</button>
                            <a href="{{ url('/home_visits') }}" class="btn btn-block btn-outline-secondary mt-2">{{ __('Clear Filters') }}</a>
                        </form>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    @forelse($therapists as $therapist)
                    <div class="card border-0 shadow-sm mb-4 hover-card" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <!-- Profile Photo - Square with Rounded Corners -->
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <a href="{{ url('/home_visits/therapist/'.$therapist->id) }}" class="d-inline-block">
                                        @php
                                            // Use profile_photo_url accessor if available, otherwise check therapist profile_photo, then default
                                            $imageUrl = ($therapist->user && $therapist->user->profile_photo_url) 
                                                ? $therapist->user->profile_photo_url
                                                : ($therapist->profile_photo 
                                                    ? (str_starts_with($therapist->profile_photo, 'storage/') 
                                                        ? asset($therapist->profile_photo) 
                                                        : asset('storage/' . $therapist->profile_photo))
                                                    : ($therapist->profile_image 
                                                        ? (str_starts_with($therapist->profile_image, 'storage/') 
                                                            ? asset($therapist->profile_image) 
                                                            : asset('storage/' . $therapist->profile_image))
                                                        : asset('web/assets/images/default-user.png')));
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             class="img-fluid therapist-photo-square" 
                                             style="width: 200px; height: 200px; object-fit: cover; border-radius: 12px; border: 4px solid #02767F; box-shadow: 0 8px 16px rgba(2,118,127,0.2); transition: all 0.3s ease;"
                                             onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 24px rgba(2,118,127,0.3)'"
                                             onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 16px rgba(2,118,127,0.2)'"
                                             onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                                             alt="{{ $therapist->user->name ?? 'Therapist' }} {{ __('Profile Photo') }}">
                                    </a>
                                </div>
                                
                                <!-- Therapist Info -->
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div>
                                            <h4 class="font-weight-bold mb-1" style="color: #02767F;">
                                                <a href="{{ url('/home_visits/therapist/'.$therapist->id) }}" class="text-decoration-none" style="color: #02767F;">
                                                    {{ $therapist->user->name }}
                                                </a>
                                            </h4>
                                            <p class="text-muted mb-2 small" style="font-size: 0.95rem;">
                                                <i class="las la-user-md" style="color: #02767F;"></i> 
                                                <strong>{{ $therapist->specialization }}</strong>
                                            </p>
                                        </div>
                                        <!-- Availability Badge -->
                                        <span class="badge badge-success px-3 py-2" style="background-color: #10b8c4; border-radius: 20px;">
                                            <i class="las la-check-circle"></i> {{ __('Available') }}
                                        </span>
                                    </div>
                                    
                                    <!-- Rating -->
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? 'text-warning' : 'text-muted' }}" style="font-size: 1.1rem;"></i>
                                            @endfor
                                            <span class="text-muted small ml-2">
                                                <strong>{{ number_format($therapist->rating ?? 0, 1) }}</strong> 
                                                ({{ $therapist->total_reviews ?? 0 }} {{ __('reviews') }})
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Details with Icons -->
                                    <div class="mb-2">
                                        <p class="mb-1 text-muted small" style="font-size: 0.9rem;">
                                            <i class="las la-briefcase" style="color: #02767F; width: 20px;"></i> 
                                            <strong>{{ $therapist->years_experience ?? 0 }}+</strong> {{ __('Years Experience') }}
                                        </p>
                                        <p class="mb-1 text-muted small" style="font-size: 0.9rem;">
                                            <i class="las la-map-marker-alt" style="color: #02767F; width: 20px;"></i> 
                                            <strong>{{ __('Available in') }}:</strong> 
                                            {{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 2)) }}
                                            @if(count($therapist->available_areas ?? []) > 2)
                                                <span class="text-primary">+{{ count($therapist->available_areas) - 2 }} {{ __('more') }}</span>
                                            @endif
                                        </p>
                                        <p class="mb-1 text-muted small" style="font-size: 0.9rem;">
                                            <i class="las la-clock" style="color: #10b8c4; width: 20px;"></i> 
                                            <strong>{{ __('Response Time') }}:</strong> {{ __('Within 2 hours') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Bio Preview -->
                                    @if($therapist->bio)
                                    <p class="mb-0 small text-muted" style="font-size: 0.85rem; line-height: 1.5;">
                                        {{ Str::limit($therapist->bio, 120) }}
                                    </p>
                                    @endif
                                </div>
                                
                                <!-- Pricing & Actions -->
                                <div class="col-md-3 text-center">
                                    <div class="pricing-box p-3 mb-3" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); border-radius: 10px; color: white;">
                                        <span class="d-block text-white-50 small mb-1" style="font-size: 0.85rem;">{{ __('Home Visit Fees') }}</span>
                                        <span class="h3 font-weight-bold text-white mb-0">{{ number_format($therapist->home_visit_rate ?? 0, 2) }} {{ __('EGP') }}</span>
                                    </div>
                                    
                                    <a href="{{ url('/home_visits/book/'.$therapist->id) }}" 
                                       class="btn btn-block text-white font-weight-bold mb-2 shadow-lg therapist-book-btn" 
                                       style="background-color: #02767F; border: none; border-radius: 8px; padding: 12px; font-size: 1rem; transition: all 0.3s ease;">
                                        <i class="las la-calendar-check mr-1"></i> {{ __('Book Now') }}
                                    </a>
                                    <a href="{{ url('/home_visits/therapist/'.$therapist->id) }}" 
                                       class="btn btn-block btn-outline-primary font-weight-bold" 
                                       style="color: #02767F; border-color: #02767F; border-radius: 8px; padding: 10px; transition: all 0.3s ease;">
                                        <i class="las la-user-circle mr-1"></i> {{ __('View Profile') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <img src="{{ asset('web/assets/images/no-results.svg') }}" style="width: 150px; opacity: 0.5;" class="mb-3">
                        <h4>{{ __('No therapists found') }}</h4>
                        <p class="text-muted">{{ __('Try adjusting your search filters') }}</p>
                    </div>
                    @endforelse

                    <div class="d-flex justify-content-center mt-4">
                        {{ $therapists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.hover-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(2,118,127,0.1) !important;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(2,118,127,0.15) !important;
    border-color: rgba(2,118,127,0.3) !important;
}

/* Therapist Photo Square with Rounded Corners */
.therapist-photo-square {
    display: block;
    margin: 0 auto;
}

/* Book Button Hover Effect */
.therapist-book-btn:hover {
    background-color: #10b8c4 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(2,118,127,0.3) !important;
}

.therapist-book-btn:active {
    transform: translateY(0);
}

/* Pricing Box Hover */
.pricing-box {
    transition: all 0.3s ease;
}

.hover-card:hover .pricing-box {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Availability Badge */
.badge-success {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

/* Fix header overlap - Add padding for fixed header */
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

/* Ensure hero section accounts for fixed header */
.hero-section {
    margin-top: 0 !important;
    padding-top: 100px !important;
}

@media (max-width: 768px) {
    .hero-section {
        padding-top: 80px !important;
    }
}

/* Condition Type Buttons */
.complain-option {
    cursor: pointer !important;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    transition: all 0.3s ease !important;
    pointer-events: auto !important;
}

.complain-option:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}

.complain-option input[type="radio"] {
    pointer-events: none;
}

.complain-option.bg-primary {
    border-color: #02767F !important;
    box-shadow: 0 4px 12px rgba(0, 137, 123, 0.4) !important;
}

/* Hero Enhancements */
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 30%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(255,255,255,0.08) 0%, transparent 50%);
    pointer-events: none;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-3px);
}

.hero-badge {
    animation: fadeInDown 0.8s ease;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Benefit Cards */
.benefit-card {
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.benefit-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 137, 123, 0.15) !important;
    border-color: #02767F;
}

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
    box-shadow: 0 8px 20px rgba(0, 137, 123, 0.2);
    transition: all 0.3s ease;
}

.benefit-card:hover .benefit-icon-circle {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 30px rgba(0, 137, 123, 0.3);
}

.benefit-icon-wrapper {
    position: relative;
}

/* Enhanced Form Card */
.card.shadow-2xl {
    box-shadow: 0 20px 60px rgba(0, 137, 123, 0.2) !important;
    border: none;
    animation: slideInUp 0.6s ease;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Request Button */
.btn-primary[style*="background-color: #02767F"] {
    background: linear-gradient(135deg, #02767F, #10b8c4) !important;
    border: none !important;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-primary[style*="background-color: #02767F"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 137, 123, 0.4) !important;
}

.btn-primary[style*="background-color: #02767F"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-primary[style*="background-color: #02767F"]:hover::before {
    left: 100%;
}
</style>
@section('scripts')
<script>
    // Tab visuals
    $('#pills-tab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    // Option Selection - Make condition type buttons clickable
    document.querySelectorAll('.complain-option').forEach(label => {
        // Add click handler to label
        label.addEventListener('click', function(e) {
            e.preventDefault();
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Add hover effect
        label.addEventListener('mouseenter', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.transform = 'scale(1.05)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
            }
        });
        
        label.addEventListener('mouseleave', function() {
            if (!this.querySelector('input[type="radio"]').checked) {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = '';
            }
        });
    });
    
    // Handle radio change
    document.querySelectorAll('input[name="complain_type"]').forEach(input => {
        input.addEventListener('change', function() {
            // Hide error message
            document.getElementById('complain_type_error').style.display = 'none';
            
            // Reset all options
            document.querySelectorAll('.complain-option').forEach(l => {
                l.classList.remove('bg-primary', 'text-white', 'border-primary'); 
                l.classList.add('bg-white', 'text-dark', 'border');
                const icon = l.querySelector('i');
                if (icon) {
                    icon.classList.add('text-primary');
                    icon.classList.remove('text-white');
                }
                l.style.transform = 'scale(1)';
                l.style.boxShadow = '';
            });
            
            // Highlight selected option
            const label = this.closest('label');
            if (label) {
                label.classList.remove('bg-white', 'text-dark', 'border');
                label.classList.add('bg-primary', 'text-white', 'border-primary');
                const icon = label.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-primary');
                    icon.classList.add('text-white');
                }
                label.style.transform = 'scale(1.05)';
                label.style.boxShadow = '0 4px 12px rgba(0, 137, 123, 0.4)';
            }
        });
    });
    
    // Form validation
    document.getElementById('visitForm')?.addEventListener('submit', function(e) {
        const selectedCondition = document.querySelector('input[name="complain_type"]:checked');
        if (!selectedCondition) {
            e.preventDefault();
            document.getElementById('complain_type_error').style.display = 'block';
            return false;
        }
    });

    // Schedule Toggle
    document.querySelectorAll('input[name="urgency"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('scheduleInput').style.display = this.value === 'normal' ? 'block' : 'none';
        });
    });
</script>
@endsection
@endsection

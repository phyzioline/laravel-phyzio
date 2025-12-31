@extends('web.layouts.app')

@push('styles')
<style>
    /* Fix header overlap - Add proper padding for fixed header */
    body {
        padding-top: 180px !important;
    }
    
    @media (max-width: 991px) {
        body {
            padding-top: 160px !important;
        }
    }
    
    @media (max-width: 768px) {
        body {
            padding-top: 140px !important;
        }
    }
    
    /* Ensure main content doesn't overlap header */
    main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    /* Enhanced Profile Header Section */
    .profile-header-section {
        background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);
        padding: 60px 0 40px;
        margin-top: 0;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header-section::before {
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
    
    .profile-header-section .container {
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .profile-header-section {
            padding: 40px 0 30px;
        }
    }
    
    /* Enhanced Photo Box */
    .therapist-photo-box {
        width: 280px;
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
        border: 5px solid white;
        box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #f8f9fa;
        display: block;
        margin: 0 auto;
        position: relative;
    }
    
    .therapist-photo-box::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 12px;
        box-shadow: inset 0 0 0 2px rgba(255,255,255,0.3);
        pointer-events: none;
    }
    
    .therapist-photo-box:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 50px rgba(0,0,0,0.3);
    }
    
    .therapist-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    /* Profile Details in Header */
    .profile-details-header {
        color: white !important;
    }
    
    .profile-details-header h2 {
        color: white !important;
        font-size: 2.5rem;
        margin-bottom: 10px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-details-header .specialization {
        color: rgba(255,255,255,0.95) !important;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .profile-details-header .text-muted {
        color: rgba(255,255,255,0.85) !important;
    }
    
    /* Trust Badges */
    .trust-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 25px;
        margin-right: 10px;
        margin-bottom: 10px;
        border: 1px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .trust-badge:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }
    
    .trust-badge i {
        margin-right: 6px;
        font-size: 1.1rem;
    }
    
    /* Stats Cards */
    .stat-card {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 15px;
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-3px);
    }
    
    .stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        margin-bottom: 5px;
    }
    
    .stat-card .stat-label {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.9);
    }
    
    /* Pricing Card */
    .pricing-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 50px rgba(0,0,0,0.2);
    }
    
    .pricing-card-header {
        background: linear-gradient(135deg, #ea3d2f 0%, #ff6b5a 100%);
        color: white;
        padding: 20px;
        text-align: center;
    }
    
    .pricing-card-body {
        padding: 30px;
    }
    
    .price-display {
        font-size: 2.5rem;
        font-weight: 700;
        color: #02767F;
        margin: 15px 0;
    }
    
    /* CTA Buttons */
    .btn-book-primary {
        background: linear-gradient(135deg, #ea3d2f 0%, #ff6b5a 100%);
        border: none;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 15px 30px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(234, 61, 47, 0.4);
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-book-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(234, 61, 47, 0.5);
        color: white;
    }
    
    .btn-book-secondary {
        background: white;
        border: 2px solid #02767F;
        color: #02767F;
        font-weight: 600;
        font-size: 1rem;
        padding: 12px 25px;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-book-secondary:hover {
        background: #02767F;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Content Sections */
    .content-section {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .content-section:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    
    .section-title {
        color: #02767F;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 3px solid #e0f7fa;
    }
    
    /* Service Cards */
    .service-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e0f7fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .service-item:hover {
        border-color: #02767F;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(2,118,127,0.15);
    }
    
    /* Working Hours */
    .working-hours-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .working-hours-header {
        background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);
        color: white;
        padding: 20px;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .hours-item {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s ease;
    }
    
    .hours-item:last-child {
        border-bottom: none;
    }
    
    .hours-item:hover {
        background: #f8f9fa;
    }
    
    .hours-item .day {
        font-weight: 600;
        color: #212529;
    }
    
    .hours-item .time {
        font-weight: 600;
    }
    
    /* Credentials Section */
    .credential-badge {
        display: inline-block;
        background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 5px;
        box-shadow: 0 2px 8px rgba(2,118,127,0.2);
    }
    
    /* Reviews */
    .review-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #02767F;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .therapist-photo-box {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
        }
        
        .profile-details-header h2 {
            font-size: 1.8rem;
        }
        
        .profile-details-header .specialization {
            font-size: 1.1rem;
        }
        
        .stat-card {
            padding: 12px 15px;
        }
        
        .stat-card .stat-number {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<main>
    <!-- Enhanced Profile Header -->
    <section class="profile-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 col-12 text-center mb-4 mb-md-0">
                    @php
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
                    <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="therapist-photo-box">
                        <img src="{{ $imageUrl }}" 
                             onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                             alt="{{ $therapist->user->name ?? 'Therapist' }} {{ __('Profile Photo') }}">
                    </a>
                </div>
                <div class="col-md-6 col-12 profile-details-header">
                    <h2>{{ $therapist->user->name }}</h2>
                    <p class="specialization mb-3">{{ $therapist->specialization ?? __('Physical Therapist') }}</p>
                    
                    <!-- Rating & Reviews -->
                    <div class="d-flex align-items-center mb-3 flex-wrap">
                        <div class="mr-4 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? 'text-warning' : 'text-muted' }}" style="font-size: 1.3rem;"></i>
                            @endfor
                            <span class="font-weight-bold ml-2" style="font-size: 1.2rem;">{{ number_format($therapist->rating ?? 0, 1) }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="las la-comment"></i> 
                            <strong>{{ $therapist->total_reviews ?? 0 }}</strong> {{ __('Reviews') }}
                        </div>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="mb-3">
                        @if($therapist->home_visit_verified)
                        <span class="trust-badge">
                            <i class="las la-check-circle"></i> {{ __('Verified Therapist') }}
                        </span>
                        @endif
                        @if($therapist->license_number)
                        <span class="trust-badge">
                            <i class="las la-certificate"></i> {{ __('Licensed') }}
                        </span>
                        @endif
                        @if($therapist->years_experience && $therapist->years_experience >= 5)
                        <span class="trust-badge">
                            <i class="las la-award"></i> {{ __('Experienced') }}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Key Stats -->
                    <div class="row mt-3">
                        <div class="col-6 col-md-4">
                            <div class="stat-card">
                                <div class="stat-number">{{ $therapist->years_experience ?? 0 }}+</div>
                                <div class="stat-label">{{ __('Years Experience') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="stat-card">
                                <div class="stat-number">{{ $therapist->homeVisits->where('status', 'completed')->count() ?? 0 }}</div>
                                <div class="stat-label">{{ __('Completed Visits') }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="stat-card">
                                <div class="stat-number">< 2h</div>
                                <div class="stat-label">{{ __('Response Time') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Areas -->
                    @if(!empty($therapist->available_areas))
                    <div class="mt-3">
                        <i class="las la-map-marker-alt"></i> 
                        <strong>{{ __('Available in') }}:</strong> 
                        {{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 4)) }}
                        @if(count($therapist->available_areas ?? []) > 4)
                            <span class="text-white-50">+{{ count($therapist->available_areas) - 4 }} {{ __('more') }}</span>
                        @endif
                    </div>
                    @endif
                </div>
                
                <!-- Pricing & Booking Card -->
                <div class="col-md-3 col-12 mt-4 mt-md-0">
                    <div class="pricing-card">
                        <div class="pricing-card-header">
                            <h5 class="mb-0">{{ __('Home Visit Fees') }}</h5>
                        </div>
                        <div class="pricing-card-body text-center">
                            <div class="price-display">{{ number_format($therapist->home_visit_rate ?? 0, 0) }}</div>
                            <p class="text-muted mb-4">{{ __('EGP per visit') }}</p>
                            
                            <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-book-primary mb-3">
                                <i class="las la-calendar-check mr-2"></i> {{ __('Book Appointment') }}
                            </a>
                            
                            <p class="small text-success mb-0">
                                <i class="las la-check-circle"></i> {{ __('No booking fees') }}
                            </p>
                            <p class="small text-muted mb-0 mt-2">
                                <i class="las la-shield-alt"></i> {{ __('Secure payment') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-user-md mr-2"></i> {{ __('About Doctor') }}
                        </h4>
                        @if($therapist->bio)
                            <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                                {{ $therapist->bio }}
                            </p>
                        @else
                            <p class="text-muted">{{ __('No bio available.') }}</p>
                        @endif
                        
                        <!-- Credentials -->
                        @if($therapist->license_number || $therapist->university_degree ?? null)
                        <div class="mt-4">
                            <h6 class="font-weight-bold mb-3" style="color: #02767F;">{{ __('Credentials & Qualifications') }}</h6>
                            <div>
                                @if($therapist->license_number)
                                <span class="credential-badge">
                                    <i class="las la-certificate"></i> {{ __('License') }}: {{ $therapist->license_number }}
                                </span>
                                @endif
                                @if($therapist->university_degree ?? null)
                                <span class="credential-badge">
                                    <i class="las la-graduation-cap"></i> {{ $therapist->university_degree }}
                                </span>
                                @endif
                                @if($therapist->professional_level ?? null)
                                <span class="credential-badge">
                                    <i class="las la-star"></i> {{ ucfirst($therapist->professional_level) }} {{ __('Level') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Services Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-concierge-bell mr-2"></i> {{ __('Services Offered') }}
                        </h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-center">
                                        <i class="las la-check-circle text-success mr-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ __('Home Physical Therapy') }}</h6>
                                            <small class="text-muted">{{ __('Professional therapy at your home') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-center">
                                        <i class="las la-check-circle text-success mr-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ __('Post-Surgery Rehabilitation') }}</h6>
                                            <small class="text-muted">{{ __('Recovery support after surgery') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-center">
                                        <i class="las la-check-circle text-success mr-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ __('Sports Injury Recovery') }}</h6>
                                            <small class="text-muted">{{ __('Specialized sports injury treatment') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-center">
                                        <i class="las la-check-circle text-success mr-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-1">{{ __('Elderly Care') }}</h6>
                                            <small class="text-muted">{{ __('Specialized care for elderly patients') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-star mr-2"></i> {{ __('Patient Reviews') }}
                        </h4>
                        
                        @if($therapist->total_reviews && $therapist->total_reviews > 0)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-3">
                                        <h2 class="mb-0" style="color: #02767F;">{{ number_format($therapist->rating ?? 0, 1) }}</h2>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? '' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-0"><strong>{{ $therapist->total_reviews }}</strong> {{ __('reviews') }}</p>
                                        <small class="text-muted">{{ __('Based on completed visits') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sample Review Cards (Replace with actual reviews when available) -->
                            <div class="review-card">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="font-weight-bold mb-0">{{ __('Patient Name') }}</h6>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted mb-0">{{ __('Great experience, very professional and caring. Highly recommended!') }}</p>
                                <small class="text-muted">{{ __('2 weeks ago') }}</small>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="las la-comment-alt" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">{{ __('No reviews yet. Be the first to book and review!') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Working Hours -->
                    <div class="working-hours-card mb-4">
                        <div class="working-hours-header">
                            <i class="las la-clock mr-2"></i> {{ __('Working Hours') }}
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-unstyled mb-0">
                                @php
                                    $days = [
                                        'saturday' => __('Saturday'),
                                        'sunday' => __('Sunday'),
                                        'monday' => __('Monday'),
                                        'tuesday' => __('Tuesday'),
                                        'wednesday' => __('Wednesday'),
                                        'thursday' => __('Thursday'),
                                        'friday' => __('Friday')
                                    ];
                                    $workingHours = $therapist->working_hours ?? [];
                                @endphp
                                @foreach($days as $key => $day)
                                    @php
                                        $daySchedule = $workingHours[$key] ?? null;
                                        $isClosed = !$daySchedule || ($daySchedule['is_closed'] ?? false);
                                    @endphp
                                    <li class="hours-item">
                                        <span class="day">{{ $day }}</span>
                                        @if($isClosed)
                                            <span class="time text-danger">{{ __('Closed') }}</span>
                                        @else
                                            <span class="time text-success">
                                                {{ isset($daySchedule['start_time']) ? \Carbon\Carbon::parse($daySchedule['start_time'])->format('g:i A') : '10:00 AM' }} - 
                                                {{ isset($daySchedule['end_time']) ? \Carbon\Carbon::parse($daySchedule['end_time'])->format('g:i A') : '10:00 PM' }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Quick Contact Card -->
                    <div class="content-section text-center">
                        <h5 class="font-weight-bold mb-4" style="color: #02767F;">{{ __('Ready to Book?') }}</h5>
                        <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-book-primary mb-3">
                            <i class="las la-calendar-check mr-2"></i> {{ __('Book Now') }}
                        </a>
                        <p class="small text-muted mb-0">
                            <i class="las la-info-circle"></i> {{ __('Instant confirmation') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

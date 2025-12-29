@extends('web.layouts.app')

@push('styles')
<style>
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
    
    /* Ensure main content doesn't overlap header */
    main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    /* Profile section styling */
    .profile-header-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 40px 0;
        margin-top: 0;
    }
    
    @media (max-width: 768px) {
        .profile-header-section {
            padding: 30px 0;
        }
    }
    
    /* Square photo box with better styling */
    .therapist-photo-box {
        width: 280px;
        height: 280px;
        border-radius: 12px;
        overflow: hidden;
        border: 4px solid #02767F;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #f8f9fa;
        display: block;
        margin: 0 auto;
    }
    
    .therapist-photo-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }
    
    .therapist-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    /* Ensure text is visible on white background */
    .profile-details {
        color: #333 !important;
    }
    
    .profile-details h2 {
        color: #212529 !important;
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .profile-details .text-primary {
        color: #02767F !important;
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .profile-details .text-muted {
        color: #6c757d !important;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .therapist-photo-box {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
        }
        
        .profile-header-section {
            padding: 30px 0;
        }
        
        .profile-details h2 {
            font-size: 1.5rem;
        }
        
        .profile-details .text-primary {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<main>
    <!-- Profile Header -->
    <section class="profile-header-section border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 col-12 text-center mb-4 mb-md-0">
                    @php
                        $imageUrl = $therapist->user->image 
                            ? asset($therapist->user->image) 
                            : ($therapist->profile_photo 
                                ? asset($therapist->profile_photo) 
                                : ($therapist->profile_image 
                                    ? asset($therapist->profile_image) 
                                    : asset('web/assets/images/default-user.png')));
                    @endphp
                    <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="therapist-photo-box">
                        <img src="{{ $imageUrl }}" 
                             onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                             alt="{{ $therapist->user->name }} {{ __('Profile Photo') }}">
                    </a>
                </div>
                <div class="col-md-6 col-12 profile-details">
                    <h2 class="font-weight-bold mb-2">{{ $therapist->user->name }}</h2>
                    <p class="text-primary font-weight-bold mb-3">{{ $therapist->specialization ?? __('Specialist') }}</p>
                    
                    <div class="d-flex align-items-center mb-3 flex-wrap">
                        <div class="mr-3 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="font-weight-bold ml-1">{{ number_format($therapist->rating ?? 0, 1) }}</span>
                        </div>
                        <div class="text-muted mb-2">
                            <i class="las la-comment"></i> {{ $therapist->total_reviews ?? 0 }} {{ __('Reviews') }}
                        </div>
                    </div>
                    
                    <p class="text-muted mb-4" style="line-height: 1.6;">
                        {{ $therapist->bio ?? __('No bio available.') }}
                    </p>
                    
                    <div class="d-flex flex-wrap">
                        <div class="mr-4 mb-2">
                            <i class="las la-briefcase text-primary mr-1"></i>
                            <span class="font-weight-bold">{{ $therapist->years_experience ?? 0 }}+ {{ __('Years Exp.') }}</span>
                        </div>
                        @if(!empty($therapist->available_areas))
                        <div class="mr-4 mb-2">
                            <i class="las la-map-marker text-primary mr-1"></i>
                            <span>{{ __('Available in') }}: {{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 3)) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 col-12 text-center mt-4 mt-md-0">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">{{ __('Home Visit Fees') }}</h6>
                            <h2 class="text-primary font-weight-bold mb-4">{{ $therapist->home_visit_rate ?? 0 }} {{ __('EGP') }}</h2>
                            
                            <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-block text-white font-weight-bold py-3 mb-2" style="background-color: #ea3d2f; font-size: 1.1rem; border-radius: 8px;">
                                {{ __('Book Appointment') }}
                            </a>
                            <p class="small text-muted mb-0">{{ __('No booking fees') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Section -->
    <section class="py-5" style="background-color: #ffffff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3" style="color: #212529;">{{ __('About Doctor') }}</h4>
                        <p class="text-muted" style="line-height: 1.8; color: #495057 !important;">
                            {{ $therapist->bio ?? __('No bio available.') }}
                        </p>
                    </div>
                    
                    <!-- Services -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3" style="color: #212529;">{{ __('Services') }}</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="las la-check-circle text-success mr-2" style="font-size: 1.2rem;"></i>
                                    <span style="color: #212529 !important;">{{ __('Home Physical Therapy') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="las la-check-circle text-success mr-2" style="font-size: 1.2rem;"></i>
                                    <span style="color: #212529 !important;">{{ __('Post-Surgery Rehabilitation') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="las la-check-circle text-success mr-2" style="font-size: 1.2rem;"></i>
                                    <span style="color: #212529 !important;">{{ __('Sports Injury Recovery') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="las la-check-circle text-success mr-2" style="font-size: 1.2rem;"></i>
                                    <span style="color: #212529 !important;">{{ __('Elderly Care') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3" style="color: #212529;">{{ __('Patient Reviews') }}</h4>
                        
                        @if($therapist->homeVisits && $therapist->homeVisits->count() > 0)
                            <!-- Loop through reviews if we had a review model, for now placeholder -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="font-weight-bold" style="color: #212529;">{{ __('Patient Name') }}</h6>
                                        <div class="text-warning">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0" style="color: #6c757d !important;">{{ __('Great experience, very professional.') }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted" style="color: #6c757d !important;">{{ __('No reviews yet.') }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Availability Calendar Placeholder -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pb-2">
                            <h5 class="font-weight-bold mb-0" style="color: #212529;">{{ __('Working Hours') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Saturday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Sunday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Monday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Tuesday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Wednesday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Thursday') }}</span>
                                    <span class="text-success font-weight-bold">10:00 {{ __('AM') }} - 10:00 {{ __('PM') }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span style="color: #212529 !important; font-weight: 500;">{{ __('Friday') }}</span>
                                    <span class="text-danger font-weight-bold">{{ __('Closed') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

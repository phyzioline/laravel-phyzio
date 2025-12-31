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
        padding: 80px 0 50px;
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
            radial-gradient(circle at 20% 30%, rgba(255,255,255,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(255,255,255,0.1) 0%, transparent 50%),
            url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        pointer-events: none;
        opacity: 0.6;
    }
    
    .profile-header-section .container {
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .profile-header-section {
            padding: 50px 0 30px;
        }
    }
    
    /* Enhanced Photo Box */
    .therapist-photo-box {
        width: 300px;
        height: 300px;
        border-radius: 20px;
        overflow: hidden;
        border: 6px solid white;
        box-shadow: 0 15px 50px rgba(0,0,0,0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: #f8f9fa;
        display: block;
        margin: 0 auto;
        position: relative;
    }
    
    .therapist-photo-box::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #02767F, #10b8c4, #02767F);
        border-radius: 20px;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .therapist-photo-box:hover::before {
        opacity: 1;
    }
    
    .therapist-photo-box:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
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
        font-size: 2.8rem;
        margin-bottom: 12px;
        font-weight: 800;
        text-shadow: 0 3px 6px rgba(0,0,0,0.2);
        letter-spacing: -0.5px;
    }
    
    .profile-details-header .specialization {
        color: rgba(255,255,255,0.95) !important;
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .profile-details-header .text-muted {
        color: rgba(255,255,255,0.9) !important;
    }
    
    /* Trust Badges */
    .trust-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(15px);
        padding: 10px 18px;
        border-radius: 30px;
        margin-right: 12px;
        margin-bottom: 12px;
        border: 2px solid rgba(255,255,255,0.4);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .trust-badge:hover {
        background: rgba(255,255,255,0.35);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    
    .trust-badge i {
        margin-right: 8px;
        font-size: 1.2rem;
    }
    
    /* Stats Cards */
    .stat-card {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(15px);
        border-radius: 15px;
        padding: 20px 25px;
        margin-bottom: 15px;
        border: 2px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .stat-card .stat-label {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.95);
        font-weight: 500;
    }
    
    /* Pricing Card */
    .pricing-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.2);
        overflow: hidden;
        transition: all 0.4s ease;
        position: relative;
    }
    
    .pricing-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #ea3d2f, #ff6b5a, #ea3d2f);
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .pricing-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .pricing-card-header {
        background: linear-gradient(135deg, #ea3d2f 0%, #ff6b5a 100%);
        color: white;
        padding: 25px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .pricing-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: pulse 3s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }
    
    .pricing-card-body {
        padding: 35px 30px;
        position: relative;
    }
    
    .price-display {
        font-size: 3rem;
        font-weight: 800;
        color: #02767F;
        margin: 20px 0;
        text-shadow: 0 2px 4px rgba(2,118,127,0.1);
    }
    
    /* CTA Buttons */
    .btn-book-primary {
        background: linear-gradient(135deg, #ea3d2f 0%, #ff6b5a 100%);
        border: none;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        padding: 18px 35px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(234, 61, 47, 0.5);
        transition: all 0.3s ease;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .btn-book-primary::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-book-primary:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-book-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 35px rgba(234, 61, 47, 0.6);
        color: white;
    }
    
    .btn-book-primary span {
        position: relative;
        z-index: 1;
    }
    
    .btn-book-secondary {
        background: white;
        border: 3px solid #02767F;
        color: #02767F;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 15px 30px;
        border-radius: 12px;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-book-secondary:hover {
        background: #02767F;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(2,118,127,0.3);
    }
    
    /* Content Sections */
    .content-section {
        background: white;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .content-section:hover {
        box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }
    
    .section-title {
        color: #02767F;
        font-size: 1.7rem;
        font-weight: 800;
        margin-bottom: 25px;
        padding-bottom: 18px;
        border-bottom: 4px solid #e0f7fa;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 12px;
        font-size: 1.5rem;
    }
    
    /* Service Cards */
    .service-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 3px solid #e0f7fa;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 18px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .service-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, #02767F, #10b8c4);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .service-item:hover::before {
        transform: scaleY(1);
    }
    
    .service-item:hover {
        border-color: #02767F;
        transform: translateX(8px);
        box-shadow: 0 8px 25px rgba(2,118,127,0.2);
    }
    
    .service-item i {
        font-size: 2rem !important;
        margin-right: 15px;
    }
    
    /* Working Hours */
    .working-hours-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        border: 1px solid #e0f7fa;
    }
    
    .working-hours-header {
        background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);
        color: white;
        padding: 25px;
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .working-hours-header i {
        font-size: 1.5rem;
    }
    
    .hours-item {
        padding: 18px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .hours-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #02767F;
        transform: scaleY(0);
        transition: transform 0.2s ease;
    }
    
    .hours-item:hover::before {
        transform: scaleY(1);
    }
    
    .hours-item:last-child {
        border-bottom: none;
    }
    
    .hours-item:hover {
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        padding-left: 30px;
    }
    
    .hours-item .day {
        font-weight: 700;
        color: #212529;
        font-size: 1.05rem;
    }
    
    .hours-item .time {
        font-weight: 700;
        font-size: 1.05rem;
    }
    
    /* Credentials Section */
    .credential-badge {
        display: inline-block;
        background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        margin: 8px 8px 8px 0;
        box-shadow: 0 4px 15px rgba(2,118,127,0.3);
        transition: all 0.3s ease;
    }
    
    .credential-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(2,118,127,0.4);
    }
    
    .credential-badge i {
        margin-right: 8px;
    }
    
    /* Reviews */
    .review-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border-left: 5px solid #02767F;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .review-card:hover {
        transform: translateX(5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 15px;
        border: 2px dashed #e0f7fa;
    }
    
    .empty-state-icon {
        font-size: 5rem;
        color: #ccc;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    /* Next Available Badge */
    .next-available-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        display: inline-flex;
        align-items: center;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        margin-top: 15px;
    }
    
    /* Trust Banner */
    .trust-banner {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 15px 0;
        text-align: center;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .trust-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shine 3s infinite;
    }
    
    @keyframes shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .trust-banner i {
        margin: 0 8px;
        font-size: 1.2rem;
    }
    
    /* Social Proof Section */
    .social-proof-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 40px 0;
        margin: 30px 0;
        border-radius: 20px;
        border: 2px solid #e0f7fa;
    }
    
    .proof-item {
        text-align: center;
        padding: 20px;
    }
    
    .proof-item i {
        font-size: 2.5rem;
        color: #02767F;
        margin-bottom: 15px;
    }
    
    .proof-item .number {
        font-size: 2rem;
        font-weight: 800;
        color: #02767F;
        margin-bottom: 5px;
    }
    
    .proof-item .label {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 600;
    }
    
    /* Urgency Badge */
    .urgency-badge {
        background: linear-gradient(135deg, #ff6b5a 0%, #ea3d2f 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        animation: pulse-badge 2s infinite;
        box-shadow: 0 4px 15px rgba(234, 61, 47, 0.4);
    }
    
    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* Enhanced Empty State */
    .empty-state-enhanced {
        text-align: center;
        padding: 80px 30px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 20px;
        border: 3px dashed #e0f7fa;
        position: relative;
        overflow: hidden;
    }
    
    .empty-state-enhanced::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(2,118,127,0.05) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .empty-state-enhanced > * {
        position: relative;
        z-index: 1;
    }
    
    /* Enhanced CTA Button */
    .btn-cta-primary {
        background: linear-gradient(135deg, #ea3d2f 0%, #ff6b5a 100%);
        border: none;
        color: white;
        font-weight: 800;
        font-size: 1.3rem;
        padding: 20px 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(234, 61, 47, 0.5);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-cta-primary::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-cta-primary:hover::after {
        width: 400px;
        height: 400px;
    }
    
    .btn-cta-primary:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 40px rgba(234, 61, 47, 0.6);
        color: white;
    }
    
    .btn-cta-primary span {
        position: relative;
        z-index: 1;
    }
    
    /* Value Proposition Cards */
    .value-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
    }
    
    .value-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(2,118,127,0.2);
        border-color: #02767F;
    }
    
    .value-card i {
        font-size: 3rem;
        color: #02767F;
        margin-bottom: 15px;
    }
    
    .value-card h6 {
        font-weight: 700;
        color: #212529;
        margin-bottom: 10px;
    }
    
    .value-card p {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .therapist-photo-box {
            width: 220px;
            height: 220px;
            margin-bottom: 25px;
        }
        
        .profile-details-header h2 {
            font-size: 2rem;
        }
        
        .profile-details-header .specialization {
            font-size: 1.2rem;
        }
        
        .stat-card {
            padding: 15px 18px;
        }
        
        .stat-card .stat-number {
            font-size: 1.8rem;
        }
        
        .content-section {
            padding: 25px 20px;
        }
        
        .btn-cta-primary {
            font-size: 1.1rem;
            padding: 15px 30px;
        }
    }
</style>
@endpush

@section('content')
<main>
    <!-- Trust Banner -->
    <div class="trust-banner">
        <div class="container">
            <i class="las la-shield-alt"></i>
            <strong>{{ __('Verified Professional') }}</strong>
            <i class="las la-check-circle"></i>
            <span class="ml-3">{{ __('100% Secure Booking') }}</span>
            <i class="las la-lock ml-3"></i>
            <span>{{ __('Instant Confirmation') }}</span>
        </div>
    </div>
    
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
                    <div class="therapist-photo-box">
                        <img src="{{ $imageUrl }}" 
                             onerror="this.src='{{ asset('web/assets/images/default-user.png') }}'"
                             alt="{{ $therapist->user->name ?? 'Therapist' }} {{ __('Profile Photo') }}">
                    </div>
                </div>
                <div class="col-md-6 col-12 profile-details-header">
                    <h2>{{ $therapist->user->name }}</h2>
                    <p class="specialization mb-4">{{ $therapist->specialization ?? __('Physical Therapist') }}</p>
                    
                    <!-- Rating & Reviews -->
                    <div class="d-flex align-items-center mb-4 flex-wrap">
                        <div class="mr-4 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? 'text-warning' : 'text-muted' }}" style="font-size: 1.5rem;"></i>
                            @endfor
                            <span class="font-weight-bold ml-2" style="font-size: 1.4rem;">{{ number_format($therapist->rating ?? 0, 1) }}</span>
                        </div>
                        <div class="mb-2">
                            <i class="las la-comment" style="font-size: 1.2rem;"></i> 
                            <strong style="font-size: 1.1rem;">{{ $therapist->total_reviews ?? 0 }}</strong> {{ __('Reviews') }}
                        </div>
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="mb-4">
                        @if($therapist->home_visit_verified)
                        <span class="trust-badge">
                            <i class="las la-check-circle"></i> {{ __('Verified Therapist') }}
                        </span>
                        @endif
                        @if($therapist->license_number)
                        <span class="trust-badge">
                            <i class="las la-certificate"></i> {{ __('Licensed Professional') }}
                        </span>
                        @endif
                        @if($therapist->years_experience && $therapist->years_experience >= 5)
                        <span class="trust-badge">
                            <i class="las la-award"></i> {{ __('Experienced') }} ({{ $therapist->years_experience }}+ {{ __('Years') }})
                        </span>
                        @endif
                        @if($therapist->years_experience && $therapist->years_experience < 5 && $therapist->years_experience > 0)
                        <span class="trust-badge">
                            <i class="las la-user-graduate"></i> {{ __('Certified') }}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Key Stats -->
                    <div class="row mt-4">
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
                    <div class="mt-4">
                        <div class="d-flex align-items-center flex-wrap">
                            <i class="las la-map-marker-alt mr-2" style="font-size: 1.3rem;"></i> 
                            <strong style="font-size: 1.1rem;">{{ __('Available in') }}:</strong> 
                            <span class="ml-2" style="font-size: 1.05rem;">
                                {{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 4)) }}
                                @if(count($therapist->available_areas ?? []) > 4)
                                    <span class="text-white-50">+{{ count($therapist->available_areas) - 4 }} {{ __('more') }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Pricing & Booking Card -->
                <div class="col-md-3 col-12 mt-4 mt-md-0">
                    <div class="pricing-card">
                        <div class="pricing-card-header">
                            <h5 class="mb-0" style="position: relative; z-index: 1;">{{ __('Home Visit Fees') }}</h5>
                        </div>
                        <div class="pricing-card-body text-center">
                            <div class="price-display">{{ number_format($therapist->home_visit_rate ?? 0, 0) }}</div>
                            <p class="text-muted mb-4" style="font-size: 1.1rem;">{{ __('EGP per visit') }}</p>
                            
                            <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-book-primary mb-3">
                                <span><i class="las la-calendar-check mr-2"></i> {{ __('Book Appointment') }}</span>
                            </a>
                            
                            @if($nextAvailableSlot)
                            <div class="next-available-badge">
                                <i class="las la-clock mr-2"></i>
                                {{ __('Next available') }}: {{ $nextAvailableSlot->format('M d, g:i A') }}
                            </div>
                            @endif
                            
                            <div class="mt-4">
                                <p class="small text-success mb-2">
                                    <i class="las la-check-circle"></i> {{ __('No booking fees') }}
                                </p>
                                <p class="small text-muted mb-0">
                                    <i class="las la-shield-alt"></i> {{ __('Secure payment') }}
                                </p>
                                <p class="small text-muted mb-0 mt-2">
                                    <i class="las la-clock"></i> {{ __('Instant confirmation') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof Section -->
    <section class="social-proof-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="proof-item">
                        <i class="las la-users"></i>
                        <div class="number">{{ $therapist->homeVisits->where('status', 'completed')->count() ?? 0 }}+</div>
                        <div class="label">{{ __('Happy Patients') }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="proof-item">
                        <i class="las la-star"></i>
                        <div class="number">{{ number_format($therapist->rating ?? 0, 1) }}</div>
                        <div class="label">{{ __('Average Rating') }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="proof-item">
                        <i class="las la-calendar-check"></i>
                        <div class="number">{{ $therapist->years_experience ?? 0 }}+</div>
                        <div class="label">{{ __('Years Experience') }}</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="proof-item">
                        <i class="las la-check-circle"></i>
                        <div class="number">100%</div>
                        <div class="label">{{ __('Verified') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
        <div class="container">
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-user-md"></i> {{ __('About Doctor') }}
                        </h4>
                        @if($therapist->bio && trim($therapist->bio) !== '')
                            <div class="bio-content" style="line-height: 1.9; font-size: 1.1rem; color: #495057;">
                                {!! nl2br(e($therapist->bio)) !!}
                            </div>
                        @else
                            <div class="empty-state-enhanced">
                                <i class="las la-user-md empty-state-icon"></i>
                                <h5 class="text-muted mb-3" style="font-size: 1.4rem; font-weight: 700;">{{ __('Professional Profile') }}</h5>
                                <p class="text-muted mb-4" style="font-size: 1.1rem;">{{ __('This therapist is a verified professional ready to help you.') }}</p>
                                <div class="d-flex justify-content-center flex-wrap">
                                    <span class="badge badge-success p-2 mr-2 mb-2" style="font-size: 0.9rem;">
                                        <i class="las la-check-circle"></i> {{ __('Licensed') }}
                                    </span>
                                    <span class="badge badge-info p-2 mr-2 mb-2" style="font-size: 0.9rem;">
                                        <i class="las la-certificate"></i> {{ __('Certified') }}
                                    </span>
                                    <span class="badge badge-warning p-2 mb-2" style="font-size: 0.9rem;">
                                        <i class="las la-star"></i> {{ __('Verified') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Credentials -->
                        @if($therapist->license_number || ($therapist->university_degree ?? null) || ($therapist->professional_level ?? null))
                        <div class="mt-5">
                            <h6 class="font-weight-bold mb-4" style="color: #02767F; font-size: 1.3rem;">
                                <i class="las la-certificate mr-2"></i> {{ __('Credentials & Qualifications') }}
                            </h6>
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
                    
                    <!-- Value Proposition Section -->
                    <div class="content-section mb-4">
                        <h4 class="section-title mb-4">
                            <i class="las la-star"></i> {{ __('Why Choose This Therapist?') }}
                        </h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="value-card">
                                    <i class="las la-user-md"></i>
                                    <h6>{{ __('Expert Care') }}</h6>
                                    <p>{{ __('Licensed and experienced professional') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-card">
                                    <i class="las la-home"></i>
                                    <h6>{{ __('Home Convenience') }}</h6>
                                    <p>{{ __('Professional care in the comfort of your home') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="value-card">
                                    <i class="las la-clock"></i>
                                    <h6>{{ __('Fast Response') }}</h6>
                                    <p>{{ __('Quick response time and flexible scheduling') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-concierge-bell"></i> {{ __('Services Offered') }}
                        </h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-start">
                                        <i class="las la-check-circle text-success"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-2" style="color: #212529; font-size: 1.1rem;">{{ __('Home Physical Therapy') }}</h6>
                                            <small class="text-muted" style="line-height: 1.6;">{{ __('Professional therapy at your home') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-start">
                                        <i class="las la-check-circle text-success"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-2" style="color: #212529; font-size: 1.1rem;">{{ __('Post-Surgery Rehabilitation') }}</h6>
                                            <small class="text-muted" style="line-height: 1.6;">{{ __('Recovery support after surgery') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-start">
                                        <i class="las la-check-circle text-success"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-2" style="color: #212529; font-size: 1.1rem;">{{ __('Sports Injury Recovery') }}</h6>
                                            <small class="text-muted" style="line-height: 1.6;">{{ __('Specialized sports injury treatment') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="service-item">
                                    <div class="d-flex align-items-start">
                                        <i class="las la-check-circle text-success"></i>
                                        <div>
                                            <h6 class="font-weight-bold mb-2" style="color: #212529; font-size: 1.1rem;">{{ __('Elderly Care') }}</h6>
                                            <small class="text-muted" style="line-height: 1.6;">{{ __('Specialized care for elderly patients') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews Section -->
                    <div class="content-section">
                        <h4 class="section-title">
                            <i class="las la-star"></i> {{ __('Patient Reviews') }}
                        </h4>
                        
                        @if($therapist->total_reviews && $therapist->total_reviews > 0)
                            <div class="mb-4 p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-radius: 15px;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-4">
                                        <h2 class="mb-0" style="color: #02767F; font-size: 3rem; font-weight: 800;">{{ number_format($therapist->rating ?? 0, 1) }}</h2>
                                        <div class="text-warning" style="font-size: 1.2rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= ($therapist->rating ?? 0) ? '' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-0" style="font-size: 1.2rem;"><strong>{{ $therapist->total_reviews }}</strong> {{ __('reviews') }}</p>
                                        <small class="text-muted">{{ __('Based on completed visits') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sample Review Cards (Replace with actual reviews when available) -->
                            <div class="review-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="font-weight-bold mb-1" style="color: #212529; font-size: 1.1rem;">{{ __('Patient Name') }}</h6>
                                        <small class="text-muted">{{ __('2 weeks ago') }}</small>
                                    </div>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted mb-0" style="line-height: 1.7; font-size: 1.05rem;">{{ __('Great experience, very professional and caring. Highly recommended!') }}</p>
                            </div>
                        @else
                            <div class="empty-state-enhanced">
                                <i class="las la-comment-alt empty-state-icon"></i>
                                <h5 class="mb-3" style="font-size: 1.5rem; font-weight: 800; color: #02767F;">{{ __('Be the First to Review!') }}</h5>
                                <p class="text-muted mb-4" style="font-size: 1.15rem; line-height: 1.8;">{{ __('This therapist is new to our platform. Book your first appointment and share your experience to help others make informed decisions.') }}</p>
                                <div class="mb-4">
                                    <span class="urgency-badge">
                                        <i class="las la-fire mr-1"></i> {{ __('Limited Time') }}
                                    </span>
                                </div>
                                <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-cta-primary">
                                    <span><i class="las la-calendar-check mr-2"></i> {{ __('Book Now & Be First to Review') }}</span>
                                </a>
                                <p class="text-muted mt-3 mb-0 small">
                                    <i class="las la-gift text-warning"></i> {{ __('Early booking benefits available') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Working Hours -->
                    <div class="working-hours-card mb-4">
                        <div class="working-hours-header">
                            <div>
                                <i class="las la-clock mr-2"></i> {{ __('Working Hours') }}
                            </div>
                            @if($nextAvailableSlot)
                            <small style="opacity: 0.9; font-size: 0.9rem;">
                                <i class="las la-calendar-check"></i> {{ __('Available') }}
                            </small>
                            @endif
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
                                    $hasSchedule = isset($schedules) && $schedules->count() > 0;
                                @endphp
                                @foreach($days as $key => $day)
                                    @php
                                        $daySchedule = $hasSchedule && $schedules->has($key) ? $schedules->get($key)->first() : null;
                                        $isClosed = !$daySchedule;
                                        $isToday = strtolower(now()->format('l')) === $key;
                                    @endphp
                                    <li class="hours-item {{ $isToday ? 'bg-light' : '' }}">
                                        <span class="day">
                                            {{ $day }}
                                            @if($isToday)
                                                <span class="badge badge-primary ml-2" style="font-size: 0.7rem;">{{ __('Today') }}</span>
                                            @endif
                                        </span>
                                        @if($isClosed)
                                            <span class="time text-danger">
                                                <i class="las la-times-circle"></i> {{ __('Closed') }}
                                            </span>
                                        @else
                                            <span class="time text-success">
                                                <i class="las la-clock"></i> 
                                                {{ \Carbon\Carbon::parse($daySchedule->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($daySchedule->end_time)->format('g:i A') }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @if(!$hasSchedule)
                            <div class="p-4 text-center border-top">
                                <p class="text-muted mb-0 small">
                                    <i class="las la-info-circle"></i> {{ __('Schedule not set yet') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Quick Contact Card -->
                    <div class="content-section text-center">
                        <h5 class="font-weight-bold mb-4" style="color: #02767F; font-size: 1.4rem;">
                            <i class="las la-calendar-check mr-2"></i> {{ __('Ready to Book?') }}
                        </h5>
                        <a href="{{ url('/home_visits/book/'.$therapist->id) }}" class="btn btn-book-primary mb-3">
                            <span><i class="las la-calendar-check mr-2"></i> {{ __('Book Now') }}</span>
                        </a>
                        <div class="mt-3">
                            <p class="small text-success mb-2">
                                <i class="las la-check-circle"></i> {{ __('No booking fees') }}
                            </p>
                            <p class="small text-muted mb-0">
                                <i class="las la-info-circle"></i> {{ __('Instant confirmation') }}
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
</main>
@endsection

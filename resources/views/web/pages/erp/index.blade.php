@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('erp');
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
@json(\App\Services\SEO\SEOService::erpSchema())
</script>
@endpush

@section('content')
<main>
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero-section position-relative" style="padding-top: 150px; padding-bottom: 60px; background-color:#02767F;">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="badge badge-pill badge-light text-primary px-3 py-2 mb-3">For Clinics</span>
                    <h1 class="mb-4 display-4 font-weight-bold text-white">Complete Management System for Your Clinic</h1>
                    <p class="lead mb-5 text-white-50">Streamline your practice with our all-in-one ERP. EMR, scheduling, billing, and patient engagement in one secure platform.</p>
                    <div class="d-flex">
                        <a href="{{ route('clinic.dashboard') }}" class="btn btn-primary btn-lg px-4 mr-3 font-weight-bold" style="background-color: #04b8c4; border-color: #04b8c4;">Start Free Trial</a>
                        <a href="#" class="btn btn-outline-secondary btn-lg px-4 font-weight-bold">Watch Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="py-100 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold" style="color: #36415A;">Everything You Need to Run Your Clinic</h2>
            </div>
            <div class="row">
                <!-- Feature 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-notes-medical mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Digital EMR</h4>
                            <p style="color: #36415a;">Secure electronic medical records with customizable templates for physiotherapy assessments.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-calendar-alt mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Smart Scheduling</h4>
                            <p style="color: #36415a;">Drag-and-drop calendar, automated reminders, and online booking integration.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-file-invoice-dollar mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Billing & Invoicing</h4>
                            <p style="color: #36415a;">Automated invoicing, insurance claim management, and financial reporting.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-users mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Staff Management</h4>
                            <p style="color: #36415a;">Manage roles, permissions, schedules, and performance for your entire team.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-chart-line mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Analytics</h4>
                            <p style="color: #36415a;">Deep insights into clinic performance, patient retention, and revenue growth.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-shield-alt mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Secure & Compliant</h4>
                            <p style="color: #36415a;">HIPAA compliant security to keep your patient data safe and protected.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
<style> 

    /* Make web header transparent over hero */ header, .header-section, .navbar, .web-header { background: transparent !important; box-shadow: none !important;  top: 0; left: 0; width: 100%; z-index: 9999;
</style>
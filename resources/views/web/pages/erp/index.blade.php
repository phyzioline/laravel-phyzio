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
    <section class="hero-section position-relative" style="padding-top: 150px; padding-bottom: 80px; background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 text-center mb-4 mb-lg-0">
                    <div class="hero-image-placeholder" style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 60px; backdrop-filter: blur(10px);">
                        <i class="las la-clinic-medical" style="font-size: 120px; color: rgba(255,255,255,0.3);"></i>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="badge badge-pill badge-light text-primary px-4 py-2 mb-3" style="font-size: 14px;">For Physical Therapy Clinics</span>
                    <h1 class="mb-4 display-4 font-weight-bold text-white">Complete Management System for Your Clinic</h1>
                    <p class="lead mb-4 text-white" style="font-size: 1.25rem; line-height: 1.8;">Streamline your practice with our all-in-one ERP platform. Specialty-based modules, EMR, smart scheduling, billing, and patient engagement in one secure, HIPAA-compliant system.</p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('clinic.dashboard') }}" class="btn btn-primary btn-lg px-5 py-3 font-weight-bold shadow-lg" style="background-color: #04b8c4; border-color: #04b8c4; border-radius: 50px;">
                            <i class="las la-rocket mr-2"></i> Start Free Trial
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3 font-weight-bold" style="border-radius: 50px;">
                            <i class="las la-play-circle mr-2"></i> Watch Demo
                        </a>
                    </div>
                    <div class="d-flex align-items-center text-white-50">
                        <i class="las la-check-circle mr-2" style="color: #04b8c4;"></i>
                        <span class="small">No credit card required</span>
                        <span class="mx-3">•</span>
                        <i class="las la-check-circle mr-2" style="color: #04b8c4;"></i>
                        <span class="small">14-day free trial</span>
                        <span class="mx-3">•</span>
                        <i class="las la-check-circle mr-2" style="color: #04b8c4;"></i>
                        <span class="small">Cancel anytime</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Overview -->
    <section id="features" class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold mb-3" style="color: #36415A; font-size: 2.5rem;">Everything You Need to Run Your Clinic</h2>
                <p class="lead text-muted" style="max-width: 700px; margin: 0 auto;">A comprehensive platform designed specifically for physical therapy clinics. Manage patients, schedules, billing, and clinical documentation all in one place.</p>
            </div>
            
            <div class="row">
                <!-- Feature 1: Specialty-Based System -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-stethoscope text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Specialty-Based System</h4>
                            <p style="color: #36415a; line-height: 1.7;">Choose your physical therapy specialty (Orthopedic, Pediatric, Neurological, Sports, Geriatric, Women's Health, Cardiorespiratory, or Home Care). The system automatically adapts with specialized workflows, assessment forms, and treatment templates.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> 9 Specialty Modules</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Auto-Activated Features</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Customized Workflows</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 2: Digital EMR -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-notes-medical text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Digital EMR & Clinical Episodes</h4>
                            <p style="color: #36415a; line-height: 1.7;">Secure electronic medical records with specialty-specific assessment forms, clinical episode tracking, treatment plans, progress notes, and outcome measurements. All data is encrypted and HIPAA-compliant.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Episode-Based Care</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Custom Assessment Forms</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Treatment Plans</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 3: Smart Scheduling -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-calendar-alt text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Smart Scheduling & Appointments</h4>
                            <p style="color: #36415a; line-height: 1.7;">Intelligent appointment scheduling with drag-and-drop calendar, automated reminders via SMS and email, online booking integration, therapist availability management, and specialty-specific reservation fields.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Drag-and-Drop Calendar</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Auto Reminders</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Online Booking</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 4: Weekly Treatment Programs -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-clipboard-list text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Weekly Treatment Programs</h4>
                            <p style="color: #36415a; line-height: 1.7;">Create structured rehabilitation programs instead of random sessions. Set weekly schedules, track progression, manage attendance, and automatically book sessions. Specialty-specific program templates included.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Structured Programs</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Auto-Booking</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Progress Tracking</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 5: Dynamic Payment Calculator -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-calculator text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Smart Payment Calculator</h4>
                            <p style="color: #36415a; line-height: 1.7;">Dynamic pricing based on specialty, therapist level, equipment usage, location (clinic/home), session duration, and package discounts. Automatic invoice generation and insurance claim management.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Dynamic Pricing</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Auto Invoicing</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Insurance Claims</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 6: Billing & Invoicing -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-file-invoice-dollar text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Billing & Invoicing</h4>
                            <p style="color: #36415a; line-height: 1.7;">Automated invoicing, payment tracking, outstanding balance management, financial reports, revenue analytics, and support for cash, card, and insurance payments.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Auto Invoicing</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Payment Tracking</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Financial Reports</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 7: Patient Management -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-user-injured text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Patient Management</h4>
                            <p style="color: #36415a; line-height: 1.7;">Complete patient profiles with medical history, attachments (reports, imaging, prescriptions), consent forms, appointment history, treatment progress, and communication logs.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Full Profiles</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Medical History</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Document Storage</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 8: Staff & Doctor Management -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-users text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Staff & Doctor Management</h4>
                            <p style="color: #36415a; line-height: 1.7;">Manage therapists, doctors, assistants, receptionists, and administrative staff. Set roles, permissions, schedules, specializations, and track performance metrics.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Role Management</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Permissions</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Performance Tracking</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 9: Analytics & Reporting -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-chart-line text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Analytics & Reporting</h4>
                            <p style="color: #36415a; line-height: 1.7;">Deep insights into clinic performance, patient retention rates, revenue growth, therapist productivity, appointment trends, and outcome measurements with visual dashboards.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Performance Metrics</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Revenue Analytics</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Visual Dashboards</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 10: Services & Departments -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-hospital text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Services & Departments</h4>
                            <p style="color: #36415a; line-height: 1.7;">Organize your clinic by departments and services. Manage multiple specialties, assign head doctors, track department performance, and organize services by specialty type.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Multi-Department</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Service Organization</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Department Analytics</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 11: Notifications & Communication -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-bell text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Notifications & Communication</h4>
                            <p style="color: #36415a; line-height: 1.7;">Stay informed with real-time notifications for appointments, payments, patient updates, system alerts, and important announcements. Email and SMS integration included.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Real-Time Alerts</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Email & SMS</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> System Notifications</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Feature 12: Job System -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="icon-wrapper mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #04b8c4, #02767F); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-briefcase text-white" style="font-size: 28px;"></i>
                            </div>
                            <h4 class="font-weight-bold mb-3" style="color: #02767F;">Recruitment & Job Posting</h4>
                            <p style="color: #36415a; line-height: 1.7;">Post job openings, manage applications, review candidates, and hire therapists directly through the platform. Integrated with the main Phyzioline job board.</p>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Job Posting</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Application Management</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Candidate Review</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Specialty-Based System Highlight -->
    <section class="py-5" style="background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge badge-pill badge-light px-3 py-2 mb-3">Unique Feature</span>
                    <h2 class="font-weight-bold text-white mb-4" style="font-size: 2.5rem;">Specialty-Based Modular System</h2>
                    <p class="lead text-white mb-4" style="line-height: 1.8;">Unlike generic clinic management systems, our platform adapts to your physical therapy specialty. When you select your specialty, the system automatically activates relevant modules, assessment forms, and workflows.</p>
                    <div class="text-white">
                        <div class="mb-3">
                            <i class="las la-check-circle mr-2" style="font-size: 20px;"></i>
                            <strong>9 Physical Therapy Specialties Supported:</strong>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <i class="las la-bone mr-2"></i> Orthopedic Physical Therapy
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-baby mr-2"></i> Pediatric Physical Therapy
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-brain mr-2"></i> Neurological Rehabilitation
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-running mr-2"></i> Sports Physical Therapy
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-wheelchair mr-2"></i> Geriatric Physical Therapy
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-female mr-2"></i> Women's Health / Pelvic Floor
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-heartbeat mr-2"></i> Cardiorespiratory Physical Therapy
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="las la-home mr-2"></i> Home Care / Mobile Physical Therapy
                            </div>
                            <div class="col-md-12 mb-2">
                                <i class="las la-hospital mr-2"></i> Multi-Specialty Clinic
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-body p-4" style="background: white;">
                            <h5 class="font-weight-bold mb-3" style="color: #02767F;">What Happens When You Select Your Specialty?</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-circle mr-3" style="width: 40px; height: 40px; background: #04b8c4; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="las la-check text-white"></i>
                                        </div>
                                        <div>
                                            <strong style="color: #36415a;">Specialized Assessment Forms</strong>
                                            <p class="mb-0 text-muted small">Automatically loads assessment templates specific to your specialty (e.g., VAS scales for Orthopedic, developmental scales for Pediatric).</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-circle mr-3" style="width: 40px; height: 40px; background: #04b8c4; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="las la-check text-white"></i>
                                        </div>
                                        <div>
                                            <strong style="color: #36415a;">Customized Workflows</strong>
                                            <p class="mb-0 text-muted small">Clinical workflows adapt to your specialty's best practices and treatment protocols.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-circle mr-3" style="width: 40px; height: 40px; background: #04b8c4; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="las la-check text-white"></i>
                                        </div>
                                        <div>
                                            <strong style="color: #36415a;">Specialty-Specific KPIs</strong>
                                            <p class="mb-0 text-muted small">Dashboard metrics and reports adjust to show what matters most for your specialty.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-circle mr-3" style="width: 40px; height: 40px; background: #04b8c4; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="las la-check text-white"></i>
                                        </div>
                                        <div>
                                            <strong style="color: #36415a;">Dynamic Reservation Fields</strong>
                                            <p class="mb-0 text-muted small">Appointment booking forms show only relevant fields for your specialty (e.g., body region for Orthopedic, child age for Pediatric).</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security & Compliance -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-body p-5 text-center" style="background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%);">
                            <i class="las la-shield-alt text-white mb-3" style="font-size: 80px;"></i>
                            <h3 class="text-white font-weight-bold mb-3">HIPAA Compliant</h3>
                            <p class="text-white mb-0">Your patient data is encrypted, secure, and fully compliant with healthcare regulations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="font-weight-bold mb-4" style="color: #36415A;">Secure & Compliant</h2>
                    <p class="lead text-muted mb-4">We take data security seriously. Your clinic's information and patient records are protected with enterprise-grade security measures.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="las la-lock text-success mr-2" style="font-size: 20px;"></i>
                            <strong>End-to-End Encryption</strong>
                            <p class="text-muted mb-0 small">All data is encrypted in transit and at rest.</p>
                        </li>
                        <li class="mb-3">
                            <i class="las la-user-shield text-success mr-2" style="font-size: 20px;"></i>
                            <strong>Role-Based Access Control</strong>
                            <p class="text-muted mb-0 small">Granular permissions ensure only authorized staff access sensitive data.</p>
                        </li>
                        <li class="mb-3">
                            <i class="las la-history text-success mr-2" style="font-size: 20px;"></i>
                            <strong>Audit Logs</strong>
                            <p class="text-muted mb-0 small">Complete audit trail of all system activities and data access.</p>
                        </li>
                        <li class="mb-3">
                            <i class="las la-cloud-download-alt text-success mr-2" style="font-size: 20px;"></i>
                            <strong>Automated Backups</strong>
                            <p class="text-muted mb-0 small">Daily automated backups ensure your data is never lost.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: #36415A;">
        <div class="container text-center">
            <h2 class="font-weight-bold text-white mb-4" style="font-size: 2.5rem;">Ready to Transform Your Clinic?</h2>
            <p class="lead text-white-50 mb-5" style="max-width: 600px; margin: 0 auto;">Join hundreds of physical therapy clinics already using Phyzioline ERP to streamline their operations and improve patient care.</p>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                <a href="{{ route('clinic.dashboard') }}" class="btn btn-primary btn-lg px-5 py-3 font-weight-bold shadow-lg" style="background-color: #04b8c4; border-color: #04b8c4; border-radius: 50px;">
                    <i class="las la-rocket mr-2"></i> Start Your Free Trial
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3 font-weight-bold" style="border-radius: 50px;">
                    <i class="las la-info-circle mr-2"></i> Learn More
                </a>
            </div>
            <p class="text-white-50 mt-4 small">No credit card required • 14-day free trial • Cancel anytime</p>
        </div>
    </section>
</main>
@endsection

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    /* Make web header transparent over hero */
    header, .header-section, .navbar, .web-header {
        background: transparent !important;
        box-shadow: none !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
    }
</style>

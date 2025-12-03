@extends('web.layouts.app')

@section('title', 'Clinic ERP System | PhyzioLine')

@section('content')
<main>
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero-section position-relative pt-150 pb-100" style="background-color: #02767F; margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <img src="https://img.freepik.com/free-vector/medical-booking-app-concept_23-2148556637.jpg" alt="Clinic ERP Dashboard" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="badge badge-pill badge-light text-primary px-3 py-2 mb-3">For Clinics</span>
                    <h1 class="mb-4 display-4 font-weight-bold text-white">Complete Management System for Your Clinic</h1>
                    <p class="lead mb-5 text-white-50">Streamline your practice with our all-in-one ERP. EMR, scheduling, billing, and patient engagement in one secure platform.</p>
                    <div class="d-flex">
                        <a href="#" class="btn btn-primary btn-lg px-4 mr-3 font-weight-bold" style="background-color: #04b8c4; border-color: #04b8c4;">Start Free Trial</a>
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
                            <h4 class="font-weight-bold mb-3">Digital EMR</h4>
                            <p class="text-muted">Secure electronic medical records with customizable templates for physiotherapy assessments.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-calendar-alt mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3">Smart Scheduling</h4>
                            <p class="text-muted">Drag-and-drop calendar, automated reminders, and online booking integration.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-file-invoice-dollar mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3">Billing & Invoicing</h4>
                            <p class="text-muted">Automated invoicing, insurance claim management, and financial reporting.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-users mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3">Staff Management</h4>
                            <p class="text-muted">Manage roles, permissions, schedules, and performance for your entire team.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-chart-line mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3">Analytics</h4>
                            <p class="text-muted">Deep insights into clinic performance, patient retention, and revenue growth.</p>
                        </div>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <i class="las la-shield-alt mb-3" style="font-size: 40px; color: #04b8c4;"></i>
                            <h4 class="font-weight-bold mb-3">Secure & Compliant</h4>
                            <p class="text-muted">HIPAA compliant security to keep your patient data safe and protected.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

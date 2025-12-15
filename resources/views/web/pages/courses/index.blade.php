@extends('web.layouts.app')

@section('title', 'Learning Platform | PhyzioLine')

@push('meta')
    <meta name="description" content="{{ __('Advance your physiotherapy career with our specialized courses and workshops. Certifications, video lessons, and practical training.') }}">
    <meta name="keywords" content="Medical Courses, CME, Physical Therapy Courses, دورات طبية, تعليم مستمر, Physiotherapy Education, تدريب علاج طبيعي">
@endpush

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section position-relative pt-150 pb-100 text-white" style="background: linear-gradient(rgba(2, 118, 127, 0.9), rgba(2, 118, 127, 0.8)), url('https://img.freepik.com/free-photo/group-students-library_23-2148166345.jpg'); background-size: cover; background-position: center; margin-top: 80px;">
        <div class="container text-center">
            <h1 class="mb-4 display-4 font-weight-bold">Advance Your Physiotherapy Career</h1>
            <p class="lead mb-5">Learn from world-class experts. Video courses, certifications, and practical workshops.</p>
            <div class="search-course mx-auto" style="max-width: 600px;">
                <form action="#" class="position-relative">
                    <input type="text" class="form-control form-control-lg rounded-pill pl-4 pr-5" placeholder="What do you want to learn?">
                    <button type="submit" class="btn btn-primary rounded-circle position-absolute" style="right: 5px; top: 5px; width: 40px; height: 40px; background-color: #04b8c4; border-color: #04b8c4; padding: 0;">
                        <i class="las la-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-5 bg-light border-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-auto mb-3"><a href="#" class="btn btn-outline-secondary rounded-pill px-4">Manual Therapy</a></div>
                <div class="col-auto mb-3"><a href="#" class="btn btn-outline-secondary rounded-pill px-4">Sports Rehab</a></div>
                <div class="col-auto mb-3"><a href="#" class="btn btn-outline-secondary rounded-pill px-4">Neurology</a></div>
                <div class="col-auto mb-3"><a href="#" class="btn btn-outline-secondary rounded-pill px-4">Pediatrics</a></div>
                <div class="col-auto mb-3"><a href="#" class="btn btn-outline-secondary rounded-pill px-4">Business & Marketing</a></div>
            </div>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-100">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h2 class="font-weight-bold" style="color: #36415A;">Featured Courses</h2>
                    <p class="text-muted mb-0">Top rated courses by our community</p>
                </div>
                <a href="#" class="font-weight-bold" style="color: #04b8c4;">View All Courses <i class="las la-arrow-right"></i></a>
            </div>

            <div class="row">
                <!-- Course Card 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="https://img.freepik.com/free-photo/doctor-examining-patient-s-knee_1098-18302.jpg" class="card-img-top" alt="Course" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge badge-light text-muted">Manual Therapy</span>
                                <span class="text-warning"><i class="las la-star"></i> 4.8</span>
                            </div>
                            <h5 class="card-title font-weight-bold mb-2">Advanced Knee Rehabilitation Techniques</h5>
                            <p class="text-muted small mb-3">Dr. Sarah Johnson</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 font-weight-bold" style="color: #04b8c4;">$49.99</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Course Card 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="https://img.freepik.com/free-photo/physiotherapist-doing-massage-man-s-shoulder_329181-18756.jpg" class="card-img-top" alt="Course" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge badge-light text-muted">Massage</span>
                                <span class="text-warning"><i class="las la-star"></i> 4.9</span>
                            </div>
                            <h5 class="card-title font-weight-bold mb-2">Mastering Deep Tissue Massage</h5>
                            <p class="text-muted small mb-3">Mark Davis, PT</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 font-weight-bold" style="color: #04b8c4;">$39.99</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Course Card 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="https://img.freepik.com/free-photo/physiotherapist-working-with-patient-clinic_23-2149099617.jpg" class="card-img-top" alt="Course" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge badge-light text-muted">Exercise</span>
                                <span class="text-warning"><i class="las la-star"></i> 4.7</span>
                            </div>
                            <h5 class="card-title font-weight-bold mb-2">Therapeutic Exercise Prescription</h5>
                            <p class="text-muted small mb-3">Prof. Emily Chen</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 font-weight-bold" style="color: #04b8c4;">$59.99</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

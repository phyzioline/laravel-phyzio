@extends('web.layouts.app')

@section('title', 'Book Home Physiotherapy Appointments | PhyzioLine')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section position-relative pt-150 pb-100" style="background-color: #02767F; margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-4 display-4 font-weight-bold text-white">Expert Physiotherapy at Your Doorstep</h1>
                    <p class="lead mb-5 text-white-50">Book certified physiotherapists for home visits. Personalized care, professional treatment, and convenient scheduling.</p>
                    
                    <!-- Search Box -->
                    <div class="search-box bg-white p-4 rounded shadow-lg">
                        <form action="#" class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted">Specialty</label>
                                    <select class="form-control border-0 bg-light">
                                        <option>All Specialties</option>
                                        <option>Sports Injury</option>
                                        <option>Post-Surgery</option>
                                        <option>Geriatric</option>
                                        <option>Pediatric</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted">Location</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Area or City">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block h-100 font-weight-bold" style="background-color: #04b8c4; border-color: #04b8c4;">
                                    Find Therapist
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://img.freepik.com/free-photo/physiotherapist-doing-healing-treatment-patient_23-2149099613.jpg" alt="Home Physiotherapy" class="img-fluid rounded-lg shadow-lg" style="border-radius: 20px;">
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section class="py-100 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-weight-bold" style="color: #36415A;">How It Works</h2>
                <p class="text-muted">Simple steps to get the care you need</p>
            </div>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: rgba(4, 184, 196, 0.1);">
                        <i class="las la-search" style="font-size: 32px; color: #04b8c4;"></i>
                    </div>
                    <h4 class="font-weight-bold mb-3">Search</h4>
                    <p class="text-muted">Find the best physiotherapist based on specialty, location, and reviews.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: rgba(4, 184, 196, 0.1);">
                        <i class="las la-calendar-check" style="font-size: 32px; color: #04b8c4;"></i>
                    </div>
                    <h4 class="font-weight-bold mb-3">Book</h4>
                    <p class="text-muted">Choose a convenient time for your home visit and book instantly.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: rgba(4, 184, 196, 0.1);">
                        <i class="las la-user-md" style="font-size: 32px; color: #04b8c4;"></i>
                    </div>
                    <h4 class="font-weight-bold mb-3">Treat</h4>
                    <p class="text-muted">Receive professional care in the comfort of your home.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-100" style="background-color: #36415A;">
        <div class="container text-center text-white">
            <h2 class="font-weight-bold mb-4">Are you a Physiotherapist?</h2>
            <p class="lead mb-5">Join our network of professionals and grow your practice.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 font-weight-bold" style="color: #36415A;">Join as a Therapist</a>
        </div>
    </section>
</main>
@endsection

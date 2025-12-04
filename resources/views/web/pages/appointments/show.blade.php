@extends('web.layouts.app')

@section('content')
<main>
    <!-- Profile Header -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $therapist->user->image ?? asset('web/assets/images/default-user.png') }}" 
                         class="rounded-circle img-fluid shadow-sm mb-3 mb-md-0" 
                         style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #fff;">
                </div>
                <div class="col-md-6">
                    <h2 class="font-weight-bold mb-2">{{ $therapist->user->name }}</h2>
                    <p class="text-primary font-weight-bold mb-2">{{ $therapist->specialization }}</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="las la-star {{ $i <= $therapist->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="font-weight-bold ml-1">{{ $therapist->rating }}</span>
                        </div>
                        <div class="text-muted">
                            <i class="las la-comment"></i> {{ $therapist->total_reviews }} Reviews
                        </div>
                    </div>
                    
                    <p class="text-muted mb-4">
                        {{ $therapist->bio }}
                    </p>
                    
                    <div class="d-flex flex-wrap">
                        <div class="mr-4 mb-2">
                            <i class="las la-briefcase text-primary mr-1"></i>
                            <span class="font-weight-bold">{{ $therapist->years_experience }}+ Years Exp.</span>
                        </div>
                        <div class="mr-4 mb-2">
                            <i class="las la-map-marker text-primary mr-1"></i>
                            <span>{{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 3)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Home Visit Fees</h6>
                            <h2 class="text-primary font-weight-bold mb-4">{{ $therapist->home_visit_rate }} EGP</h2>
                            
                            <a href="{{ url('/appointments/book/'.$therapist->id) }}" class="btn btn-block text-white font-weight-bold py-3 mb-2" style="background-color: #ea3d2f;">
                                Book Appointment
                            </a>
                            <p class="small text-muted mb-0">No booking fees</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- About -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3">About Doctor</h4>
                        <p class="text-muted">
                            {{ $therapist->bio }}
                        </p>
                    </div>
                    
                    <!-- Services -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3">Services</h4>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="las la-check-circle text-success mr-2"></i>
                                    <span>Home Physical Therapy</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="las la-check-circle text-success mr-2"></i>
                                    <span>Post-Surgery Rehabilitation</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="las la-check-circle text-success mr-2"></i>
                                    <span>Sports Injury Recovery</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="las la-check-circle text-success mr-2"></i>
                                    <span>Elderly Care</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="mb-5">
                        <h4 class="font-weight-bold mb-3">Patient Reviews</h4>
                        
                        @if($therapist->appointments->count() > 0)
                            <!-- Loop through reviews if we had a review model, for now placeholder -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="font-weight-bold">Patient Name</h6>
                                        <div class="text-warning">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">Great experience, very professional.</p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Availability Calendar Placeholder -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="font-weight-bold mb-0">Working Hours</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Saturday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Sunday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Monday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Tuesday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Wednesday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Thursday</span>
                                    <span class="text-success">10:00 AM - 10:00 PM</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>Friday</span>
                                    <span class="text-danger">Closed</span>
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

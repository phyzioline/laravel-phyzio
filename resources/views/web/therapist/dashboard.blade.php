@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Welcome back, Dr.') }} {{ Auth::user()->name }}</h2>
            <p class="text-muted">{{ __('Here\'s what\'s happening with your practice today') }}</p>
        </div>
        <div class="d-flex align-items-center">
             <a href="{{ route('therapist.availability.edit') }}" class="btn btn-outline-primary mr-2 shadow-sm">
                <i class="las la-calendar-check"></i> {{ __('Set Availability') }}
            </a>
            <button class="btn btn-primary shadow-sm">
                <i class="las la-video"></i> {{ __('Start Consultation') }}
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Pending Requests -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow border-0 h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="icon-circle bg-warning-light text-warning mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #fff3cd;">
                                <i class="las la-clock" style="font-size: 20px;"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">8</h3>
                            <div class="text-muted small">{{ __('Pending Requests') }}</div>
                            <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('+2 from yesterday') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow border-0 h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="icon-circle bg-teal-light text-teal mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #e0f2f1; color: #00897b;">
                                <i class="las la-calendar-day" style="font-size: 20px;"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">12</h3>
                            <div class="text-muted small">{{ __('Today\'s Appointments') }}</div>
                            <div class="text-info small mt-1"><i class="las la-clock"></i> {{ __('3 upcoming') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow border-0 h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="icon-circle bg-success-light text-success mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #d1e7dd;">
                                <i class="las la-user-injured" style="font-size: 20px;"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">156</h3>
                            <div class="text-muted small">{{ __('Total Patients') }}</div>
                            <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('+5 this week') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Earnings -->
        <div class="col-xl-3 col-md-6 mb-4">
             <div class="card shadow border-0 h-100 py-2">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                              <!-- Placeholder for dollar or currency based on locale -->
                            <div class="icon-circle bg-info-light text-info mb-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #cff4fc;">
                                <i class="las la-dollar-sign" style="font-size: 20px;"></i>
                            </div>
                            <h3 class="font-weight-bold text-dark mb-1">$2,450</h3>
                            <div class="text-muted small">{{ __('This Month\'s Earnings') }}</div>
                            <div class="text-success small mt-1"><i class="las la-arrow-up"></i> {{ __('+15% from last month') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white border-0">
                    <h5 class="m-0 font-weight-bold text-dark">{{ __('Today\'s Schedule') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <!-- Item 1 -->
                        <div class="list-group-item d-flex align-items-center py-3 border-bottom-0 mb-2">
                             <div class="avatar-circle bg-teal text-white rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 48px; height: 48px; min-width: 48px; background-color: #00897b;">
                                JD
                            </div>
                            <div class="flex-grow-1 ml-3">
                                <h6 class="mb-1 font-weight-bold text-dark">John Doe</h6>
                                <p class="mb-0 text-muted small">Cardiology Consultation</p>
                                <div class="text-primary small font-weight-bold"><i class="las la-clock"></i> 10:00 AM - 10:30 AM</div>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-outline-secondary btn-sm rounded-circle mr-2 w-100 h-100 p-2"><i class="las la-eye"></i></button>
                                <button class="btn btn-teal btn-sm rounded text-white" style="background-color: #00897b;"><i class="las la-video"></i></button>
                            </div>
                        </div>

                         <!-- Item 2 -->
                        <div class="list-group-item d-flex align-items-center py-3 border-bottom-0 mb-2">
                             <div class="avatar-circle bg-teal text-white rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 48px; height: 48px; min-width: 48px; background-color: #00897b;">
                                SM
                            </div>
                            <div class="flex-grow-1 ml-3">
                                <h6 class="mb-1 font-weight-bold text-dark">Sarah Miller</h6>
                                <p class="mb-0 text-muted small">Follow-up Consultation</p>
                                <div class="text-primary small font-weight-bold"><i class="las la-clock"></i> 2:00 PM - 2:30 PM</div>
                            </div>
                             <div class="d-flex">
                                <button class="btn btn-outline-secondary btn-sm rounded-circle mr-2 w-100 h-100 p-2"><i class="las la-eye"></i></button>
                                <button class="btn btn-teal btn-sm rounded text-white" style="background-color: #00897b;"><i class="las la-video"></i></button>
                            </div>
                        </div>

                         <!-- Item 3 -->
                        <div class="list-group-item d-flex align-items-center py-3 border-bottom-0 mb-2">
                             <div class="avatar-circle bg-teal text-white rounded-circle mr-3 d-flex align-items-center justify-content-center font-weight-bold" style="width: 48px; height: 48px; min-width: 48px; background-color: #00897b;">
                                MJ
                            </div>
                            <div class="flex-grow-1 ml-3">
                                <h6 class="mb-1 font-weight-bold text-dark">Michael Johnson</h6>
                                <p class="mb-0 text-muted small">Initial Consultation</p>
                                <div class="text-primary small font-weight-bold"><i class="las la-clock"></i> 4:00 PM - 4:30 PM</div>
                            </div>
                             <div class="d-flex">
                                <button class="btn btn-outline-secondary btn-sm rounded-circle mr-2 w-100 h-100 p-2"><i class="las la-eye"></i></button>
                                <button class="btn btn-teal btn-sm rounded text-white" style="background-color: #00897b;"><i class="las la-video"></i></button>
                            </div>
                        </div>

                    </div>
                    <div class="text-center py-3">
                        <a href="{{ route('therapist.appointments.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">{{ __('View All Appointments') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Reviews -->
        <div class="col-lg-4">
             <!-- Quick Actions -->
             <div class="card shadow mb-4 border-0">
                <div class="card-header py-3 bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-dark">{{ __('Quick Actions') }}</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('therapist.appointments.index') }}" class="btn btn-outline-secondary btn-block text-left mb-2 d-flex align-items-center">
                        <i class="las la-calendar-check mr-2"></i> {{ __('Review Pending Requests (8)') }}
                    </a>
                    <a href="{{ route('therapist.schedule.index') }}" class="btn btn-outline-secondary btn-block text-left mb-2 d-flex align-items-center">
                        <i class="las la-clock mr-2"></i> {{ __('Update Availability') }}
                    </a>
                    <a href="{{ route('therapist.profile.edit') }}" class="btn btn-outline-secondary btn-block text-left mb-2 d-flex align-items-center">
                        <i class="las la-user-edit mr-2"></i> {{ __('Edit Profile') }}
                    </a>
                     <a href="{{ route('therapist.earnings.index') }}" class="btn btn-outline-warning btn-block text-left mb-2 d-flex align-items-center">
                        <i class="las la-file-invoice-dollar mr-2"></i> {{ __('View Earnings Report') }}
                    </a>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card shadow mb-4 border-0">
                 <div class="card-header py-3 bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-dark">{{ __('Recent Reviews') }}</h6>
                </div>
                <div class="card-body">
                    <!-- Review 1 -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                             <div class="bg-teal text-white rounded-circle mr-2 d-flex align-items-center justify-content-center small" style="width: 24px; height: 24px; background-color: #00897b;">
                                AL
                            </div>
                            <span class="font-weight-bold text-dark small">Anna Lee</span>
                             <div class="ml-auto text-warning small">
                                <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0 font-italic">"Excellent consultation. Very professional and helpful."</p>
                    </div>
                     <!-- Review 2 -->
                    <div class="mb-3">
                         <div class="d-flex align-items-center mb-1">
                             <div class="bg-teal text-white rounded-circle mr-2 d-flex align-items-center justify-content-center small" style="width: 24px; height: 24px; background-color: #00897b;">
                                RW
                            </div>
                            <span class="font-weight-bold text-dark small">Robert Wilson</span>
                             <div class="ml-auto text-warning small">
                                <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                            </div>
                        </div>
                        <p class="text-muted small mb-0 font-italic">"Great experience. Doctor was very knowledgeable."</p>
                    </div>

                    <div class="text-muted text-center small mt-3">
                        Average Rating: 4.9/5 (156 reviews)
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

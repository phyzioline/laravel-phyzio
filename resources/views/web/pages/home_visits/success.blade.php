@extends('web.layouts.app')

@section('content')
<main>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="mb-4">
                        <i class="las la-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="font-weight-bold mb-3">Booking Confirmed!</h2>
                    <p class="text-muted lead mb-5">
                        Your appointment with <strong>{{ $appointment->therapist->name }}</strong> has been successfully booked.
                    </p>
                    
                    <div class="card border-0 shadow-sm mb-5 text-left">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold border-bottom pb-3 mb-3">Appointment Details</h5>
                            
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Date</div>
                                <div class="col-8 font-weight-bold">{{ $appointment->appointment_time->format('d M Y') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Time</div>
                                <div class="col-8 font-weight-bold">{{ $appointment->appointment_time->format('h:i A') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Location</div>
                                <div class="col-8">{{ $appointment->location_address }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Payment</div>
                                <div class="col-8">
                                    <span class="badge badge-success px-3 py-2">
                                        {{ ucfirst($appointment->payment_method) }} - {{ ucfirst($appointment->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/patient/dashboard') }}" class="btn btn-outline-primary px-5 mr-3">Go to Dashboard</a>
                        <a href="{{ url('/') }}" class="btn btn-primary px-5 text-white">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

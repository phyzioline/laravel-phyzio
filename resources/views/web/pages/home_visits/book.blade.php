@extends('web.layouts.app')

@section('content')
<main>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white border-bottom p-4">
                            <h3 class="font-weight-bold mb-0">Book Appointment</h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Therapist Summary -->
                            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                <img src="{{ $therapist->user->image ? asset($therapist->user->image) : asset('web/assets/images/default-user.png') }}" 
                                     class="rounded-circle mr-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h5 class="font-weight-bold mb-1">{{ $therapist->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $therapist->specialization }}</p>
                                </div>
                                <div class="ml-auto text-right">
                                    <span class="d-block text-muted small">Fees</span>
                                    <span class="h5 font-weight-bold text-primary">{{ $therapist->home_visit_rate }} EGP</span>
                                </div>
                            </div>

                            <form action="{{ route('web.home_visits.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                                
                                <!-- Step 1: Date & Time -->
                                <h5 class="font-weight-bold mb-3">1. Select Date & Time</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" name="appointment_date" class="form-control" required min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Time</label>
                                            <select name="appointment_time" class="form-control" required>
                                                <option value="">Select Time</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="12:00">12:00 PM</option>
                                                <option value="13:00">01:00 PM</option>
                                                <option value="14:00">02:00 PM</option>
                                                <option value="15:00">03:00 PM</option>
                                                <option value="16:00">04:00 PM</option>
                                                <option value="17:00">05:00 PM</option>
                                                <option value="18:00">06:00 PM</option>
                                                <option value="19:00">07:00 PM</option>
                                                <option value="20:00">08:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Location -->
                                <h5 class="font-weight-bold mb-3">2. Location Details</h5>
                                <div class="form-group mb-4">
                                    <label>Home Address</label>
                                    <input type="text" name="location_address" class="form-control" placeholder="Enter full address (Street, Building, Apartment)" required>
                                </div>
                                
                                <!-- Step 3: Patient Details -->
                                <h5 class="font-weight-bold mb-3">3. Patient Details</h5>
                                <div class="form-group mb-3">
                                    <label>Patient Name</label>
                                    <input type="text" name="patient_name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Phone Number</label>
                                    <input type="tel" name="patient_phone" class="form-control" value="{{ auth()->user()->phone ?? '' }}" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Medical Notes / Complaint (Optional)</label>
                                    <textarea name="patient_notes" class="form-control" rows="3" placeholder="Briefly describe the condition..."></textarea>
                                </div>

                                <!-- Summary -->
                                <div class="bg-light p-3 rounded mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Consultation Fee</span>
                                        <span>{{ $therapist->home_visit_rate }} EGP</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Booking Fee</span>
                                        <span>0 EGP</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between font-weight-bold">
                                        <span>Total</span>
                                        <span class="text-primary">{{ $therapist->home_visit_rate }} EGP</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block text-white font-weight-bold py-3" style="background-color: #ea3d2f;">
                                    Confirm Booking
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

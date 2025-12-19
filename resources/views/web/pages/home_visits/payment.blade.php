@extends('web.layouts.app')

@section('content')
<main>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white border-bottom p-4 text-center">
                            <h3 class="font-weight-bold mb-0">Complete Payment</h3>
                            <p class="text-muted mb-0">Visit ID: #{{ $visit->id }}</p>
                        </div>
                        <div class="card-body p-4">
                            <!-- Summary -->
                            <div class="bg-light p-3 rounded mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Therapist</span>
                                    <span class="font-weight-bold">{{ $visit->therapist->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Date & Time</span>
                                    <span class="font-weight-bold">{{ $visit->scheduled_at }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Location</span>
                                    <span class="font-weight-bold">{{ Str::limit($visit->address, 30) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between font-weight-bold h5">
                                    <span>Total Amount</span>
                                    <span class="text-primary">{{ $visit->total_amount }} EGP</span>
                                </div>
                            </div>

                            <form action="{{ route('web.home_visits.process_payment', $visit->id) }}" method="POST">
                                @csrf
                                <h5 class="font-weight-bold mb-3">Select Payment Method</h5>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-radio mb-3 border p-3 rounded">
                                        <input type="radio" id="card" name="payment_method" value="card" class="custom-control-input" checked>
                                        <label class="custom-control-label d-flex align-items-center justify-content-between w-100" for="card">
                                            <span>
                                                <i class="las la-credit-card mr-2 text-primary" style="font-size: 24px;"></i>
                                                Pay with Card
                                            </span>
                                            <span class="text-muted small">Visa / Mastercard</span>
                                        </label>
                                    </div>
                                    
                                    <div class="custom-control custom-radio mb-3 border p-3 rounded">
                                        <input type="radio" id="cash" name="payment_method" value="cash" class="custom-control-input">
                                        <label class="custom-control-label d-flex align-items-center justify-content-between w-100" for="cash">
                                            <span>
                                                <i class="las la-money-bill-wave mr-2 text-success" style="font-size: 24px;"></i>
                                                Cash on Visit
                                            </span>
                                            <span class="text-muted small">Pay directly to therapist</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block text-white font-weight-bold py-3 mt-4" style="background-color: #04b8c4;">
                                    Pay {{ $visit->total_amount }} EGP
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

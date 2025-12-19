@extends('web.layouts.app')

@section('title', 'Visit Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    @if($visit->status == 'requested')
                        <div class="py-4">
                            <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                            <h3 class="mt-3">Finding a Therapist...</h3>
                            <p class="text-muted">We are notifying nearby professionals.</p>
                        </div>
                    @elseif($visit->status == 'accepted')
                        <div class="py-4">
                            <i class="las la-user-check text-success display-3"></i>
                            <h3 class="mt-3">Therapist Found!</h3>
                            <p class="lead">{{ $visit->therapist->name }} accepted your request.</p>
                            <hr>
                            <p class="text-muted">They will start the trip soon.</p>
                        </div>
                    @elseif($visit->status == 'on_way')
                        <div class="py-4">
                            <i class="las la-car-side text-warning display-3"></i>
                            <h3 class="mt-3">Therapist is On The Way</h3>
                            <p class="lead">{{ $visit->therapist->name }} is driving to your location.</p>
                            <div class="alert alert-warning">
                                Estimated Arrival: 15 mins
                            </div>
                        </div>
                    @elseif($visit->status == 'in_session')
                        <div class="py-4">
                            <i class="las la-procedures text-info display-3"></i>
                            <h3 class="mt-3">Session in Progress</h3>
                            <p class="text-muted">Your session has started at {{ $visit->arrived_at->format('h:i A') }}</p>
                        </div>
                     @elseif($visit->status == 'completed')
                        <div class="py-4">
                            <i class="las la-check-circle text-success display-3"></i>
                            <h3 class="mt-3">Visit Completed</h3>
                            <p class="text-muted">Thank you for using Phyzioline Home Visits.</p>
                            <button class="btn btn-outline-primary mt-3">Rate Therapist</button>
                        </div>
                    @endif

                    <div class="mt-4 border-top pt-4 text-left">
                        <h5>Visit Details</h5>
                        <p><strong>Condition:</strong> {{ $visit->complain_type }}</p>
                        <p><strong>Address:</strong> {{ $visit->address }}</p>
                        <p><strong>Scheduled:</strong> {{ $visit->scheduled_at->format('D, d M h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Auto-refresh for status updates logic
    @if(in_array($visit->status, ['requested', 'accepted', 'on_way']))
        setTimeout(function(){
           location.reload();
        }, 10000);
    @endif
</script>
@endsection

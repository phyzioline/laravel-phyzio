@extends('web.layouts.app')

@section('title', __('Book Home Visit Physiotherapy'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0 font-weight-bold"><i class="las la-ambulance mr-2"></i> {{ __('Request Home Physiotherapist') }}</h3>
                    <p class="mb-0 opacity-75">{{ __('Professional clinical care at your doorstep') }}</p>
                </div>
                <div class="card-body p-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('patient.visits.store') }}" method="POST" id="visitForm">
                        @csrf
                        
                        <!-- Step 1: Condition -->
                        <h5 class="text-primary mb-3"><i class="las la-stethoscope"></i> {{ __('What is the issue?') }}</h5>
                        <div class="row mb-4">
                            @foreach(['Orthopedic (Bone/Muscle)' => 'bone', 'Neurological (Stroke/Nerve)' => 'brain', 'Post-Surgery' => 'procedure', 'Elderly Care' => 'blind', 'Pediatric' => 'baby', 'Sports Injury' => 'running'] as $label => $icon)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="btn btn-outline-light text-dark btn-block border h-100 py-3 shadow-sm complain-option">
                                    <input type="radio" name="complain_type" value="{{ $label }}" class="d-none" required>
                                    <i class="las la-{{ $icon }} la-3x mb-2 text-primary"></i><br>
                                    <small class="font-weight-bold">{{ $label }}</small>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Step 2: Location -->
                        <h5 class="text-primary mb-3"><i class="las la-map-marker"></i> {{ __('Where do you need us?') }}</h5>
                        <div class="form-group">
                            <input type="text" name="address" class="form-control form-control-lg" placeholder="Enter your detailed address" required>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <input type="number" step="any" name="lat" class="form-control" placeholder="Latitude (Auto)" value="30.0444" required readonly>
                            </div>
                            <div class="col">
                                <input type="number" step="any" name="lng" class="form-control" placeholder="Longitude (Auto)" value="31.2357" required readonly>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-secondary" onclick="alert('Geolocation would auto-fill here')"><i class="las la-crosshairs"></i> Locate Me</button>
                            </div>
                        </div>

                        <!-- Step 3: Time & Urgency -->
                        <h5 class="text-primary mb-3 mt-4"><i class="las la-clock"></i> {{ __('When?') }}</h5>
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-danger active">
                                    <input type="radio" name="urgency" value="urgent" checked> 
                                    <i class="las la-exclamation-circle"></i> ASAP (Urgent)
                                </label>
                                <label class="btn btn-outline-primary">
                                    <input type="radio" name="urgency" value="normal"> 
                                    <i class="las la-calendar"></i> Schedule Later
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="scheduleInput" style="display:none;">
                            <input type="datetime-local" name="scheduled_at" class="form-control">
                        </div>

                        <hr>
                        
                        <button type="submit" class="btn btn-success btn-lg btn-block shadow-lg">
                            {{ __('Request Therapist Now') }} <i class="las la-arrow-right ml-2"></i>
                        </button>
                        <p class="text-center text-muted mt-2"><small>By requesting, you agree to our clinical terms of service.</small></p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple interactions
    document.querySelectorAll('input[name="complain_type"]').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.complain-option').forEach(l => l.classList.remove('bg-primary', 'text-white'));
            this.closest('label').classList.add('bg-primary', 'text-white');
        });
    });

    document.querySelectorAll('input[name="urgency"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('scheduleInput').style.display = this.value === 'normal' ? 'block' : 'none';
        });
    });
</script>
@endsection

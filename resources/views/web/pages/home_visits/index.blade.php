@extends('web.layouts.app')

@section('title', __('Home Physical Therapy Visits'))

@push('meta')
    <meta name="description" content="{{ __('Book a professional physical therapist for home visits. Expert care at your doorstep from certified specialists.') }}">
    <meta name="keywords" content="Home Physical Therapy, Doctor Visit, زيارات منزلية, دكتور علاج طبيعي, Home Rehabilitation, تأهيل منزلي, Phyzioline Home Visits">
@endpush

@section('content')
<main>
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero-section py-5 position-relative" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); padding-top: 180px !important; padding-bottom: 100px !important;">
        <div class="container">
            <div class="row align-items-start">
                <!-- Left: Title & Context -->
                <div class="col-lg-5 text-white mb-5 mb-lg-0 pt-lg-5">
                    <h1 class="font-weight-bold mb-4 display-4">{{ __('Home Physical Therapy') }}</h1>
                    <p class="lead mb-5 opacity-90">{{ __('Expert physiotherapists at your doorstep. Urgent requests or scheduled appointments.') }}</p>
                    
                    <div class="d-none d-lg-block">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box bg-white text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px;">
                                <i class="las la-check-circle la-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ __('Certified Specialists') }}</h5>
                                <small class="opacity-75">{{ __('Verified professionals') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-white text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px;">
                                <i class="las la-shield-alt la-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">{{ __('Safe & Secure') }}</h5>
                                <small class="opacity-75">{{ __('Health & Safety protocols') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Unified Request Box -->
                <div class="col-lg-7">
                    <div class="card shadow-2xl border-0 overflow-hidden" style="border-radius: 20px;">
                        <div class="card-header bg-white p-0 border-0">
                            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active py-3 font-weight-bold rounded-0" id="pills-request-tab" data-toggle="pill" href="#pills-request" role="tab" aria-controls="pills-request" aria-selected="true" style="font-size: 1.1rem;">
                                        <i class="las la-ambulance mr-2"></i> {{ __('Request Visit') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-3 font-weight-bold rounded-0" id="pills-search-tab" data-toggle="pill" href="#pills-search" role="tab" aria-controls="pills-search" aria-selected="false" style="font-size: 1.1rem;">
                                        <i class="las la-search mr-2"></i> {{ __('Search Therapist') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body p-4 bg-light">
                            <div class="tab-content" id="pills-tabContent">
                                
                                <!-- Tab 1: Direct Request (from visits/create) -->
                                <div class="tab-pane fade show active" id="pills-request" role="tabpanel">
                                    <form action="{{ route('patient.visits.store') }}" method="POST" id="visitForm">
                                        @csrf
                                        
                                        <!-- Condition -->
                                        <h6 class="text-primary font-weight-bold mb-3"><i class="las la-stethoscope"></i> {{ __('Condition Type') }}</h6>
                                        <div class="row no-gutters mb-3">
                                            @foreach(['Orthopedic' => 'bone', 'Neurological' => 'brain', 'Post-Surgery' => 'procedure', 'Elderly' => 'blind', 'Pediatric' => 'baby', 'Sports' => 'running'] as $label => $icon)
                                            <div class="col-4 col-md-4 p-1">
                                                <label class="btn btn-outline-white bg-white text-dark btn-block border p-2 shadow-sm complain-option mb-0 h-100 d-flex flex-column align-items-center justify-content-center">
                                                    <input type="radio" name="complain_type" value="{{ $label }}" class="d-none" required>
                                                    <i class="las la-{{ $icon }} la-2x mb-1 text-primary"></i>
                                                    <small class="font-weight-bold" style="font-size: 0.75rem; line-height: 1.1;">{{ $label }}</small>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Address -->
                                        <div class="form-group mb-3">
                                            <div class="input-group input-group-lg shadow-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white border-0"><i class="las la-map-marker text-danger"></i></span>
                                                </div>
                                                <input type="text" name="address" class="form-control border-0" placeholder="Address (Area, Street, Building...)" required>
                                            </div>
                                            <!-- Hidden Lat/Lng defaults -->
                                            <input type="hidden" name="lat" value="30.0444">
                                            <input type="hidden" name="lng" value="31.2357">
                                        </div>

                                        <!-- Urgency -->
                                        <div class="form-group mb-4">
                                            <div class="btn-group btn-group-toggle w-100 shadow-sm" data-toggle="buttons">
                                                <label class="btn btn-white border active py-3">
                                                    <input type="radio" name="urgency" value="urgent" checked> 
                                                    <i class="las la-exclamation-circle text-danger"></i> <span class="font-weight-bold">{{ __('ASAP') }}</span>
                                                </label>
                                                <label class="btn btn-white border py-3">
                                                    <input type="radio" name="urgency" value="normal"> 
                                                    <i class="las la-calendar text-primary"></i> <span class="font-weight-bold">{{ __('Schedule Later') }}</span>
                                                </label>
                                            </div>
                                            <input type="datetime-local" name="scheduled_at" id="scheduleInput" class="form-control mt-2 shadow-sm border-0" style="display:none;">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-lg font-weight-bold" style="background-color: #02767F; border-color: #02767F;">
                                            {{ __('Request Now') }} <i class="las la-arrow-right ml-2"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Tab 2: Search (Existing) -->
                                <div class="tab-pane fade" id="pills-search" role="tabpanel">
                                    <form action="{{ url('/appointments') }}" method="GET">
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted">{{ __('Specialization') }}</label>
                                            <select name="specialization" class="form-control form-control-lg border-0 shadow-sm">
                                                <option value="">{{ __('All Specializations') }}</option>
                                                @foreach($specializations as $spec)
                                                    <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="font-weight-bold text-muted">{{ __('Area / Governance') }}</label>
                                            <select name="area" class="form-control form-control-lg border-0 shadow-sm">
                                                <option value="">{{ __('All Areas') }}</option>
                                                @foreach($areas as $area)
                                                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-lg font-weight-bold" style="background-color: #02767F; border-color: #02767F;">
                                            {{ __('Find Therapist') }} <i class="las la-search ml-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Therapists List -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h5 class="font-weight-bold mb-4">{{ __('Filters') }}</h5>
                        <form action="{{ url('/appointments') }}" method="GET">
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Gender') }}</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="male">
                                    <label class="custom-control-label" for="male">{{ __('Male') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="female">
                                    <label class="custom-control-label" for="female">{{ __('Female') }}</label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">{{ __('Availability') }}</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="today">
                                    <label class="custom-control-label" for="today">{{ __('Available Today') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tomorrow">
                                    <label class="custom-control-label" for="tomorrow">{{ __('Available Tomorrow') }}</label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-block btn-outline-primary mt-4">{{ __('Apply Filters') }}</button>
                        </form>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-lg-9">
                    @forelse($therapists as $therapist)
                    <div class="card border-0 shadow-sm mb-4 hover-card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ $therapist->user->image ?? asset('web/assets/images/default-user.png') }}" 
                                         class="rounded-circle img-fluid mb-2" 
                                         style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f8f9fa;">
                                </div>
                                <div class="col-md-6">
                                    <h4 class="font-weight-bold mb-1">
                                        <a href="{{ url('/appointments/therapist/'.$therapist->id) }}" class="text-dark text-decoration-none">
                                            {{ $therapist->user->name }}
                                        </a>
                                    </h4>
                                    <p class="text-muted mb-2">{{ $therapist->specialization }}</p>
                                    
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="las la-star {{ $i <= $therapist->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="text-muted small">({{ $therapist->total_reviews }} {{ __('reviews') }})</span>
                                    </div>
                                    
                                    <p class="mb-2 text-muted" style="font-size: 14px;">
                                        <i class="las la-briefcase text-primary scale-110 mr-1"></i> 
                                        <strong>{{ $therapist->years_experience }}+ {{ __('Years Exp.') }}</strong>
                                    </p>
                                    
                                    <p class="mb-2 small text-muted">
                                        <i class="las la-map-marker text-primary"></i> 
                                        {{ __('Available in') }}: {{ implode(', ', array_slice($therapist->available_areas ?? [], 0, 3)) }}
                                        {{ count($therapist->available_areas ?? []) > 3 ? '+'.(count($therapist->available_areas)-3).' '.__('more') : '' }}
                                    </p>
                                    
                                    <p class="mb-0 small">
                                        {{ Str::limit($therapist->bio, 100) }}
                                    </p>
                                </div>
                                <div class="col-md-3 text-center border-left">
                                    <div class="mb-3">
                                        <span class="d-block text-muted small">{{ __('Home Visit Fees') }}</span>
                                        <span class="h4 font-weight-bold text-primary">{{ $therapist->home_visit_rate }} {{ __('EGP') }}</span>
                                    </div>
                                    
                                    <a href="{{ url('/appointments/book/'.$therapist->id) }}" class="btn btn-block text-white font-weight-bold mb-2 shadow-lg" style="background-color: #02767F; border-bottom: 3px solid #FFD700; transition: all 0.3s ease;">
                                        {{ __('Book Now') }}
                                    </a>
                                    <a href="{{ url('/appointments/therapist/'.$therapist->id) }}" class="btn btn-block btn-outline-info btn-sm font-weight-bold" style="color: #02767F; border-color: #02767F;">
                                        {{ __('View Profile') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <img src="{{ asset('web/assets/images/no-results.svg') }}" style="width: 150px; opacity: 0.5;" class="mb-3">
                        <h4>{{ __('No therapists found') }}</h4>
                        <p class="text-muted">{{ __('Try adjusting your search filters') }}</p>
                    </div>
                    @endforelse

                    <div class="d-flex justify-content-center mt-4">
                        {{ $therapists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.hover-card {
    transition: transform 0.2s;
}
.hover-card:hover {
    transform: translateY(-5px);
}

header,
.header-section {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: transparent !important;
    box-shadow: none !important;
    z-index: 9999;
}
</style>
@section('scripts')
<script>
    // Tab visuals
    $('#pills-tab a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    // Option Selection
    document.querySelectorAll('input[name="complain_type"]').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.complain-option').forEach(l => {
                l.classList.remove('bg-primary', 'text-white'); 
                l.classList.add('bg-white', 'text-dark');
                l.querySelector('i').classList.add('text-primary');
                l.querySelector('i').classList.remove('text-white');
            });
            
            const label = this.closest('label');
            label.classList.remove('bg-white', 'text-dark');
            label.classList.add('bg-primary', 'text-white');
            label.querySelector('i').classList.remove('text-primary');
            label.querySelector('i').classList.add('text-white');
        });
    });

    // Schedule Toggle
    document.querySelectorAll('input[name="urgency"]').forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('scheduleInput').style.display = this.value === 'normal' ? 'block' : 'none';
        });
    });
</script>
@endsection
@endsection

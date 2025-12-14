@extends('web.layouts.app')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section py-5" style="background: linear-gradient(135deg, #02767F 0%, #10b8c4 100%); padding-top: 200px !important;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="text-white font-weight-bold mb-4">{{ __('Book a Home Visit Physiotherapist') }}</h1>
                    <p class="text-white lead mb-5">{{ __('Professional care in the comfort of your home') }}</p>
                    
                    <!-- Search Box -->
                    <div class="bg-white p-4 rounded shadow-lg">
                        <form action="{{ url('/appointments') }}" method="GET" class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <select name="specialization" class="form-control border-0 bg-light" style="height: 50px;">
                                    <option value="">{{ __('Select Specialization') }}</option>
                                    @foreach($specializations as $spec)
                                        <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <select name="area" class="form-control border-0 bg-light" style="height: 50px;">
                                    <option value="">{{ __('Select Area') }}</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-block text-white font-weight-bold shadow" style="background-color: #02767F; height: 50px; border-bottom: 3px solid #FFD700;">
                                    {{ __('Search Therapists') }}
                                </button>
                            </div>
                        </form>
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
@endsection

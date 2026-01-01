@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid px-4 py-5" style="background-color: #f4f7f6;">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h3 font-weight-bold text-gray-800 mb-1" style="color: #2c3e50;">{{ __('My Profile') }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">{{ __('Manage your personal information and public profile appearance') }}</p>
        </div>
        <button type="submit" form="profile-form" class="btn btn-primary px-4 py-2 shadow-sm d-flex align-items-center" style="background-color: #02767F; border-color: #02767F; border-radius: 8px;">
            <i class="las la-save mr-2" style="font-size: 1.2rem;"></i> 
            <span class="font-weight-bold">{{ __('Save Changes') }}</span>
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-lg mb-4" role="alert" style="border-left: 5px solid #28a745;">
            <div class="d-flex align-items-center">
                <i class="las la-check-circle mr-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h6 class="mb-0 font-weight-bold">{{ __('Success') }}</h6>
                    <small>{{ session('success') }}</small>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-lg mb-4" role="alert" style="border-left: 5px solid #dc3545;">
             <div class="d-flex align-items-center">
                <i class="las la-exclamation-triangle mr-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h6 class="mb-0 font-weight-bold">{{ __('Please correct the following errors:') }}</h6>
                    <ul class="mb-0 small pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar: Profile Photo & Key Stats -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body text-center p-5 position-relative">
                    <div class="position-absolute w-100" style="height: 100px; background: linear-gradient(135deg, #02767F 0%, #039ba7 100%); top: 0; left: 0;"></div>
                    
                    <div class="position-relative d-inline-block mt-4 mb-3">
                        <div class="avatar-circle rounded-circle bg-white d-flex align-items-center justify-content-center mx-auto shadow" style="width: 140px; height: 140px; border: 5px solid #fff; overflow: hidden; position: relative;">
                            @php
                                use Illuminate\Support\Facades\Storage;
                                $profileImage = null;
                                if(isset($user)) {
                                    $imagePath = $profile->profile_photo ?? $user->image ?? null;
                                    if($imagePath) {
                                        if(strpos($imagePath, 'http') === 0) {
                                            $profileImage = $imagePath;
                                        } elseif(strpos($imagePath, 'storage/') === 0) {
                                            $profileImage = asset($imagePath);
                                        } else {
                                            $profileImage = Storage::url($imagePath);
                                        }
                                    }
                                }
                            @endphp
                            
                            @if($profileImage)
                                <img src="{{ $profileImage }}" id="profile-preview" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='{{ asset('dashboard/images/avatar-placeholder.png') }}'; this.onerror=null;">
                            @else
                                <div id="default-avatar" class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                    <i class="las la-user" style="font-size: 60px;"></i>
                                </div>
                                <img id="profile-preview" src="" style="display:none;" class="w-100 h-100" style="object-fit: cover;">
                            @endif
                        </div>
                        <button type="button" onclick="document.getElementById('profile_image_input').click()" class="btn btn-primary rounded-circle position-absolute shadow-sm d-flex align-items-center justify-content-center" style="bottom: 5px; right: 5px; width: 40px; height: 40px; background-color: #02767F; border-color: #fff; border-width: 3px;" title="{{ __('Change Photo') }}">
                            <i class="las la-camera text-white"></i>
                        </button>
                    </div>

                    <h5 class="font-weight-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-3 badge bg-light text-dark px-3 py-1 font-weight-normal">{{ $profile->specialization ?? __('Physiotherapist') }}</p>

                    <div class="d-flex justify-content-center mt-4">
                        <div class="px-4 border-right">
                            <div class="h4 font-weight-bold text-dark mb-0">{{ number_format($rating ?? 0, 1) }} <i class="las la-star text-warning" style="font-size: 0.8em;"></i></div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Rating') }}</small>
                        </div>
                        <div class="px-4">
                            <div class="h4 font-weight-bold text-dark mb-0">{{ $totalPatients ?? 0 }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('Patients') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Health Widget (if applicable) -->
             @if(in_array(Auth::user()->type, ['vendor', 'company', 'therapist']))
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-0">
                        @include('web.components.account-health')
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="profile-form" method="POST" action="{{ route('therapist.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" name="profile_image" id="profile_image_input" style="display: none;" accept="image/*" onchange="previewImage(this)">

                <!-- Personal Information -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h6 class="font-weight-bold text-dark d-flex align-items-center">
                            <i class="las la-user-circle mr-2 text-primary" style="font-size: 1.4rem;"></i>
                            {{ __('Personal Information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-user"></i></span>
                                    <input type="text" name="name" class="form-control border-left-0" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Phone Number') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-phone"></i></span>
                                    <input type="text" name="phone" class="form-control border-left-0" value="{{ old('phone', $user->phone) }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-envelope"></i></span>
                                    <input type="email" class="form-control border-left-0 bg-light" value="{{ $user->email }}" disabled>
                                </div>
                                <small class="text-muted ms-2"><i class="las la-lock"></i> {{ __('Email cannot be changed directly.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Details -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                         <h6 class="font-weight-bold text-dark d-flex align-items-center">
                            <i class="las la-briefcase mr-2 text-primary" style="font-size: 1.4rem;"></i>
                            {{ __('Professional Details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Specialization') }}</label>
                                <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization) }}" placeholder="e.g. Sports Physiotherapy" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Professional Bio') }}</label>
                                <textarea name="bio" class="form-control" rows="5" placeholder="{{ __('Tell patients about your experience, certifications, and approach...') }}">{{ old('bio', $profile->bio) }}</textarea>
                            </div>
                             <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Home Visit Rate (EGP)') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-right-0 fw-bold">{{ __('EGP') }}</span>
                                    <input type="number" name="home_visit_rate" class="form-control border-left-0" value="{{ old('home_visit_rate', $profile->home_visit_rate) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                   <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                         <h6 class="font-weight-bold text-dark d-flex align-items-center">
                            <i class="las la-university mr-2 text-primary" style="font-size: 1.4rem;"></i>
                            {{ __('Payment Information') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-light border-0 bg-light-primary mb-4">
                            <div class="d-flex">
                                <i class="las la-shield-alt text-primary mr-3" style="font-size: 1.5rem;"></i>
                                <small class="text-muted" style="line-height: 1.4;">{{ __('Your bank details are securely stored and used only for processing your earnings payouts.') }}</small>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Bank Name') }}</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $profile->bank_name ?? '') }}" placeholder="{{ __('e.g. CIB') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Account Holder Name') }}</label>
                                <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name', $profile->bank_account_name ?? '') }}" placeholder="{{ __('Full Name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('IBAN') }}</label>
                                <input type="text" name="iban" class="form-control" value="{{ old('iban', $profile->iban ?? '') }}">
                            </div>
                             <div class="col-md-6">
                                <label class="form-label small font-weight-bold text-muted">{{ __('Swift Code') }} <small>({{ __('Optional') }})</small></label>
                                <input type="text" name="swift_code" class="form-control" value="{{ old('swift_code', $profile->swift_code ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- Service Areas -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                         <h6 class="font-weight-bold text-dark d-flex align-items-center">
                            <i class="las la-map-marked-alt mr-2 text-primary" style="font-size: 1.4rem;"></i>
                            {{ __('Service Areas') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted">{{ __('Governorate / City') }}</label>
                                <select class="form-select" id="citySelect">
                                    <option value="all">{{ __('Show All Cities') }}</option>
                                    @if(isset($locations['Egypt']))
                                        @foreach(array_keys($locations['Egypt']) as $city)
                                            <option value="{{ $city }}">{{ $city }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <div class="bg-light p-3 rounded" id="areasContainer" style="max-height: 350px; overflow-y: auto; border: 1px solid #e9ecef;">
                            @if(isset($locations['Egypt']))
                                @foreach($locations['Egypt'] as $city => $areas)
                                    <div class="area-group mb-3" data-city="{{ $city }}">
                                        <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-2 sticky-top bg-light" style="top: -1rem;">{{ $city }}</h6>
                                        <div class="row g-2">
                                            @foreach($areas as $area)
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="available_areas[]" value="{{ $area }}" id="area_{{ \Illuminate\Support\Str::slug($city) }}_{{ \Illuminate\Support\Str::slug($area) }}" 
                                                            {{ in_array($area, $profile->available_areas ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="area_{{ \Illuminate\Support\Str::slug($city) }}_{{ \Illuminate\Support\Str::slug($area) }}">{{ $area }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">{{ __('No location data available.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('profile-preview');
                var defaultAvatar = document.getElementById('default-avatar');
                
                if(preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                if(defaultAvatar) {
                    defaultAvatar.style.display = 'none';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('citySelect');
        const areaGroups = document.querySelectorAll('.area-group');

        if(citySelect) {
            citySelect.addEventListener('change', function() {
                const selectedCity = this.value;

                areaGroups.forEach(group => {
                    if (selectedCity === 'all' || group.getAttribute('data-city') === selectedCity) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection

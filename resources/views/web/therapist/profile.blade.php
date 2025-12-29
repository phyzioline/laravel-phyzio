@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="las la-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="las la-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="las la-exclamation-circle mr-2"></i> 
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('My Profile') }}</h2>
            <p class="text-muted">{{ __('Manage your personal and professional information') }}</p>
        </div>
        <div>
            <button type="submit" form="profile-form" class="btn btn-primary shadow-sm"><i class="las la-save"></i> {{ __('Save Changes') }}</button>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar / Image Card -->
        <div class="col-lg-4 mb-4">
             <div class="card shadow border-0 text-center">
                <div class="card-body">
                        <div class="position-relative d-inline-block mb-3">
                        <div class="avatar-circle rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; border: 4px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden;">
                            @php
                                use Illuminate\Support\Facades\Storage;
                                $profileImage = null;
                                if(isset($user)) {
                                    // Try profile_photo first, then image
                                    $imagePath = $profile->profile_photo ?? $user->image ?? null;
                                    if($imagePath) {
                                        // Handle different path formats
                                        if(strpos($imagePath, 'http') === 0) {
                                            // Already a full URL
                                            $profileImage = $imagePath;
                                        } elseif(strpos($imagePath, 'storage/') === 0) {
                                            // Path starts with storage/, use asset
                                            $profileImage = asset($imagePath);
                                        } else {
                                            // Relative path, use Storage::url
                                            $profileImage = Storage::url($imagePath);
                                        }
                                    }
                                }
                            @endphp
                            @if($profileImage)
                                <img src="{{ $profileImage }}" id="profile-preview" class="rounded-circle w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none'; document.getElementById('default-icon').style.display='block';">
                                <i class="las la-user text-muted" id="default-icon" style="font-size: 60px; display: none;"></i>
                            @else
                                <img id="profile-preview" src="" style="display:none;" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                <i class="las la-user text-muted" id="default-icon" style="font-size: 60px;"></i>
                            @endif
                        </div>
                        <button type="button" onclick="document.getElementById('profile_image_input').click()" class="btn btn-sm btn-primary rounded-circle position-absolute" style="bottom: 0; right: 0; width: 35px; height: 35px; padding: 0;" title="Change Photo">
                            <i class="las la-camera"></i>
                        </button>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-3">{{ $profile->specialization ?? 'Specialist' }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                         <div class="px-3 border-right">
                             <div class="font-weight-bold text-dark h5 mb-0">{{ number_format($rating ?? 0, 1) }}</div>
                             <small class="text-muted">{{ __('Rating') }}</small>
                             @if($totalReviews > 0)
                                 <small class="d-block text-muted" style="font-size: 0.7rem;">({{ $totalReviews }} {{ __('reviews') }})</small>
                             @endif
                         </div>
                         <div class="px-3">
                             <div class="font-weight-bold text-dark h5 mb-0">{{ $totalPatients ?? 0 }}</div>
                             <small class="text-muted">{{ __('Patients') }}</small>
                         </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Health Widget -->
            @if(in_array(Auth::user()->type, ['vendor', 'company', 'therapist']))
                <div class="card shadow border-0 mt-4">
                    @include('web.components.account-health')
                </div>
            @endif
        </div>

        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Details') }}</h6>
                </div>
                <div class="card-body">
                    <form id="profile-form" method="POST" action="{{ route('therapist.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="file" name="profile_image" id="profile_image_input" style="display: none;" accept="image/*" onchange="previewImage(this)">

                        <h6 class="text-uppercase text-muted small font-weight-bold mb-3 border-bottom pb-2">{{ __('Personal Information') }}</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-user"></i></span>
                                    </div>
                                    <input type="text" name="name" class="form-control border-left-0" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('Phone Number') }}</label>
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-phone"></i></span>
                                    </div>
                                    <input type="text" name="phone" class="form-control border-left-0" value="{{ old('phone', $user->phone) }}" required>
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="small font-weight-bold text-dark">{{ __('Email Address') }}</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="las la-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control border-left-0" value="{{ $user->email }}" disabled>
                            </div>
                            <small class="text-muted ml-1">{{ __('Email cannot be changed directly.') }}</small>
                        </div>

                        <h6 class="text-uppercase text-muted small font-weight-bold mb-3 mt-4 border-bottom pb-2">{{ __('Professional Details') }}</h6>
                         <div class="form-group">
                            <label class="small font-weight-bold text-dark">{{ __('Specialization') }}</label>
                            <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization) }}" placeholder="e.g. Sports Physiotherapy" required>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">{{ __('Professional Bio') }}</label>
                            <textarea name="bio" class="form-control" rows="4" placeholder="Tell patients about your experience...">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                        
                         <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('Home Visit Rate (EGP)') }}</label>
                                 <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-tags"></i></span>
                                    </div>
                                    <input type="number" name="home_visit_rate" class="form-control border-left-0" value="{{ old('home_visit_rate', $profile->home_visit_rate) }}" required>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted small font-weight-bold mb-3 mt-4 border-bottom pb-2">{{ __('Bank Details') }}</h6>
                        <div class="alert alert-info">
                            <i class="las la-info-circle"></i> 
                            <strong>{{ __('Payment Information') }}</strong><br>
                            {{ __('Add your bank details to receive payments. This information is kept secure and confidential.') }}
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('Bank Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-university"></i></span>
                                    </div>
                                    <input type="text" name="bank_name" class="form-control border-left-0" value="{{ old('bank_name', $profile->bank_name ?? '') }}" placeholder="{{ __('e.g. CIB, NBE, QNB') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('Account Holder Name') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-user"></i></span>
                                    </div>
                                    <input type="text" name="bank_account_name" class="form-control border-left-0" value="{{ old('bank_account_name', $profile->bank_account_name ?? '') }}" placeholder="{{ __('Full name as in bank account') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('IBAN') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-credit-card"></i></span>
                                    </div>
                                    <input type="text" name="iban" class="form-control border-left-0" value="{{ old('iban', $profile->iban ?? '') }}" placeholder="{{ __('EG123456789...') }}">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold text-dark">{{ __('SWIFT Code') }} <small class="text-muted">({{ __('Optional') }})</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="las la-globe"></i></span>
                                    </div>
                                    <input type="text" name="swift_code" class="form-control border-left-0" value="{{ old('swift_code', $profile->swift_code ?? '') }}" placeholder="{{ __('For international transfers') }}">
                                </div>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted small font-weight-bold mb-3 mt-4 border-bottom pb-2">{{ __('Service Areas') }}</h6>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark d-block mb-3">{{ __('Select areas you cover for home visits') }}</label>
                            
                            <!-- Location Filters -->
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">{{ __('Country') }}</label>
                                    <select class="form-control custom-select" id="countrySelect" disabled>
                                        <option value="Egypt" selected>Egypt</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">{{ __('Governorate / City') }}</label>
                                    <select class="form-control custom-select" id="citySelect">
                                        <option value="all">{{ __('Show All') }}</option>
                                        @if(isset($locations['Egypt']))
                                            @foreach(array_keys($locations['Egypt']) as $city)
                                                <option value="{{ $city }}">{{ $city }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <hr class="my-3">

                            <!-- Area Checkboxes -->
                            <div id="areasContainer" style="max-height: 400px; overflow-y: auto;">
                                @if(isset($locations['Egypt']))
                                    @foreach($locations['Egypt'] as $city => $areas)
                                        <div class="area-group" data-city="{{ $city }}">
                                            <h6 class="font-weight-bold text-primary mb-2 mt-2 px-1">{{ $city }}</h6>
                                            <div class="row px-1">
                                                @foreach($areas as $area)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="available_areas[]" value="{{ $area }}" class="custom-control-input" id="area_{{ \Illuminate\Support\Str::slug($city) }}_{{ \Illuminate\Support\Str::slug($area) }}" 
                                                                {{ in_array($area, $profile->available_areas ?? []) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="area_{{ \Illuminate\Support\Str::slug($city) }}_{{ \Illuminate\Support\Str::slug($area) }}">{{ $area }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">No location data available.</p>
                                @endif
                            </div>
                        </div>

                        <!-- JS for Filtering -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const citySelect = document.getElementById('citySelect');
                                const areaGroups = document.querySelectorAll('.area-group');

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
                            });
                        </script>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('profile-preview');
                var icon = document.getElementById('default-icon');
                
                if(preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                if(icon) {
                    icon.style.display = 'none';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

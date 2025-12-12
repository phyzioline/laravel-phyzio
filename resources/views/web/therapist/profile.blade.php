@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
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
                         <div class="avatar-circle rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; border: 4px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                            @if(isset($user) && $user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                            @else
                                <i class="las la-user text-muted" style="font-size: 60px;"></i>
                            @endif
                        </div>
                        <button class="btn btn-sm btn-primary rounded-circle position-absolute" style="bottom: 0; right: 0; width: 35px; height: 35px; padding: 0;" title="Change Photo">
                            <i class="las la-camera"></i>
                        </button>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-3">{{ $profile->specialization ?? 'Specialist' }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                         <div class="px-3 border-right">
                             <div class="font-weight-bold text-dark h5 mb-0">4.9</div>
                             <small class="text-muted">Rating</small>
                         </div>
                         <div class="px-3">
                             <div class="font-weight-bold text-dark h5 mb-0">156</div>
                             <small class="text-muted">Patients</small>
                         </div>
                    </div>
                </div>
            </div>
            
             <div class="card shadow border-0 mt-4">
                <div class="card-header bg-white font-weight-bold text-dark">{{ __('Account Status') }}</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ __('Verification') }}</span>
                        <span class="badge badge-success px-3 py-2">{{ __('Verified') }}</span>
                    </div>
                     <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Subscription') }}</span>
                        <span class="badge badge-info px-3 py-2">{{ __('Pro Plan') }}</span>
                    </div>
                </div>
            </div>
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

                        <h6 class="text-uppercase text-muted small font-weight-bold mb-3 mt-4 border-bottom pb-2">{{ __('Service Areas') }}</h6>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark d-block mb-2">{{ __('Select areas you cover for home visits') }}</label>
                            <div class="row">
                                @foreach(['Nasr City', 'New Cairo', 'Maadi', 'Giza', 'Dokki', 'Mohandessin', 'Zamalek', 'Sheikh Zayed', '6th of October'] as $area)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="available_areas[]" value="{{ $area }}" class="custom-control-input" id="area_{{ $loop->index }}" 
                                                {{ in_array($area, $profile->available_areas ?? []) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="area_{{ $loop->index }}">{{ $area }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

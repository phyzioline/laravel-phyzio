@extends('web.layouts.dashboard_master')

@section('title', 'Add Doctor')
@section('header_title', 'Register New Doctor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Doctor Information</h5>
            </div>
            <div class="card-body">
                @if(!$clinic)
                    <div class="alert alert-warning mb-4">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>Warning:</strong> No clinic found. You need to have a clinic associated with your account to register doctors.
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('clinic.doctors.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="Dr. John" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Doe" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Primary Specialization <span class="text-danger">*</span></label>
                        <select name="specialization" class="form-control @error('specialization') is-invalid @enderror" required>
                            <option value="">{{ __('Select Specialization') }}</option>
                            <option value="Orthopedic" {{ old('specialization') == 'Orthopedic' ? 'selected' : '' }}>{{ __('Orthopedic') }}</option>
                            <option value="Sports Physiotherapy" {{ old('specialization') == 'Sports Physiotherapy' ? 'selected' : '' }}>{{ __('Sports Physiotherapy') }}</option>
                            <option value="Neurology" {{ old('specialization') == 'Neurology' ? 'selected' : '' }}>{{ __('Neurology') }}</option>
                            <option value="Pediatrics" {{ old('specialization') == 'Pediatrics' ? 'selected' : '' }}>{{ __('Pediatrics') }}</option>
                            <option value="Geriatric" {{ old('specialization') == 'Geriatric' ? 'selected' : '' }}>{{ __('Geriatric') }}</option>
                            <option value="Women's Health" {{ old('specialization') == 'Women\'s Health' ? 'selected' : '' }}>{{ __('Women\'s Health') }}</option>
                            <option value="Cardiorespiratory" {{ old('specialization') == 'Cardiorespiratory' ? 'selected' : '' }}>{{ __('Cardiorespiratory') }}</option>
                        </select>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @php
                        $clinicSpecialties = \App\Models\ClinicSpecialty::where('clinic_id', $clinic->id ?? 0)
                            ->where('is_active', true)
                            ->get();
                    @endphp
                    
                    @if($clinic && $clinicSpecialties->count() > 0)
                    <div class="form-group">
                        <label>{{ __('Assign to Services/Departments') }}</label>
                        <p class="text-muted small mb-2">{{ __('Select which services this doctor will be responsible for') }}</p>
                        <div class="row">
                            @foreach($clinicSpecialties as $clinicSpecialty)
                            @php
                                $specialtyName = \App\Models\ClinicSpecialty::SPECIALTIES[$clinicSpecialty->specialty] ?? ucfirst(str_replace('_', ' ', $clinicSpecialty->specialty));
                            @endphp
                            <div class="col-md-6 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="specialty_{{ $clinicSpecialty->specialty }}" 
                                           name="specialties[]" 
                                           value="{{ $clinicSpecialty->specialty }}"
                                           {{ in_array($clinicSpecialty->specialty, old('specialties', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="specialty_{{ $clinicSpecialty->specialty }}">
                                        {{ $specialtyName }}
                                        @if($clinicSpecialty->is_primary)
                                            <span class="badge badge-primary badge-sm">Primary</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">{{ __('You can also assign doctors to services later from the Services page.') }}</small>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i> 
                        {{ __('No services/departments configured yet. Add services first, then assign doctors to them.') }}
                    </div>
                    @endif
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="email@clinic.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+123456789" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Professional Bio</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="{{ __('Brief professional background...') }}">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password (Optional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank for default password">
                        <small class="text-muted">{{ __('Default password will be \'password\' if left blank. Doctor should change it on first login.') }}</small>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.doctors.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.dashboard_master')

@section('title', 'Register New Patient')
@section('header_title', 'New Patient')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-5">
                <h4 class="font-weight-bold mb-4" style="color: #00897b;">{{ __('Patient Registration Form') }}</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ __('Please fix the following errors:') }}</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <form action="{{ route('clinic.patients.store') }}" method="POST" id="patientForm" 
                      data-inline-validation="true" data-save-continue="true" data-show-progress="true">
                    @csrf
                    
                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Personal Information') }}</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" required placeholder="e.g. Ahmed" value="{{ old('first_name') }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" required placeholder="e.g. Ali" value="{{ old('last_name') }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" required placeholder="e.g. 01xxxxxxxxx" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. ahmed@example.com" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>{{ __('Date of Birth') }}</label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}">
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Gender') }}</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Address') }}</label>
                            <input type="text" name="address" class="form-control" placeholder="City, Street..." value="{{ old('address') }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Medical & Insurance') }}</h6>
                    <div class="form-group">
                        <label>{{ __('Medical History / Alert') }}</label>
                        <textarea name="medical_history" rows="3" class="form-control" placeholder="Diabetes, Hypertension, Previous Surgeries...">{{ old('medical_history') }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance Provider') }}</label>
                            <input type="text" name="insurance_provider" class="form-control" placeholder="e.g. MetLife" value="{{ old('insurance_provider') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance / Policy Number') }}</label>
                            <input type="text" name="insurance_number" class="form-control" placeholder="Policy #" value="{{ old('insurance_number') }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between flex-wrap">
                        <a href="{{ route('clinic.patients.index') }}" class="btn btn-light mb-2">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <div>
                            <button type="button" class="btn btn-outline-primary mr-2 mb-2 btn-save-continue" style="border-color: #00897b; color: #00897b;">
                                <i class="las la-save"></i> {{ __('Save & Add Another') }}
                            </button>
                            <button type="submit" class="btn btn-primary px-5 mb-2" id="submitBtn" style="background-color: #00897b; border-color: #00897b;">
                                <i class="las la-check"></i> {{ __('Register Patient') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .invalid-feedback-live {
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        display: block;
    }
    .form-progress {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .form-progress .progress {
        margin-bottom: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/clinic-form-enhancements.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('patientForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Validate before submit
            const isValid = form.checkValidity();
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                // Focus first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            // Don't prevent default - let form submit normally
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> {{ __('Registering...') }}';
        });
    }
});
</script>
@endpush
@endsection

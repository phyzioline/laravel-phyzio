@extends('web.layouts.dashboard_master')

@section('title', 'Edit Patient - ' . $patient->full_name)
@section('header_title', 'Edit Patient')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="font-weight-bold mb-0" style="color: #00897b;">
                        {{ __('Edit Patient Information') }}
                    </h4>
                    <span class="badge badge-pill badge-light p-2 px-3">
                        <i class="las la-id-card"></i> ID: #{{ $patient->id }}
                    </span>
                </div>
                
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

                <form action="{{ route('clinic.patients.update', $patient->id) }}" method="POST" id="patientForm">
                    @csrf
                    @method('PUT')
                    
                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Personal Information') }}</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" required placeholder="e.g. Ahmed" value="{{ old('first_name', $patient->first_name) }}">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" required placeholder="e.g. Ali" value="{{ old('last_name', $patient->last_name) }}">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" required placeholder="e.g. 01xxxxxxxxx" value="{{ old('phone', $patient->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. ahmed@example.com" value="{{ old('email', $patient->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>{{ __('Date of Birth') }}</label>
                            <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $patient->date_of_birth ? ($patient->date_of_birth instanceof \Carbon\Carbon ? $patient->date_of_birth->format('Y-m-d') : substr($patient->date_of_birth, 0, 10)) : '') }}">
                            @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Gender') }}</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Address') }}</label>
                            <input type="text" name="address" class="form-control" placeholder="City, Street..." value="{{ old('address', $patient->address) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Medical & Insurance') }}</h6>
                    <div class="form-group">
                        <label>{{ __('Medical History / Alert') }}</label>
                        <textarea name="medical_history" rows="3" class="form-control" placeholder="Diabetes, Hypertension, Previous Surgeries...">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance Provider') }}</label>
                            <input type="text" name="insurance_provider" class="form-control" placeholder="e.g. MetLife" value="{{ old('insurance_provider', $patient->insurance_provider) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance / Policy Number') }}</label>
                            <input type="text" name="insurance_number" class="form-control" placeholder="Policy #" value="{{ old('insurance_number', $patient->insurance_number) }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between flex-wrap">
                        <a href="{{ route('clinic.patients.show', $patient->id) }}" class="btn btn-light mb-2">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5 mb-2" id="submitBtn" style="background-color: #00897b; border-color: #00897b;">
                            <i class="las la-save"></i> {{ __('Update Patient Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('patientForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                return false;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> {{ __('Updating...') }}';
        });
    }
});
</script>
@endpush

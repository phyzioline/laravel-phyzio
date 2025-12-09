@extends('web.layouts.dashboard_master')

@section('title', 'Register New Patient')
@section('header_title', 'New Patient')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-5">
                <h4 class="font-weight-bold mb-4" style="color: #00897b;">{{ __('Patient Registration Form') }}</h4>
                
                <form action="{{ route('clinic.patients.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Personal Information') }}</h6>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required placeholder="e.g. Ahmed">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required placeholder="e.g. Ali">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required placeholder="e.g. 01xxxxxxxxx">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Email Address') }}</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. ahmed@example.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>{{ __('Date of Birth') }}</label>
                            <input type="date" name="dob" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Gender') }}</label>
                            <select name="gender" class="form-control">
                                <option value="male">{{ __('Male') }}</option>
                                <option value="female">{{ __('Female') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>{{ __('Address') }}</label>
                            <input type="text" name="address" class="form-control" placeholder="City, Street...">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">{{ __('Medical & Insurance') }}</h6>
                    <div class="form-group">
                        <label>{{ __('Medical History / Alert') }}</label>
                        <textarea name="medical_history" rows="3" class="form-control" placeholder="Diabetes, Hypertension, Previous Surgeries..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance Provider') }}</label>
                            <input type="text" name="insurance_provider" class="form-control" placeholder="e.g. MetLife">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Insurance / Policy Number') }}</label>
                            <input type="text" name="insurance_number" class="form-control" placeholder="Policy #">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('clinic.patients.index') }}" class="btn btn-light mr-3">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-5" style="background-color: #00897b; border-color: #00897b;">
                            {{ __('Register Patient') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

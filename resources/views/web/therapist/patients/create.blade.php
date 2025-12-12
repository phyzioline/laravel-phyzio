@extends('therapist.layouts.app')

@section('title', 'Add New Patient')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Add New Patient') }}</h1>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Patient Information') }}</h6>
    </div>
    <div class="card-body">
        <form>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstName">{{ __('First Name') }}</label>
                    <input type="text" class="form-control" id="firstName" placeholder="John">
                </div>
                <div class="form-group col-md-6">
                    <label for="lastName">{{ __('Last Name') }}</label>
                    <input type="text" class="form-control" id="lastName" placeholder="Doe">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">{{ __('Email Address') }}</label>
                    <input type="email" class="form-control" id="email" placeholder="john@example.com">
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">{{ __('Phone Number') }}</label>
                    <input type="text" class="form-control" id="phone" placeholder="+1234567890">
                </div>
            </div>
             <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="dob">{{ __('Date of Birth') }}</label>
                    <input type="date" class="form-control" id="dob">
                </div>
                 <div class="form-group col-md-4">
                    <label for="gender">{{ __('Gender') }}</label>
                    <select id="gender" class="form-control">
                        <option selected>Choose...</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">{{ __('Status') }}</label>
                     <select id="status" class="form-control">
                        <option selected>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="medicalHistory">{{ __('Medical History / Notes') }}</label>
                <textarea class="form-control" id="medicalHistory" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Create Patient') }}</button>
            <a href="{{ route('therapist.patients.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        </form>
    </div>
</div>
@endsection

@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="mb-0 text-center text-primary font-weight-bold">{{ __('Therapist Profile Setup - Step 1/6') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('therapist.onboarding.step1.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <h5 class="mb-4 text-muted border-bottom pb-2">{{ __('1.1 Personal Information') }}</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Full Name') }}</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>
                             <div class="form-group col-md-6">
                                <label>{{ __('National ID Number') }}</label>
                                <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $therapist->national_id) }}">
                            </div>
                        </div>
                        <div class="form-row">
                             <div class="form-group col-md-4">
                                <label>{{ __('Date of Birth') }}</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $therapist->date_of_birth) }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>{{ __('Gender') }}</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male" {{ (old('gender', $therapist->gender) == 'male') ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ (old('gender', $therapist->gender) == 'female') ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>{{ __('Profile Photo') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="profile_photo" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('1.2 Professional Details') }}</h5>
                         <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Specialty') }}</label>
                                <select name="specialty" class="form-control" required>
                                    <option value="">{{ __('Select Specialty') }}</option>
                                    <option value="Orthopedic" {{ (old('specialty', $therapist->specialization) == 'Orthopedic') ? 'selected' : '' }}>{{ __('Orthopedic') }}</option>
                                    <option value="Neurology" {{ (old('specialty', $therapist->specialization) == 'Neurology') ? 'selected' : '' }}>{{ __('Neurology') }}</option>
                                    <option value="Pediatrics" {{ (old('specialty', $therapist->specialization) == 'Pediatrics') ? 'selected' : '' }}>{{ __('Pediatrics') }}</option>
                                    <option value="Sports" {{ (old('specialty', $therapist->specialization) == 'Sports') ? 'selected' : '' }}>{{ __('Sports') }}</option>
                                    <option value="Geriatrics" {{ (old('specialty', $therapist->specialization) == 'Geriatrics') ? 'selected' : '' }}>{{ __('Geriatrics') }}</option>
                                    <option value="Cardiopulmonary" {{ (old('specialty', $therapist->specialization) == 'Cardiopulmonary') ? 'selected' : '' }}>{{ __('Cardiopulmonary') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Professional Level') }}</label>
                                <select name="professional_level" class="form-control" required>
                                    <option value="">{{ __('Select Level') }}</option>
                                    <option value="Senior" {{ (old('professional_level', $therapist->professional_level) == 'Senior') ? 'selected' : '' }}>{{ __('Senior') }}</option>
                                    <option value="Junior" {{ (old('professional_level', $therapist->professional_level) == 'Junior') ? 'selected' : '' }}>{{ __('Junior') }}</option>
                                    <option value="Assistant" {{ (old('professional_level', $therapist->professional_level) == 'Assistant') ? 'selected' : '' }}>{{ __('Assistant') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('License Number') }}</label>
                                <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $therapist->license_number) }}" required>
                            </div>
                             <div class="form-group col-md-6">
                                <label>{{ __('Issuing Authority') }}</label>
                                <input type="text" name="license_authority" class="form-control" value="{{ old('license_authority', $therapist->license_issuing_authority) }}">
                            </div>
                        </div>
                         <div class="form-group">
                             <label>{{ __('Years of Experience') }}</label>
                             <input type="number" name="years_experience" class="form-control" value="{{ old('years_experience', $therapist->years_experience) }}">
                         </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

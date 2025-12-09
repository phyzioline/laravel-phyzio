@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="mb-0 text-center text-primary font-weight-bold">{{ __('Therapist Activity Settings - Step 2/6') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('therapist.onboarding.step2.post') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-4 text-muted border-bottom pb-2">{{ __('2.1 Services Offered') }}</h5>
                        <p class="text-sm text-muted mb-3">{{ __('Select services you offer and set your default duration/price.') }}</p>
                        
                        @php
                            $services = ['Evaluation', 'Manual Therapy', 'Exercise Therapy', 'Electrotherapy', 'Neurological Rehab', 'Pediatric Therapy', 'Sports Rehabilitation'];
                        @endphp

                        @foreach($services as $service)
                        <div class="form-row align-items-center mb-2">
                             <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="service_{{ Str::slug($service) }}" name="services[{{ $loop->index }}][enabled]" value="1">
                                    <label class="custom-control-label font-weight-bold" for="service_{{ Str::slug($service) }}">{{ $service }}</label>
                                    <input type="hidden" name="services[{{ $loop->index }}][name]" value="{{ $service }}">
                                </div>
                             </div>
                             <div class="col-md-4">
                                 <input type="number" class="form-control form-control-sm" placeholder="Duration (mins)" name="services[{{ $loop->index }}][duration]">
                             </div>
                             <div class="col-md-4">
                                 <input type="number" class="form-control form-control-sm" placeholder="Price (EGP)" name="services[{{ $loop->index }}][price]">
                             </div>
                        </div>
                        @endforeach
                        
                        <div class="form-group mt-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="home_visit" name="home_visit_enabled" value="yes">
                                <label class="custom-control-label font-weight-bold text-success" for="home_visit">{{ __('I offer Home Visits') }}</label>
                            </div>
                        </div>

                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('2.2 Alert & Notification Preferences') }}</h5>
                        <div class="form-group">
                            <div class="custom-control custom-switch mb-2">
                                <input type="checkbox" class="custom-control-input" id="notif_appts" name="notifications[appointments]" value="1" checked>
                                <label class="custom-control-label" for="notif_appts">{{ __('Appointment Reminders') }}</label>
                            </div>
                             <div class="custom-control custom-switch mb-2">
                                <input type="checkbox" class="custom-control-input" id="notif_new_patient" name="notifications[new_patient]" value="1" checked>
                                <label class="custom-control-label" for="notif_new_patient">{{ __('New Patient Assignment Alerts') }}</label>
                            </div>
                        </div>

                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('2.3 Profile Public Visibility') }}</h5>
                        <div class="form-group">
                            <label>{{ __('Short Biography') }}</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $therapist->bio) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Profile Visibility') }}</label>
                            <select name="visibility" class="form-control">
                                <option value="public">{{ __('Public (Visible to everyone)') }}</option>
                                <option value="assigned_only">{{ __('Private (Only assigned patients)') }}</option>
                                <option value="hidden">{{ __('Hidden (Temporarily)') }}</option>
                            </select>
                        </div>

                        <div class="text-right mt-4">
                            <a href="{{ route('therapist.onboarding.step1') }}" class="btn btn-secondary px-4 mr-2">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.dashboard_master')

@section('title', 'Add to Waitlist')
@section('header_title', 'Add Patient to Waitlist')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('clinic.waitlist.store') }}" method="POST">
            @csrf
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Patient Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ __('Patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" id="patient_id" class="form-control" required>
                            <option value="">{{ __('Select Patient') }}</option>
                            @foreach($patients ?? [] as $p)
                                <option value="{{ $p->id }}" {{ ($patient && $patient->id == $p->id) ? 'selected' : '' }}>
                                    {{ $p->first_name }} {{ $p->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Preferred Doctor') }}</label>
                                <select name="doctor_id" class="form-control">
                                    <option value="">{{ __('Any Available') }}</option>
                                    @foreach($doctors ?? [] as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Specialty') }}</label>
                                <select name="specialty" class="form-control">
                                    <option value="">{{ __('Any') }}</option>
                                    @foreach(\App\Models\ClinicSpecialty::getAvailableSpecialties() as $key => $label)
                                        <option value="{{ $key }}" {{ old('specialty') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Visit Type') }}</label>
                                <select name="visit_type" class="form-control">
                                    <option value="">{{ __('Any') }}</option>
                                    <option value="evaluation" {{ old('visit_type') == 'evaluation' ? 'selected' : '' }}>{{ __('Evaluation') }}</option>
                                    <option value="followup" {{ old('visit_type') == 'followup' ? 'selected' : '' }}>{{ __('Follow-up') }}</option>
                                    <option value="re_evaluation" {{ old('visit_type') == 're_evaluation' ? 'selected' : '' }}>{{ __('Re-evaluation') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Priority') }} <span class="text-danger">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                                </select>
                                @error('priority')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Preferred Dates & Times') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Preferred Start Date') }}</label>
                                <input type="date" name="preferred_start_date" class="form-control" value="{{ old('preferred_start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Preferred End Date') }}</label>
                                <input type="date" name="preferred_end_date" class="form-control" value="{{ old('preferred_end_date') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Preferred Days of Week') }}</label>
                        <div class="row">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preferred_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, old('preferred_days', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_{{ $day }}">
                                        {{ ucfirst($day) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Preferred Times') }}</label>
                        <div class="row">
                            @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preferred_times[]" value="{{ $time }}" id="time_{{ $time }}" {{ in_array($time, old('preferred_times', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="time_{{ $time }}">
                                        {{ $time }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="font-weight-bold mb-0">{{ __('Additional Notes') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="notes" class="form-control" rows="4" placeholder="{{ __('Any additional notes about this waitlist entry...') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save"></i> {{ __('Add to Waitlist') }}
                    </button>
                    <a href="{{ route('clinic.waitlist.index') }}" class="btn btn-outline-secondary">
                        <i class="las la-times"></i> {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection


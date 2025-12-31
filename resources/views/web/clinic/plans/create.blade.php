@extends('web.layouts.dashboard_master')

@section('title', 'Create Treatment Plan')
@section('header_title', 'New Treatment Plan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-5">
                <h4 class="font-weight-bold mb-4" style="color: #00897b;">{{ __('Design Treatment Plan') }}</h4>
                
                <form action="{{ route('clinic.plans.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>{{ __('Select Patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-control select2" required>
                            <option value="">{{ __('Choose a patient...') }}</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ isset($selectedPatientId) && $selectedPatientId == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->first_name }} {{ $patient->last_name }} (ID: {{ $patient->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Diagnosis / Primary Condition') }} <span class="text-danger">*</span></label>
                        <input type="text" name="diagnosis" class="form-control" placeholder="e.g. Rotator Cuff Tendonitis" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Frequency (Sessions/Week)') }}</label>
                            <input type="number" name="frequency" class="form-control" value="2" min="1">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Duration (Weeks)') }}</label>
                            <input type="number" name="duration" class="form-control" value="4" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Treatment Goals') }}</label>
                        <small class="form-text text-muted mb-2">{{ __('Enter each goal on a new line.') }}</small>
                        <textarea name="treatment_goals" rows="4" class="form-control" placeholder="- Reduce pain level to 2/10&#10;- Increase ROM by 20 degrees&#10;- Strengthen rotator cuff muscles"></textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ __('Modalities & Interventions') }}</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="mod_manual" name="modalities[]" value="Manual Therapy">
                                    <label class="custom-control-label" for="mod_manual">{{ __('Manual Therapy') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="mod_ex" name="modalities[]" value="Therapeutic Exercise">
                                    <label class="custom-control-label" for="mod_ex">{{ __('Therapeutic Exercise') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="mod_electro" name="modalities[]" value="Electrotherapy">
                                    <label class="custom-control-label" for="mod_electro">{{ __('Electrotherapy') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="mod_neuro" name="modalities[]" value="Neuromuscular Re-ed">
                                    <label class="custom-control-label" for="mod_neuro">{{ __('Neuromuscular Re-education') }}</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="mod_hydro" name="modalities[]" value="Hydrotherapy">
                                    <label class="custom-control-label" for="mod_hydro">{{ __('Hydrotherapy') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                         <a href="{{ route('clinic.patients.index') }}" class="btn btn-light mr-3">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-5" style="background-color: #00897b; border-color: #00897b;">
                            {{ __('Publish Plan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="mb-0 text-center text-primary font-weight-bold">{{ __('Patient Treatment Workflow - Step 3/6') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('therapist.onboarding.step3.post') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-4 text-muted border-bottom pb-2">{{ __('3.1 Evaluation Template Setup') }}</h5>
                        <p class="text-muted text-sm">{{ __('Check the sections you want to appear in your patient evaluation forms.') }}</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-dark">{{ __('General Evaluation') }}</h6>
                                @foreach(['Chief Complaint', 'Medical History', 'Surgical History', 'Imaging Upload', 'Red Flags Checklist', 'Pain Scale (0-10)', 'Functional Limitations'] as $item)
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input" id="eval_gen_{{ $loop->index }}" name="evaluation[general][]" value="{{ $item }}" checked>
                                        <label class="custom-control-label" for="eval_gen_{{ $loop->index }}">{{ __($item) }}</label>
                                    </div>
                                @endforeach
                            </div>
                             <div class="col-md-6">
                                <h6 class="font-weight-bold text-dark">{{ __('Physical Examination') }}</h6>
                                @foreach(['Range of Motion', 'Strength Testing', 'Posture Analysis', 'Special Tests', 'Neurological Screening', 'Gait Assessment'] as $item)
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input" id="eval_phys_{{ $loop->index }}" name="evaluation[physical][]" value="{{ $item }}" checked>
                                        <label class="custom-control-label" for="eval_phys_{{ $loop->index }}">{{ __($item) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('3.2 Treatment Plan Template') }}</h5>
                        <p class="text-muted text-sm">{{ __('Pre-define fields for your treatment plans.') }}</p>
                         <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="tp_goals" name="treatment_plan[]" value="Goals" checked disabled>
                            <label class="custom-control-label" for="tp_goals">{{ __('Short & Long Term Goals (Mandatory)') }}</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="tp_hep" name="treatment_plan[]" value="HEP" checked>
                            <label class="custom-control-label" for="tp_hep">{{ __('Home Exercise Program (HEP)') }}</label>
                        </div>
                        
                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('3.3 Session Note Template') }}</h5>
                         <div class="form-group">
                             <label>{{ __('Select fields for daily session notes:') }}</label>
                             <div class="d-flex flex-wrap">
                                 @foreach(['Subjective', 'Objective', 'Treatment Provided', 'Exercise Prescription', 'Manual Therapy', 'Electrotherapy', 'Pain Scale', 'Therapist Comments', 'Next Check-in'] as $field)
                                     <div class="custom-control custom-checkbox mr-4 mb-2">
                                        <input type="checkbox" class="custom-control-input" id="sn_{{ $loop->index }}" name="session_note[]" value="{{ $field }}" checked>
                                        <label class="custom-control-label" for="sn_{{ $loop->index }}">{{ __($field) }}</label>
                                    </div>
                                 @endforeach
                             </div>
                         </div>

                        <div class="text-right mt-4">
                            <a href="{{ route('therapist.onboarding.step2') }}" class="btn btn-secondary px-4 mr-2">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

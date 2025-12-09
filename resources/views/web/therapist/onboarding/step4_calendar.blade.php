@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="mb-0 text-center text-primary font-weight-bold">{{ __('Calendar & Appointment Rules - Step 4/6') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('therapist.onboarding.step4.post') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-4 text-muted border-bottom pb-2">{{ __('4.1 Calendar Settings') }}</h5>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>{{ __('Default View') }}</label>
                                <select name="calendar[view]" class="form-control">
                                    <option value="week">{{ __('Week View') }}</option>
                                    <option value="day">{{ __('Day View') }}</option>
                                    <option value="month">{{ __('Month View') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>{{ __('Allow Overlapping Sessions?') }}</label>
                                <select name="calendar[overlap]" class="form-control">
                                    <option value="no">{{ __('No') }}</option>
                                    <option value="yes">{{ __('Yes') }}</option>
                                </select>
                            </div>
                             <div class="form-group col-md-4">
                                <label>{{ __('Auto-Block Time Post-Eval') }}</label>
                                <select name="calendar[autoblock]" class="form-control">
                                    <option value="yes">{{ __('Yes (15 mins)') }}</option>
                                    <option value="no">{{ __('No') }}</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-4 text-muted border-bottom pb-2">{{ __('4.2 Appointment Limits') }}</h5>
                        <div class="form-row">
                             <div class="form-group col-md-6">
                                <label>{{ __('Max Sessions per Day') }}</label>
                                <input type="number" name="max_sessions" class="form-control" value="8">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Break Hours (Total)') }}</label>
                                <input type="number" name="break_hours" class="form-control" value="1">
                            </div>
                        </div>
                        
                         <h5 class="mt-4 mb-4 text-muted border-bottom pb-2">{{ __('4.3 Approval Rules') }}</h5>
                         <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="approve_auto" name="calendar[approval]" class="custom-control-input" value="auto" checked>
                            <label class="custom-control-label" for="approve_auto">{{ __('Auto-approve appointments') }}</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="approve_manual" name="calendar[approval]" class="custom-control-input" value="manual">
                            <label class="custom-control-label" for="approve_manual">{{ __('Require manual approval') }}</label>
                        </div>

                        <div class="text-right mt-4">
                            <a href="{{ route('therapist.onboarding.step3') }}" class="btn btn-secondary px-4 mr-2">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

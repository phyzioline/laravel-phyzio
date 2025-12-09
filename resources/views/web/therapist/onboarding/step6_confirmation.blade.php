@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow text-center py-5 px-4">
                <div class="card-body">
                    <div class="mb-4 text-success display-4">
                        <i class="las la-check-circle"></i>
                    </div>
                    <h2 class="font-weight-bold mb-3">{{ __('All Set!') }}</h2>
                    <p class="text-muted lead mb-4">
                        {{ __('Thank you for completing your profile. Please review your details before submitting for final approval. Once approved, you will have full access to your Dashboard.') }}
                    </p>
                    
                    <div class="list-group text-left mb-4 shadow-sm w-75 mx-auto">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="las la-user mr-2 text-primary"></i> {{ __('Profile Details') }}</span>
                            <a href="{{ route('therapist.onboarding.step1') }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="las la-briefcase mr-2 text-primary"></i> {{ __('Services & Activity') }}</span>
                            <a href="{{ route('therapist.onboarding.step2') }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="las la-clipboard-list mr-2 text-primary"></i> {{ __('Workflow Templates') }}</span>
                            <a href="{{ route('therapist.onboarding.step3') }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="las la-calendar-alt mr-2 text-primary"></i> {{ __('Schedule & Rules') }}</span>
                            <a href="{{ route('therapist.onboarding.step4') }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="las la-wallet mr-2 text-primary"></i> {{ __('Payments') }}</span>
                            <a href="{{ route('therapist.onboarding.step5') }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning w-75 mx-auto text-left">
                        <small><i class="las la-exclamation-triangle"></i> {{ __('Identity Verification: You may be asked to upload additional ID documents or perform a live selfie check upon your first login after approval.') }}</small>
                    </div>

                    <form action="{{ route('therapist.onboarding.submit') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow mt-3">{{ __('Submit Account for Approval') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

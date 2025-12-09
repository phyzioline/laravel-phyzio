@extends('web.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h4 class="mb-0 text-center text-primary font-weight-bold">{{ __('Payments & Earnings - Step 5/6') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('therapist.onboarding.step5.post') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-4 text-muted border-bottom pb-2">{{ __('5.1 Payment Method') }}</h5>
                        <div class="form-group">
                            <label>{{ __('Payment Frequency Preference') }}</label>
                            <select name="payment_frequency" class="form-control">
                                <option value="monthly">{{ __('Monthly') }}</option>
                                <option value="bi-weekly">{{ __('Bi-Weekly') }}</option>
                                <option value="weekly">{{ __('Weekly') }}</option>
                            </select>
                        </div>
                        
                        <div class="card bg-light p-3 mb-3">
                            <h6 class="font-weight-bold">{{ __('Option A: Bank Transfer') }}</h6>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Bank Name') }}</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="e.g. CIB, NBE">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ __('Account Holder Name') }}</label>
                                    <input type="text" name="account_holder_name" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>{{ __('Account Number / IBAN') }}</label>
                                    <input type="text" name="bank_account_number" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light p-3 mb-3">
                            <h6 class="font-weight-bold">{{ __('Option B: Mobile Wallet') }}</h6>
                            <div class="form-group">
                                <label>{{ __('Wallet Number') }}</label>
                                <input type="text" name="wallet_number" class="form-control" placeholder="01xxxxxxxxx">
                            </div>
                        </div>

                        <h5 class="mt-5 mb-4 text-muted border-bottom pb-2">{{ __('5.2 Revenue Rules Review') }}</h5>
                        <div class="alert alert-info">
                            <ul class="mb-0 pl-3">
                                <li>{{ __('Platform Fee: 15% per session') }}</li>
                                <li>{{ __('Cancellation Policy: No fee if cancelled 24h prior') }}</li>
                                <li>{{ __('Payouts are processed 5 days after the billing cycle ends.') }}</li>
                            </ul>
                        </div>
                        
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="terms_agree" required>
                            <label class="custom-control-label font-weight-bold" for="terms_agree">{{ __('I Agree to the Terms and Conditions') }}</label>
                        </div>

                        <div class="text-right mt-4">
                            <a href="{{ route('therapist.onboarding.step4') }}" class="btn btn-secondary px-4 mr-2">{{ __('Back') }}</a>
                            <button type="submit" class="btn btn-primary px-5">{{ __('Save & Continue') }} <i class="las la-arrow-right ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

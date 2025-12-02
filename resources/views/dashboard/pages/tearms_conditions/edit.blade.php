@extends('dashboard.layouts.app')
@section('title', __('Edit Terms & Conditions'))

@section('content')
<style>
    .cke_notification_warning, .cke_notification_info, .cke_notification_message {
        display: none !important;
    }
</style>
<main class="main-wrapper">
    <div class="main-content">
        <div class="row mb-5">
            <div class="col-12 col-xl-10 mx-auto">
                <form method="post" action="{{ route('dashboard.tearms_conditions.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="accordion" id="termsAccordion">

                        {{-- Product Usage --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingProductUsage">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProductUsage">
                                    {{ __('Product Usage') }}
                                </button>
                            </h2>
                            <div id="collapseProductUsage" class="accordion-collapse collapse show" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="product_usage_en" name="product_usage_en" class="form-control" rows="5">{{ old('product_usage_en', $tearms_conditions->product_usage_en ?? '') }}</textarea>
                                        @error('product_usage_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="product_usage_ar" name="product_usage_ar" class="form-control" rows="5">{{ old('product_usage_ar', $tearms_conditions->product_usage_ar ?? '') }}</textarea>
                                        @error('product_usage_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Account Security --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAccountSecurity">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccountSecurity">
                                    {{ __('Account Security') }}
                                </button>
                            </h2>
                            <div id="collapseAccountSecurity" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="account_security_en" name="account_security_en" class="form-control" rows="5">{{ old('account_security_en', $tearms_conditions->account_security_en ?? '') }}</textarea>
                                        @error('account_security_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="account_security_ar" name="account_security_ar" class="form-control" rows="5">{{ old('account_security_ar', $tearms_conditions->account_security_ar ?? '') }}</textarea>
                                        @error('account_security_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping & Delivery --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingShippingDelivery">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShippingDelivery">
                                    {{ __('Shipping & Delivery') }}
                                </button>
                            </h2>
                            <div id="collapseShippingDelivery" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="shipping_delivery_en" name="shipping_delivery_en" class="form-control" rows="5">{{ old('shipping_delivery_en', $tearms_conditions->shipping_delivery_en ?? '') }}</textarea>
                                        @error('shipping_delivery_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="shipping_delivery_ar" name="shipping_delivery_ar" class="form-control" rows="5">{{ old('shipping_delivery_ar', $tearms_conditions->shipping_delivery_ar ?? '') }}</textarea>
                                        @error('shipping_delivery_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Returns & Refund --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingReturnsRefund">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReturnsRefund">
                                    {{ __('Returns & Refund') }}
                                </button>
                            </h2>
                            <div id="collapseReturnsRefund" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="returns_refund_en" name="returns_refund_en" class="form-control" rows="5">{{ old('returns_refund_en', $tearms_conditions->returns_refund_en ?? '') }}</textarea>
                                        @error('returns_refund_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="returns_refund_ar" name="returns_refund_ar" class="form-control" rows="5">{{ old('returns_refund_ar', $tearms_conditions->returns_refund_ar ?? '') }}</textarea>
                                        @error('returns_refund_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Policy --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPaymentPolicy">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePaymentPolicy">
                                    {{ __('Payment Policy') }}
                                </button>
                            </h2>
                            <div id="collapsePaymentPolicy" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="payment_policy_en" name="payment_policy_en" class="form-control" rows="5">{{ old('payment_policy_en', $tearms_conditions->payment_policy_en ?? '') }}</textarea>
                                        @error('payment_policy_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="payment_policy_ar" name="payment_policy_ar" class="form-control" rows="5">{{ old('payment_policy_ar', $tearms_conditions->payment_policy_ar ?? '') }}</textarea>
                                        @error('payment_policy_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Legal Compliance --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLegalCompliance">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLegalCompliance">
                                    {{ __('Legal Compliance') }}
                                </button>
                            </h2>
                            <div id="collapseLegalCompliance" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="legal_compliance_en" name="legal_compliance_en" class="form-control" rows="5">{{ old('legal_compliance_en', $tearms_conditions->legal_compliance_en ?? '') }}</textarea>
                                        @error('legal_compliance_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="legal_compliance_ar" name="legal_compliance_ar" class="form-control" rows="5">{{ old('legal_compliance_ar', $tearms_conditions->legal_compliance_ar ?? '') }}</textarea>
                                        @error('legal_compliance_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact & Support --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingContactSupport">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContactSupport">
                                    {{ __('Contact & Support') }}
                                </button>
                            </h2>
                            <div id="collapseContactSupport" class="accordion-collapse collapse" data-bs-parent="#termsAccordion">
                                <div class="accordion-body row">
                                    <div class="col-md-6">
                                        <label>{{ __('English') }}</label>
                                        <textarea id="contact_support_en" name="contact_support_en" class="form-control" rows="5">{{ old('contact_support_en', $tearms_conditions->contact_support_en ?? '') }}</textarea>
                                        @error('contact_support_en') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>{{ __('Arabic') }}</label>
                                        <textarea id="contact_support_ar" name="contact_support_ar" class="form-control" rows="5">{{ old('contact_support_ar', $tearms_conditions->contact_support_ar ?? '') }}</textarea>
                                        @error('contact_support_ar') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer mt-4">
                        <button class="btn btn-primary w-100">{{ __('Update Terms & Conditions') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

{{-- CKEditor --}}
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('product_usage_en');
    CKEDITOR.replace('product_usage_ar');
    CKEDITOR.replace('account_security_en');
    CKEDITOR.replace('account_security_ar');
    CKEDITOR.replace('shipping_delivery_en');
    CKEDITOR.replace('shipping_delivery_ar');
    CKEDITOR.replace('returns_refund_en');
    CKEDITOR.replace('returns_refund_ar');
    CKEDITOR.replace('payment_policy_en');
    CKEDITOR.replace('payment_policy_ar');
    CKEDITOR.replace('legal_compliance_en');
    CKEDITOR.replace('legal_compliance_ar');
    CKEDITOR.replace('contact_support_en');
    CKEDITOR.replace('contact_support_ar');
</script>
@endsection

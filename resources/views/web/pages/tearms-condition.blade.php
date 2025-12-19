@extends('web.layouts.app')
@section('title', __('Tearms Conditions'))
@section('content')
    <!-- main body - start
                  ================================================== -->
    <main>
        <!-- slider-section - start
                   ================================================== -->
        <section id="slider-section" class="slider-section clearfix">
            <div class="item d-flex align-items-center" data-background="{{ asset('web/assets/images/hero-bg.png') }}"
                style="height: 100vh">
                <div class="container">

                    <div class="d-flex flex-column align-items-center">
                        <h1 class="hero-text">Physicaltherapy Software Solutions</h1>

                    </div>
                    <div class="hero-size">
                        <h5 class="hero-praph">All Physical Therapist Needs is Our Mission
                            From PT to PT </h5>
                    </div>


                </div>


            </div>
        </section>
        <!-- slider-section - end
                   ================================================== -->
        <Section>
            <div class="container">
                <div class="terms-container">
                    <h1 class="terms-header mt-4">Terms & Conditions</h1>
                    <p>By accessing and purchasing from our medical store, you agree to the following terms:</p>

                    <ul class="terms-list">
                        <li class="terms-item"><i class="fas fa-check-circle"></i> <strong>Product Usage:</strong>
                            {{ strip_tags($tearms_condition->{'product_usage_' . app()->getLocale()} ?? '' ) }}</li>
                        <li class="terms-item"><i class="fas fa-user-shield"></i> <strong>Account Security:</strong>
                            {{ strip_tags($tearms_condition->{'account_security_' . app()->getLocale()} ?? '') }}</li>
                        <li class="terms-item"><i class="fas fa-undo"></i> <strong>Returns & Refunds:</strong>
                            {{ strip_tags($tearms_condition->{'returns_refund_' . app()->getLocale()} ?? '' ) }}</li>
                        <li class="terms-item"><i class="fas fa-credit-card"></i> <strong>Payment Policy:</strong>
                            {{ strip_tags($tearms_condition->{'payment_policy_' . app()->getLocale()} ?? '') }}</li>
                        <li class="terms-item"><i class="fas fa-gavel"></i> <strong>Legal Compliance:</strong>
                            {{ strip_tags($tearms_condition->{'legal_compliance_' . app()->getLocale()} ?? '') }}</li>
                        <li class="terms-item"><i class="fas fa-envelope"></i> <strong>Contact Support:</strong> Have
                            questions? <a href="contact.html" class="text-primary">
                                {{ strip_tags($tearms_condition->{'contact_support_' . app()->getLocale()} ?? '') }}</a>.</li>
                    </ul>

                </div>
            </div>
        </Section>
    </main>
    <!-- main body - end

                        ================================================== -->
@endsection

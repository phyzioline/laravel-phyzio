@extends('web.layouts.app')

@section('title', 'Contact Us - PhyzioLine')

@push('css')
<style>
    .contact-hero {
        padding: 80px 0;
        background: #f8f9fa;
        margin-top: 130px; /* Fix header overlap */
    }
    .contact-form-wrapper {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    .text-teal {
        color: #02767F !important;
    }
    /* ... existing styles ... */
</style>
@endpush

@section('content')
@php
    $setting = \App\Models\Setting::first();
@endphp
<main>
    <section class="contact-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="pe-lg-5">
                        <span class="fw-bold text-uppercase mb-2 d-block text-teal">Get in Touch</span>
                        <h1 class="mb-4 display-5 fw-bold" style="color: #333;">Let's Chat</h1>
                        <p class="mb-5" style="color: #555;">Have questions about our services? Need technical support? Our team is ready to assist you.</p>

                        <div class="contact-info-list">
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-map-marker text-teal" style="font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-teal">Address</h5>
                                    <p class="mb-0" style="color: #333;">
                                        {{ $setting->{'address_' . app()->getLocale()} ?? '123 Healthcare Avenue, Medical City, MC 12345' }}
                                    </p>
                                </div>
                            </div>
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-phone text-teal" style="font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-teal">Phone</h5>
                                    <p class="mb-0" style="color: #333;">
                                        {{ $setting->phone ?? '+1 (555) 123-4567' }}
                                    </p>
                                    <small class="text-muted">Mon-Fri 9AM-6PM EST</small>
                                </div>
                            </div>
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-envelope text-teal" style="font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-teal">Email</h5>
                                    <p class="mb-0" style="color: #333;">{{ $setting->email ?? 'phyzioline@gmail.com' }}</p>
                                    <small class="text-muted">We'll respond within 24 hours</small>
                                </div>
                            </div>
                        </div>

                        <div class="social-links mt-5">
                            <h6 class="fw-bold mb-3">Follow Us</h6>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="lab la-facebook-f"></i></a>
                                <a href="#" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="lab la-twitter"></i></a>
                                <a href="#" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="lab la-instagram"></i></a>
                                <a href="#" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="lab la-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-form-wrapper">
                        <h3 class="mb-4 fw-bold">Send us a Message</h3>
                        <form id="contactForm" action="{{ route('feedback.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Subject *</label>
                                    <select name="subject" class="form-select form-control" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Success Support">Support</option>
                                        <option value="Partnership">Partnership</option>
                                        <option value="Feedback">Feedback</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea name="message" class="form-control" rows="5" placeholder="Please describe your inquiry in detail..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-submit w-100">
                                        <i class="las la-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            var originalBtnText = btn.html();

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                beforeSend: function() {
                    btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i> Sending...');
                },
                success: function(response) {
                    toastr.success(response.message);
                    form[0].reset();
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Something went wrong. Please try again.';
                    if(errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    toastr.error(errorMessage);
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalBtnText);
                }
            });
        });
    });
</script>
@endpush

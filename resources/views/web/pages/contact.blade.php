@extends('web.layouts.app')

@section('title', 'Contact Us - PhyzioLine')

@push('css')
<style>
    .contact-hero {
        padding: 80px 0;
        background: #f8f9fa;
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
    .form-control {
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #02767F;
        box-shadow: 0 0 0 3px rgba(2, 118, 127, 0.1);
    }
    .btn-submit {
        background: #02767F;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        background: #04b8c4;
        transform: translateY(-2px);
    }
    .contact-info-item {
        margin-bottom: 30px;
    }
    .contact-info-item i {
        color: #02767F;
        font-size: 24px;
        margin-bottom: 15px;
    }
    .contact-info-item h4 {
        font-weight: 600;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<main>
    <section class="contact-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <div class="pe-lg-5">
                        <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="color: #02767F !important;">Get in Touch</span>
                        <h1 class="mb-4 display-5 fw-bold">Let's Chat</h1>
                        <p class="text-muted mb-5">Have questions about our services? Need technical support? Our team is ready to assist you.</p>

                        <div class="contact-info-list">
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-map-marker" style="color: #02767F; font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Address</h5>
                                    <p class="text-muted mb-0">123 Healthcare Avenue, Medical City, MC 12345</p>
                                </div>
                            </div>
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-phone" style="color: #02767F; font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Phone</h5>
                                    <p class="text-muted mb-0">+1 (555) 123-4567</p>
                                    <small class="text-muted">Mon-Fri 9AM-6PM EST</small>
                                </div>
                            </div>
                            <div class="contact-info-item d-flex mb-4">
                                <div class="icon-box me-3">
                                    <i class="las la-envelope" style="color: #02767F; font-size: 28px;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Email</h5>
                                    <p class="text-muted mb-0">phyzioline@gmail.com</p>
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

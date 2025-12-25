<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Company Registration</title>
    <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- International Tel Input -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section>
        <div class="container">
            <div class="row full-screen align-items-center">
                <div class="left">
                    <img src="{{ asset('web/assets/images/LOGO PHYSIOLINE SVG 1 (2).svg') }}" width="100%" alt="">
                </div>
                <div class="right">
                    <div class="form">
                        <div class="text-center">
                            <h6><span>Company Registration</span></h6>
                            <div class="card-3d-wrap singup" id="formWrapper" style="height: fit-content; min-height: 900px;">
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <h4 class="heading">Register as Company</h4>
                                            <form method="POST" action="{{ route('company.register.store.' . app()->getLocale()) }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="type" value="company">

                                                <!-- Company Name -->
                                                <div class="form-group">
                                                    <input type="text" name="name" class="form-style" placeholder="Company Name" value="{{ old('name') }}" autocomplete="off" required>
                                                    <i class="input-icon material-icons">business</i>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Business Email -->
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-style" placeholder="Business Email" value="{{ old('email') }}" autocomplete="off" required>
                                                    <i class="input-icon material-icons">alternate_email</i>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Phone Number -->
                                                <div class="form-group">
                                                    <input type="tel" name="phone" id="phone" class="form-style" placeholder="Phone Number" value="{{ old('phone') }}" autocomplete="off" required>
                                                    <i class="input-icon material-icons">phone</i>
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Password -->
                                                <div class="form-group">
                                                    <input type="password" name="password" class="form-style" placeholder="Password" autocomplete="off" required>
                                                    <i class="input-icon material-icons">lock</i>
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Confirm Password -->
                                                <div class="form-group">
                                                    <input type="password" name="password_confirmation" class="form-style" placeholder="Confirm Password" autocomplete="off" required>
                                                    <i class="input-icon material-icons">lock_outline</i>
                                                </div>

                                                <!-- Business Documents Section -->
                                                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                                                    <h6 style="color: #02767F; margin-bottom: 15px; font-size: 14px;">
                                                        <i class="fas fa-file-alt me-2"></i>Business Documents (Optional)
                                                    </h6>

                                                    <!-- Commercial Register -->
                                                    <div class="form-group" style="margin-bottom: 15px;">
                                                        <label style="display: block; color: #666; font-size: 12px; margin-bottom: 5px;">
                                                            Commercial Register
                                                        </label>
                                                        <input type="file" name="commercial_register" class="form-style" accept=".pdf,.jpg,.jpeg,.png">
                                                        <i class="input-icon material-icons">description</i>
                                                        @error('commercial_register')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <!-- Tax Card -->
                                                    <div class="form-group" style="margin-bottom: 15px;">
                                                        <label style="display: block; color: #666; font-size: 12px; margin-bottom: 5px;">
                                                            Tax Card
                                                        </label>
                                                        <input type="file" name="tax_card" class="form-style" accept=".pdf,.jpg,.jpeg,.png">
                                                        <i class="input-icon material-icons">receipt</i>
                                                        @error('tax_card')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn">Register Company</button>
                                            </form>

                                            <!-- Google Login Button -->
                                            <a class="btn btn-info" href="{{ route('auth.social.redirect', 'google') }}" style="margin-top: 15px; display: block; text-align: center; background: #4285f4; color: white; padding: 12px; border-radius: 5px; text-decoration: none;">
                                                <i class="fab fa-google me-2"></i>Sign Up with Google
                                            </a>

                                            <p class="text-center" style="margin-top: 20px;">
                                                <a href="{{ route('view_login.' . app()->getLocale()) }}" class="link">Already have an account? Log In</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Toastr & Logic -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- International Tel Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    <script>
        let iti; // International Tel Input instance

        document.addEventListener('DOMContentLoaded', function() {
            initializePhoneInput(); // Initialize phone input with flags
        });

        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };

            @if(Session::has('message'))
                var type = "{{ Session::get('message')['type'] }}";
                var text = "{!! Session::get('message')['text'] !!}";
                toastr[type](text);
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });

        function initializePhoneInput() {
            const phoneInput = document.querySelector("#phone");
            if (phoneInput) {
                iti = intlTelInput(phoneInput, {
                    initialCountry: "eg",
                    preferredCountries: ["eg", "sa", "ae"],
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                });

                // Update hidden input on change
                phoneInput.addEventListener('change', function() {
                    if (iti.isValidNumber()) {
                        phoneInput.value = iti.getNumber();
                    }
                });
            }
        }
    </script>
</body>

</html>

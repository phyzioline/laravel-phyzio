<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
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
                    <h6><span>Sign Up</span></h6>
                    <div
                        class="card-3d-wrap singup"
                        id="formWrapper"
                        style="height: fit-content; min-height: {{ old('type') == 'vendor' ? '1200px' : '800px' }};"
                    >
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <h4 class="heading">Sign Up</h4>
                                    <form method="post" action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf

                                        <!-- User Type -->
                                        <div class="form-group">
                                            <select name="type" class="form-style" id="userType" required onchange="handleUserTypeChange()">
                                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select User Type</option>
                                                <option value="vendor" {{ old('type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                                <option value="therapist" {{ old('type') == 'therapist' ? 'selected' : '' }}>Therapist</option>
                                                <option value="buyer" {{ old('type') == 'buyer' ? 'selected' : '' }}>Buyer / Patient</option>
                                                <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company (Clinic / Recruitment)</option>
                                            </select>
                                            <i class="input-icon material-icons">account_circle</i>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Common Fields -->
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-style" placeholder="Your Name" value="{{ old('name') }}" autocomplete="off">
                                            <i class="input-icon material-icons">perm_identity</i>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="email" name="email" class="form-style" placeholder="Your Email" value="{{ old('email') }}" autocomplete="off">
                                            <i class="input-icon material-icons">alternate_email</i>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Country Selector -->
                                        <div class="form-group">
                                            <select name="country" id="country" class="form-style" required onchange="handleCountryChange()">
                                                <option value="" disabled selected>Select Country</option>
                                                <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>Egypt</option>
                                                <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>Saudi Arabia</option>
                                                <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>UAE</option>
                                                <option value="JO" {{ old('country') == 'JO' ? 'selected' : '' }}>Jordan</option>
                                                <option value="KW" {{ old('country') == 'KW' ? 'selected' : '' }}>Kuwait</option>
                                                <option value="QA" {{ old('country') == 'QA' ? 'selected' : '' }}>Qatar</option>
                                                <option value="BH" {{ old('country') == 'BH' ? 'selected' : '' }}>Bahrain</option>
                                                <option value="OM" {{ old('country') == 'OM' ? 'selected' : '' }}>Oman</option>
                                                <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>Lebanon</option>
                                                <option value="IQ" {{ old('country') == 'IQ' ? 'selected' : '' }}>Iraq</option>
                                            </select>
                                            <i class="input-icon material-icons">public</i>
                                            @error('country')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Phone with Country Code & Flag -->
                                        <div class="form-group" style="position: relative;">
                                            <input type="tel" name="phone" id="phone" class="form-style" placeholder="Your phone" value="{{ old('phone') }}" autocomplete="off" required>
                                            <i class="input-icon material-icons">phone</i>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="password" name="password" class="form-style" placeholder="Your Password" autocomplete="off">
                                            <i class="input-icon material-icons">lock</i>
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="password" name="password_confirmation" class="form-style" placeholder="Confirm Password" autocomplete="off">
                                            <i class="input-icon material-icons">lock</i>
                                            @error('password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Vendor Only Fields -->
                                        <div id="vendorOnlyFields" style="display:none;">
                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">Profile Image</label>
                                                <input type="file" name="image" class="form-style">
                                                <i class="input-icon material-icons">image</i>
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">Account Statement</label>
                                                <input type="file" name="account_statement" class="form-style">
                                                <i class="input-icon material-icons">description</i>
                                                @error('account_statement')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            
                                             <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">ID Card Image</label>
                                                <input type="file" name="card_image" class="form-style">
                                                <i class="input-icon material-icons">credit_card</i>
                                                @error('card_image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Business Docs (Vendor + Company) -->
                                        <div id="businessDocsFields" style="display:none;">
                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">Commercial Register</label>
                                                <input type="file" name="commercial_register" class="form-style">
                                                <i class="input-icon material-icons">business</i>
                                                @error('commercial_register')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">Tax Card</label>
                                                <input type="file" name="tax_card" class="form-style">
                                                <i class="input-icon material-icons">assignment</i>
                                                @error('tax_card')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Therapist Fields Only -->
                                        <div id="therapistFields" style="{{ old('type') == 'therapist' ? '' : 'display:none;' }}">
                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">Professional License Document</label>
                                                <input type="file" name="license_document" class="form-style">
                                                <i class="input-icon material-icons">verified_user</i>
                                                @error('license_document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label style="margin-bottom: 10px; display: block">National ID Document</label>
                                                <input type="file" name="id_document" class="form-style">
                                                <i class="input-icon material-icons">badge</i>
                                                @error('id_document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn">Sign Up</button>
                                    </form>

                                    <a class="btn btn-info" href="{{ route('auth.social.redirect','google') }}">Login with Google</a>

                                    <p class="text-center">
                                        <a href="{{ route('view_login') }}" class="link">Already have an account? Log In</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- .card-3d-wrap -->
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Toastr & Logic -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- International Tel Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    
    <script>
        let iti; // International Tel Input instance

        document.addEventListener('DOMContentLoaded', function() {
            handleUserTypeChange(); // Ensure correct fields show on load
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
            iti = window.intlTelInput(phoneInput, {
                initialCountry: "eg",
                preferredCountries: ["eg", "sa", "ae", "jo", "kw"],
                separateDialCode: true,
                nationalMode: true,
                autoPlaceholder: "polite",
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    return "e.g. " + selectedCountryPlaceholder;
                },
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"
            });

            // Sync country dropdown with phone country
            const countrySelect = document.getElementById('country');
            if (countrySelect.value) {
                iti.setCountry(countrySelect.value.toLowerCase());
            }
        }

        function handleCountryChange() {
            const countrySelect = document.getElementById('country');
            const selectedCountry = countrySelect.value;
            
            // Update phone input country when country dropdown changes
            if (iti && selectedCountry) {
                iti.setCountry(selectedCountry.toLowerCase());
            }
        }

        function handleUserTypeChange() {
            const userType = document.getElementById('userType').value;
            
            const vendorOnlyFields = document.getElementById('vendorOnlyFields');
            const businessDocsFields = document.getElementById('businessDocsFields');
            const therapistFields = document.getElementById('therapistFields');
            
            const formWrapper = document.getElementById('formWrapper');

            // Hide all first
            vendorOnlyFields.style.display = 'none';
            businessDocsFields.style.display = 'none';
            therapistFields.style.display = 'none';
            
            formWrapper.style.minHeight = '850px'; 

            if (userType === 'vendor') {
                vendorOnlyFields.style.display = 'block';
                businessDocsFields.style.display = 'block';
                formWrapper.style.minHeight = '1450px';
            } else if (userType === 'company') {
                businessDocsFields.style.display = 'block';
                formWrapper.style.minHeight = '1100px';
            } else if (userType === 'therapist') {
                therapistFields.style.display = 'block';
                formWrapper.style.minHeight = '950px';
            }
        }
    </script>
</body>
</html>

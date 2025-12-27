<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP</title>
  <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
  <style>
    .otp-inputs {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 20px 0;
    }

    .otp-inputs input {
      width: 50px;
      height: 50px;
      font-size: 24px;
      text-align: center;
      border-radius: 8px;
      border: 1px solid #ccc;
      outline: none;
    }

    .otp-inputs input:focus {
      border-color: #1d3972;
    }

    .btn {
      margin-top: 10px;
    }
  </style>
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
              <h6><span>OTP</span></h6>
              <div class="card-3d-wrap">
                <div class="card-3d-wrapper">
                  <div class="card-front">
                    <div class="center-wrap">
                      <h4 class="heading">Enter OTP</h4>
                       <form id="otp-form" method="POST" action="{{ route('verify.' . app()->getLocale()) }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        <input type="hidden" name="otp" id="otp">
                        <div class="otp-inputs">
                          <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
                          <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
                          <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
                          <input type="text" maxlength="1" pattern="\d*" inputmode="numeric">
                        </div>
                        <button class="w-100 my-4 btn-primary border-0 py-3" style="border-radius: 8px" type="submit">
                           {{__('Submit')}}
                        </button>
                      </form>
                      <p class="text-center">
                        <a href="{{ route('resend_otp.' . app()->getLocale()) }}" class="link" id="resend-link">
                          {{ __('Didn\'t receive the code?') }} {{ __('Resend') }}
                        </a>
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

  <script>
    const inputs = document.querySelectorAll('.otp-inputs input');
    const otpHiddenInput = document.getElementById('otp');

    inputs.forEach((input, index) => {
      input.addEventListener('input', () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }

        otpHiddenInput.value = Array.from(inputs).map(i => i.value).join('');
      });

      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && index > 0) {
          inputs[index - 1].focus();
        }
      });
    });

    document.getElementById('otp-form').addEventListener('submit', function() {
      otpHiddenInput.value = Array.from(inputs).map(i => i.value).join('');
    });
  </script>
</body>
</html>

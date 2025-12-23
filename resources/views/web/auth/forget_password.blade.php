<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
  <style>
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
              <h6><span>Forgot Password</span></h6>
              <div class="card-3d-wrap">
                <div class="card-3d-wrapper">
                  <div class="card-front">
                    <div class="center-wrap">
                      <h4 class="heading">Reset Your Password</h4>
                      <form action="{{ route('forget_password') }}" method="post">
                        @csrf
                        <div class="form-group">
                          <input type="email" name="email" class="form-style" placeholder="Your Email" autocomplete="off">
                          <i class="input-icon material-icons">alternate_email</i>
                        </div>
                        <button type="submit" class="btn">Send Reset Link</button>
                      </form>
                      <p class="text-center"><a href="{{ route('view_login.' . app()->getLocale()) }}" class="link">Back to Login</a></p>
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
</body>
</html>

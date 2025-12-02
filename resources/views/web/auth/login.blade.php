<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('web/assets/css/login.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
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
              <h6><span>Log In</span></h6>
              <div class="card-3d-wrap" style="min-height : 500px"> 
                <div class="card-3d-wrapper">
                  <div class="card-front">
                    <div class="center-wrap">
                      <h4 class="heading">Log In</h4>
                      <form method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                          <input type="email"  name="email" class="form-style" placeholder="Your Email" autocomplete="off">
                          <i class="input-icon material-icons">alternate_email</i>
                        </div>
                        <div class="form-group">
                          <input type="password" name="password" class="form-style" placeholder="Your Password" autocomplete="off">
                          <i class="input-icon material-icons">lock</i>
                        </div>
                        <button type="submit" class="btn">Login</button>
                      </form>
                      <p class="text-center">
                    <a class="btn btn-info" href="{{ route('auth.social.redirect','google') }}">Login with google</a>

                        <a href="{{ route('view_forget_password') }}" class="link">Forgot your password?</a><br>
                        <a href="{{ route('view_register') }}" class="link">Don't have an account? Sign Up</a>
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
</body>
</html>

<footer id="footer-section" class="footer-section clearfix" style="background-color: #02767F; color: white; padding-top: 60px; margin-top: 50px;">
  <div class="container">
    <div class="row">
      <!-- Brand & About -->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
        <div class="footer-widget">
          <div class="brand-logo mb-4">
            <a href="{{ '/' . app()->getLocale() }}" class="brand-link">
              <!-- Using white logo or filtering the existing one if needed. Assuming white text logo is preferred on dark bg -->
               @php
                    $setting = \App\Models\Setting::first();
                @endphp
              <img src="{{ asset('web/assets/images/Frame 131.svg') }}" 
                   alt="Phyzioline Logo"
                   width="150"
                   height="50"
                   loading="lazy"
                   style="max-height: 50px; filter: brightness(0) invert(1);" /> 
            </a>
          </div>
          <p class="mb-4" style="color: rgba(255,255,255,0.8);">
             {{ \Illuminate\Support\Str::limit($setting->{'description_' . app()->getLocale()} ?? 'Your complete medical platform for healthcare services, career opportunities, and medical products.', 150) }}
          </p>
        </div>
      </div>

      <!-- Services -->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
        <div class="footer-widget">
          <h4 class="widget-title mb-4" style="font-weight: 700; font-size: 18px; color: white;">{{ __('Services') }}</h4>
          <ul class="list-unstyled">
             <li class="mb-2"><a href="{{ route('web.home_visits.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Home Visits') }}</a></li>
             <li class="mb-2"><a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Find Jobs') }}</a></li>
             <li class="mb-2"><a href="{{ route('web.courses.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Courses') }}</a></li>
             <li class="mb-2"><a href="{{ route('show') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Products') }}</a></li>
          </ul>
        </div>
      </div>

      <!-- Support -->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
        <div class="footer-widget">
          <h4 class="widget-title mb-4" style="font-weight: 700; font-size: 18px; color: white;">{{ __('Support') }}</h4>
           <ul class="list-unstyled">
             <li class="mb-2"><a href="{{ route('help.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Help Center') }}</a></li>
             <li class="mb-2"><a href="{{ route('history_order.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Track Your Order') }}</a></li>
             <li class="mb-2"><a href="{{ route('feedback.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Contact Us') }}</a></li>
             <li class="mb-2"><a href="{{ route('shipping_policy.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Shipping Policy') }}</a></li>
              <li class="mb-2"><a href="{{ route('privacy_policy.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Privacy Policy') }}</a></li>
              <li class="mb-2"><a href="{{ route('tearms_condition.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Terms & Conditions') }}</a></li>
               <li class="mb-2"><a href="{{ route('tearms_condition.index.' . app()->getLocale()) }}#returns" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Returns & Refunds') }}</a></li>
          </ul>
        </div>
      </div>

      <!-- Account & Connect -->
      <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
        <div class="footer-widget">
           <h4 class="widget-title mb-4" style="font-weight: 700; font-size: 18px; color: white;">{{ __('Account') }}</h4>
            <ul class="list-unstyled mb-5">
                @if(Auth::check())
                 <li class="mb-2"><a href="{{ route('history_order.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('My Account') }}</a></li>
                 <li class="mb-2"><a href="{{ route('history_order.index.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Order History') }}</a></li>
                @else
                 <li class="mb-2"><a href="{{ route('view_login.' . app()->getLocale()) }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Login') }} / {{ __('Register') }}</a></li>
                @endif
                 <li class="mb-2"><a href="{{ route('show') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">{{ __('Shop') }}</a></li>
            </ul>

            <h5 class="widget-title mb-3" style="font-weight: 700; font-size: 16px; color: white;">{{ __('Connect With Us') }}</h5>
            <div class="social-links d-flex gap-3 mb-3">
                 <a href="https://www.facebook.com/share/g/1BHkoMrDjk" target="_blank" style="color: white; font-size: 20px;"><i class="lab la-facebook-f"></i></a>
                 <a href="{{ $setting->twitter ?? '#' }}" target="_blank" style="color: white; font-size: 20px;"><i class="lab la-twitter"></i></a>
                 <a href="{{ $setting->instagram ?? '#' }}" target="_blank" style="color: white; font-size: 20px;"><i class="lab la-instagram"></i></a>
                 <a href="{{ $setting->linkedin ?? '#' }}" target="_blank" style="color: white; font-size: 20px;"><i class="lab la-linkedin-in"></i></a>
            </div>
            
            <div class="email-contact d-flex align-items-center gap-2">
                <i class="las la-envelope" style="font-size: 18px;"></i>
                <a href="mailto:phyzioline@gmail.com" style="color: white; text-decoration: none;">phyzioline@gmail.com</a>
            </div>
        </div>
      </div>
    </div>

    <div class="row pt-4 pb-4 mt-4" style="border-top: 1px solid rgba(255,255,255,0.1);">
      <div class="col-md-6 text-center text-md-start">
        <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 14px;">&copy; {{ date('Y') }} Phyzioline. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-center text-md-end">
         <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 14px;">
             Made with <i class="las la-heart" style="color: #ff6b6b;"></i> for healthcare
         </p>
      </div>
    </div>
  </div>
</footer>

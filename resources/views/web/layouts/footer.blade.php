 <footer id="footer-section" class="footer-section pb-30 mt-5 clearfix">
    <div class="container">
      <div style="background-color: #F4F8FB; border-radius: 20px 0px 20px 0px;" class="widget-area clearfix p-5">
        <div class="row justify-content-lg-between justify-content-md-center justify-content-md-center">
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="about-content">
              <div class="brand-logo mb-4 clearfix">
                <a href="index.html" class="brand-link">
                    @php
                    $setting = \App\Models\Setting::first();
                    @endphp

                <img src="{{ optional($setting)->image ? asset($setting->image) : asset('web/assets/images/colored-logo.svg') }}" alt="logo_not_found" />

                </a>
              </div>
              <!--<p class="mb-30">-->
              <!--  {{-- Medicia is an online medical service and medicinewooCommerce-->
              <!--  theme for your ultimate online store and medical service. --}}-->
              <!--  {{ $setting->{'description_' . app()->getLocale()} ?? 'Medicia is an online medical service and medicinewooCommerce theme for your ultimate online store and medical service.' }}-->
              <!--</p>-->
        <p class="mb-30">
  {{ \Illuminate\Support\Str::limit($setting->{'description_' . app()->getLocale()} ?? 'Medicia is an online medical service and medicinewooCommerce theme for your ultimate online store and medical service.', 100) }}
  <a href="#" data-bs-toggle="modal" data-bs-target="#descriptionModal">Read more</a>
</p>


              <div class="contact-info ul-li-block clearfix">
               <ul class="clearfix">
  <li>
    <span><i class="las la-map-marked-alt"></i></span>
    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($setting->{'address_' . app()->getLocale()} ?? '16122 Collins Street West Victoria 8007 Australia') }}" target="_blank">
      {{ $setting->{'address_' . app()->getLocale()} ?? '16122 Collins Street West Victoria 8007 Australia' }}
    </a>
  </li>
  <li>
    <span><i class="las la-envelope-open-text"></i></span>
    <a href="mailto:{{ $setting->email ?? '' }}">
      {{ $setting->email ?? '' }}
    </a>
  </li>
 <li>
  <span><i class="las la-phone-volume"></i></span>
  <a href="https://wa.me/{{ preg_replace('/\D/', '', $setting->phone ?? '') }}" target="_blank">
    {{ $setting->phone ?? '' }}
  </a>
</li>

</ul>

              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="useful-links ul-li-block clearfix">
              <h3 class="widget-title" style="color: #36415A;">Phyzioline</h3>
              <ul class="clearfix">
                <li><a href="{{ route('show') }}">Shop</a></li>
                <li><a href="#privateCases">Private Cases</a></li>
                <li><a href="#System">Our System</a></li>
                <li><a href="#jobs">Jobs</a></li>
                <li><a href="#courses">Courses</a></li>
                <li><a href="#about">About Us</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="useful-links ul-li-block clearfix">
              <h3 class="widget-title" style="color: #36415A;">Quick Link</h3>
              <ul class="clearfix">
                <li><a href="#!">Support</a></li>
                <!--<li><a href="TearmsCondition.html">Tearms & Condition</a></li>-->
                <li><a href="{{ route('shipping_policy.index') }}">Shipping policies</a></li>
                <li><a href="{{ route('privacy_policy.index') }}">Privacy Policies</a></li>
                <li><a href="{{ route('tearms_condition.index') }}">Tearms & Condition</a></li>
                <!-- <li><a href="#!">Help Center</a></li>-->
              </ul>
            </div>
            <div class="social-icon-circle ul-li clearfix mt-5">
              <ul class="clearfix">
                <li>
                  <a href="https://www.facebook.com/share/g/1BHkoMrDjk/?mibextid=wwXIfr
                  "><i class="lab la-facebook-f"></i></a>
                </li>
                <li>
                  <a href="{{ $setting->twitter ?? '' }}"><i class="lab la-twitter"></i></a>
                </li>
                <li>
                  <a href="{{ $setting->instagram  ?? '' }}"><i class="lab la-instagram"></i></a>
                </li>
                <li>
  <a href="{{ $setting->linkedin ?? '' }}" target="_blank">
    <i class="lab la-linkedin"></i>
  </a>
</li>

              </ul>
            </div>
          </div>


        </div>
      </div>

      <div class="footer-bottom  text-center clearfix">
        <p class="mb-0">
          Designed and developed by <a href="https://brmja.tech/" target="_blank" style="color:#02767F;">Brmja Tech</a>
          . 2025
        </p>

      </div>
    </div>
  </footer>
<!-- Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="descriptionModalLabel">About Us</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{ $setting->{'description_' . app()->getLocale()} ?? 'Medicia is an online medical service and medicinewooCommerce theme for your ultimate online store and medical service.' }}
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

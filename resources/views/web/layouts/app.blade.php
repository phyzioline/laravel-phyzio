<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />

  @php
      $gSetting = \App\Models\Setting::first();
      $currentLocale = app()->getLocale();
      $isArabic = $currentLocale === 'ar';
      $metaTitle = $gSetting->{'website_title_' . $currentLocale} ?? ($isArabic 
          ? 'فيزيولاين - جميع احتياجات أخصائي العلاج الطبيعي من PT إلى PT'
          : 'Phyzioline - All Physical Therapist Needs From PT to PT');
      $metaDesc = \Illuminate\Support\Str::limit($gSetting->{'description_' . $currentLocale} ?? ($isArabic
          ? 'فيزيولاين: جميع احتياجات أخصائي العلاج الطبيعي هي مهمتنا من PT إلى PT. حلول برمجية شاملة، منتجات، زيارات منزلية، إدارة عيادات، دورات، وظائف، ومعلومات.'
          : 'Phyzioline: All Physical Therapist Needs is Our Mission From PT to PT. Comprehensive software solutions, products, home visits, clinic management, courses, jobs, and data.'), 160);
      $metaKeywords = ($isArabic 
          ? 'فيزيولاين, علاج طبيعي, حلول برمجية, منتجات طبية, إدارة عيادات, دورات, وظائف, زيارات منزلية, بنك المعلومات'
          : 'Phyzioline, Physical Therapy, PT Software Solutions, Medical Products, Clinic ERP, Courses, Jobs, Home Visits, Data Hub') . ', ' . ($gSetting->keywords ?? '');
      $metaImage = asset('web/assets/images/logo.png');
      $currentUrl = url()->current();
  @endphp

  <title>@yield('title', $metaTitle)</title>
  <meta name="description" content="@yield('description', $metaDesc)">
  <meta name="keywords" content="@yield('keywords', $metaKeywords)">
  <meta name="author" content="Phyzioline">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="googlebot" content="index, follow">
  <link rel="canonical" href="{{ $currentUrl }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ $currentUrl }}">
  <meta property="og:title" content="@yield('title', $metaTitle)">
  <meta property="og:description" content="@yield('description', $metaDesc)">
  <meta property="og:image" content="@yield('image', $metaImage)">
  <meta property="og:site_name" content="Phyzioline">
  <meta property="og:locale" content="{{ $currentLocale == 'ar' ? 'ar_AR' : 'en_US' }}">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ $currentUrl }}">
  <meta property="twitter:title" content="@yield('title', $metaTitle)">
  <meta property="twitter:description" content="@yield('description', $metaDesc)">
  <meta property="twitter:image" content="@yield('image', $metaImage)">

  <!-- Hreflang for SEO -->
  @foreach(Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
      <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
  @endforeach

  <!-- Organization Schema -->
  <script type="application/ld+json">
  @json(\App\Services\SEO\SEOService::organizationSchema())
  </script>

  <!-- Website Schema -->
  <script type="application/ld+json">
  @json(\App\Services\SEO\SEOService::websiteSchema())
  </script>

  @stack('structured-data')

  @stack('meta')
  
  <!-- Resource Hints for Performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://www.googletagmanager.com">
  <link rel="dns-prefetch" href="https://maps.googleapis.com">
  
  <link rel="icon" type="image/png" href="{{ asset('web/assets/images/logo.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('web/assets/images/logo.png') }}" />

  <!-- Critical CSS - Load synchronously for faster FCP/LCP -->
  <link rel="stylesheet" href="{{ asset('web/assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('web/assets/css/style.css')}}?v=1.0">
  
  <!-- Non-Critical CSS - Load asynchronously -->
  <link rel="preload" href="{{ asset('web/assets/css/line-awesome.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('web/assets/css/line-awesome.min.css')}}"></noscript>
  
  <link rel="preload" href="{{ asset('web/assets/css/owl.carousel.min.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('web/assets/css/owl.carousel.min.css')}}"></noscript>
  
  <link rel="preload" href="{{ asset('web/assets/css/magnific-popup.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('web/assets/css/magnific-popup.css')}}"></noscript>
  
  <link rel="preload" href="{{ asset('web/assets/css/jquery-ui.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('web/assets/css/jquery-ui.css')}}"></noscript>
  
  <!-- font - Optimized with font-display swap and preload -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400&display=swap" rel="stylesheet">
  
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
  
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></noscript>

  <!-- swiper -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"></noscript>
  
  <!-- Load CSS asynchronously script -->
  <script>
    /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
    (function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(){}}
    var loadCSS=function(href,before,media){var doc=w.document;var ss=doc.createElement("link");var ref;if(before){ref=before}else{var refs=(doc.body||doc.getElementsByTagName("head")[0]).childNodes;ref=refs[refs.length-1]}var sheets=doc.styleSheets;ss.rel="stylesheet";ss.href=href;ss.media="only x";function ready(cb){if(doc.body){return cb()}setTimeout(function(){ready(cb)})}ready(function(){ref.parentNode.insertBefore(ss,before?ref:ref.nextSibling)});var onloadcssdefined=function(cb){var resolvedHref=ss.href;var i=sheets.length;while(i--){if(sheets[i].href===resolvedHref){return cb()}}setTimeout(function(){onloadcssdefined(cb)})};ss.onloadcssdefined=onloadcssdefined;onloadcssdefined(function(){if(ss.media!=="all"){ss.media=media||"all"}});return ss};
    if(typeof exports!=="undefined"){exports.loadCSS=loadCSS}else{w.loadCSS=loadCSS}}(this));
  </script>

<style>
    .product-grid .btns-group > ul {
        margin-left : 143px !important;
    }
    .cart-dropdown{
    max-height: 400px !important;
    overflow: auto !important;
    }

    /* Fix mobile menu button — move it fully to the right */
   .mobile-menu-btn {
    margin-left: auto !important; /* pushes it right */
    margin-right: 10px !important;
    font-size: 28px !important;
    color: #02767F !important; /* Dark color for visibility on transparent header */
    position: relative;
    z-index: 99999; /* فوق الهيدر */
  }
   
   /* When header is stuck, use white color */
   .header-section.stuck .mobile-menu-btn {
       color: #fff !important;
   }

  /* Ensure mobile header layout is flex aligned properly */
    .brand-logo {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
  }

   /* Hide desktop menu on mobile */
   @media (max-width: 991px) {
    .main-menu {
        display: none !important;
     }
   } 

   /* Global Selected Text Color Fix - Override all white selection styles */
   *::selection,
   *::-moz-selection,
   body *::selection,
   body *::-moz-selection,
   html *::selection,
   html *::-moz-selection,
   .home-v1 *::selection,
   .home-v1 *::-moz-selection,
   .home-v1::selection,
   .home-v1::-moz-selection,
   section *::selection,
   section *::-moz-selection,
   div *::selection,
   div *::-moz-selection,
   p *::selection,
   p *::-moz-selection,
   h1 *::selection,
   h1 *::-moz-selection,
   h2 *::selection,
   h2 *::-moz-selection,
   h3 *::selection,
   h3 *::-moz-selection,
   h4 *::selection,
   h4 *::-moz-selection,
   h5 *::selection,
   h5 *::-moz-selection,
   h6 *::selection,
   h6 *::-moz-selection,
   span *::selection,
   span *::-moz-selection,
   a *::selection,
   a *::-moz-selection {
       color: #02767F !important;
       background: rgba(4, 184, 196, 0.3) !important;
   }
   
   /* Also target text directly (not just children) */
   ::selection,
   ::-moz-selection {
       color: #02767F !important;
       background: rgba(4, 184, 196, 0.3) !important;
   }

</style>
  <!-- RTL Overrides for Arabic -->
  @if(app()->getLocale() == 'ar')
  <link rel="preload" href="{{ asset('css/rtl-overrides.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/rtl-overrides.css') }}"></noscript>
  @endif

   <link rel="preload" href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
   <noscript><link rel="stylesheet" href="{{ asset('layout/plugins/toastr/toastr.min.css') }}"></noscript>
@stack('css')

  <!-- Google tag (gtag.js) - Load async to not block rendering -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-QJ455L4DV3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);} 
    gtag('js', new Date());
    gtag('config', 'G-QJ455L4DV3');
  </script>

</head>

<body class="home-v1">
  <!-- backtotop - start -->
  <div id="thetop"></div>
  <div id="backtotop">
    <a href="#" id="scroll">
      <i class="las la-arrow-up"></i>
    </a>
  </div>
  <!-- backtotop - end -->



  <!-- header-section - start
		================================================== -->
    @include('web.layouts.header')

  <!-- sidebar mobile menu - start -->
   @include('web.layouts.sidebar')
  <!-- sidebar mobile menu - end -->
  <!-- header-section - end
		================================================== -->

  <!-- main body - start
		================================================== -->
 @yield('content')
  <!-- main body - end
		================================================== -->

  <!-- footer-section - start
		================================================== -->
    @include('web.layouts.footer')
  <!-- footer-section - end
		================================================== -->


@stack('scripts')
  
  <!-- jquery include - Load first but defer -->
  <script src="{{ asset('web/assets/js/jquery-3.4.1.min.js')}}" defer></script>
  
  <!-- Load Swiper after jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js" defer></script>
  
  <script defer>
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof Swiper !== 'undefined') {
        var swiper = new Swiper(".mySwiper", {
          slidesPerView: 3,
          spaceBetween: 20,
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },
          autoplay: {
            delay: 3000,
            disableOnInteraction: false,
          },
          breakpoints: {
            992: { slidesPerView: 4 },
            768: { slidesPerView: 2 },
            576: { slidesPerView: 1 },
            425: { slidesPerView: 1 },
            200: { slidesPerView: 1 },
          }
        });
        var swiper2 = new Swiper('.swiper-container', {
          slidesPerView: 1,
          spaceBetween: 10,
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
          },
          autoplay: {
            delay: 3000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true
          }
        });
      }
      
      // Smooth scroll
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    });
  </script>
  
  <script src="{{ asset('layout/plugins/toastr/toastr.min.js') }}" defer></script>
   @if (\Session::has('message'))
        <script type="text/javascript" defer>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined' && typeof $ !== 'undefined') {
                    toastr["{{ \Session::get('message')['type'] }}"]('{!! \Session::get('message')['text'] !!}',
                        "{{ ucfirst(\Session::get('message')['type']) }}!");
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                }
            });
        </script>
        <?php echo \Session::forget('message'); ?>
    @endif

    @if ($errors->any())
        <script type="text/javascript" defer>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof toastr !== 'undefined' && typeof $ !== 'undefined') {
                    toastr["error"]('{{ $errors->first() }}', "Error!");
                }
            });
        </script>
    @endif

  <!-- jquery plugins - Load after jQuery with defer -->
  <script src="{{ asset('web/assets/js/jquery-ui.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/popper.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/bootstrap.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/magnific-popup.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/owl.carousel.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/owl.carousel2.thumbs.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/isotope.pkgd.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/masonry.pkgd.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/imagesloaded.pkgd.min.js')}}" defer></script>
  <script src="{{ asset('web/assets/js/countdown.js')}}" defer></script>
  
  <!-- google map - Load async -->
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDk2HrmqE4sWSei0XdKGbOMOHN3Mm2Bf-M&ver=2.1.6"></script>
  <script src="{{ asset('web/assets/js/gmaps.min.js')}}" defer></script>

  <!-- mobile menu - jquery include -->
  <script src="{{ asset('web/assets/js/mCustomScrollbar.js')}}" defer></script>
  
  <!-- custom - jquery include -->
  <script src="{{ asset('web/assets/js/custom.js')}}" defer></script>
  
  <script defer>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined') {
            console.log('jQuery is working!');
        }
    });
  </script>




  
</body>



</html>

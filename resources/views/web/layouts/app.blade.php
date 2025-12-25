<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />

  @php
      $gSetting = \App\Models\Setting::first();
      $currentLocale = app()->getLocale();
      $metaTitle = $gSetting->{'website_title_' . $currentLocale} ?? 'Phyzioline - Physical Therapy Products & Medical Equipment';
      $metaDesc = \Illuminate\Support\Str::limit($gSetting->{'description_' . $currentLocale} ?? 'Phyzioline is your premier destination for physical therapy products, medical equipment, and educational courses in the Middle East.', 160);
      $metaKeywords = 'Physical Therapy, العلاج الطبيعي, Medical Equipment, معدات طبية, Phyzioline, فيزيولاين, Rehabilitation, إعادة التأهيل, ' . ($gSetting->keywords ?? '');
      $metaImage = asset('web/assets/images/logo.png'); // Default image
      $currentUrl = url()->current();
  @endphp

  <title>@yield('title', $metaTitle)</title>
  <meta name="description" content="@yield('description', $metaDesc)">
  <meta name="keywords" content="@yield('keywords', $metaKeywords)">
  <meta name="author" content="Phyzioline">

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
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Phyzioline",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('web/assets/images/logo.png') }}",
    "sameAs": [
      "{{ $gSetting->facebook ?? 'https://facebook.com ' }}",
      "{{ $gSetting->twitter ?? '#' }}",
      "{{ $gSetting->instagram ?? '#' }}",
      "{{ $gSetting->linkedin ?? '#' }}"
    ]
  }
  </script>

  @stack('meta')
  <link rel="icon" type="image/png" href="{{ asset('web/assets/images/logo.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('web/assets/images/logo.png') }}" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- css include -->

  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/line-awesome.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/owl.carousel.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/magnific-popup.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/jquery-ui.css')}}" />
  <!-- font -->
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


  <!-- swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

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
    color: #fff !important;
    position: relative;
    z-index: 99999; /* فوق الهيدر */
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
  <!-- custom - css include -->
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/style.css')}}?v={{ time() }}" />
  
  <!-- RTL Overrides for Arabic -->
  @if(app()->getLocale() == 'ar')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/rtl-overrides.css') }}" />
  @endif

   <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stack('css')

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-QJ455L4DV3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);} 
    gtag('js', new Date());

    gtag('config', 'G-QJ455L4DV3');
  </script>

</head>
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
  <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
  
  <script>
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
    var swiper = new Swiper('.swiper-container', {
      slidesPerView: 1, // Adjust number of images visible
      spaceBetween: 10, // Adjust space between images
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
  </script>
  <script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  });
});
    <script src="{{ asset('layout/plugins/toastr/toastr.min.js') }}"></script>

  </script>
   @if (\Session::has('message'))
        <script type="text/javascript">
            $(function() {
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
            });
        </script>
        <?php echo \Session::forget('message'); ?>
    @endif

    @if ($errors->any())
        <script type="text/javascript">
            $(function() {
                toastr["error"]('{{ $errors->first() }}', "Error!");
            });
        </script>
    @endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- jquery include -->
  <script src="{{ asset('web/assets/js/jquery-3.4.1.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/jquery-ui.js')}}"></script>
  <script src="{{ asset('web/assets/js/popper.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/bootstrap.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/magnific-popup.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/owl.carousel.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/owl.carousel2.thumbs.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/isotope.pkgd.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/masonry.pkgd.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{ asset('web/assets/js/countdown.js')}}"></script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&callback=initMap">
</script>
  <!-- google map - jquery include -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDk2HrmqE4sWSei0XdKGbOMOHN3Mm2Bf-M&ver=2.1.6"></script>
  <script src="{{ asset('web/assets/js/gmaps.min.js')}}"></script>

  <!-- mobile menu - jquery include -->
  <script src="{{ asset('web/assets/js/mCustomScrollbar.js')}}"></script>
 <script>
        $(document).ready(function() {
            console.log('jQuery is working!');
        });
    </script>
  <!-- custom - jquery include -->
  <script src="{{ asset('web/assets/js/custom.js')}}"></script>




  
</body>



</html>

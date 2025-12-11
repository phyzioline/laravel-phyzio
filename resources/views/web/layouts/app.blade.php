<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />

  <title>@yield('title', __('Physioline - Physical Therapy Products & Medical Equipment'))</title>
  @stack('meta')
  <link rel="shortcut icon" href="{{ asset('web/assets/images/logo.png')}}" />
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

</style>
  <!-- custom - css include -->
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/style.css')}}" />

   <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stack('css')

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

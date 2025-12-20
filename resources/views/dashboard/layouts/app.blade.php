<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHYZIOLINE | Dashboard</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('dashboard/images/Frame 127.svg') }}" type="image/png">
  <!-- loader-->
  <link href="{{ asset('dashboard/assets/css/pace.min.css')}}" rel="stylesheet">
  <script src="{{ asset('dashboard/assets/js/pace.min.js')}}"></script>

  <!--plugins-->
  <link href="{{ asset('dashboard/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/metismenu/metisMenu.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/metismenu/mm-vertical.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/simplebar/css/simplebar.css')}}">
  <!--bootstrap css-->
  <link href="{{ asset('dashboard/assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--main css-->
  <link href="{{ asset('dashboard/assets/css/bootstrap-extended.css')}}" rel="stylesheet">
  <link href="{{ asset('dashboard/sass/main.css')}}" rel="stylesheet">
  <link href="{{ asset('dashboard/sass/dark-theme.css')}}" rel="stylesheet">
  <link href="{{ asset('dashboard/sass/semi-dark.css')}}" rel="stylesheet">
  <link href="{{ asset('dashboard/sass/bordered-theme.css')}}" rel="stylesheet">
  <link href="{{ asset('dashboard/sass/responsive.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('dashboard/css/dash.css')}}">
  <link rel="stylesheet" href="{{ asset('dashboard/css/teal-theme.css')}}">

   <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
   <!-- swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-QJ455L4DV3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);} 
    gtag('js', new Date());

    gtag('config', 'G-QJ455L4DV3');
  </script>

   
   @stack('styles')
   <style>
     html body .top-header,
     html body .top-header .navbar,
     html body .sidebar-wrapper .sidebar-header,
     html[data-bs-theme] body .top-header,
     html[data-bs-theme] body .sidebar-header,
     header, .top-header, .sidebar-header, .navbar {
       background-color: #ffffff !important;
       background: #ffffff !important;
       background-image: none !important;
       border-bottom: 1px solid #e0e0e0 !important;
       transition: none !important;
       box-shadow: none !important;
     }

     /* Ensure Logo is visible on white Sidebar Header */
     .sidebar-header img, .sidebar-wrapper .sidebar-header img {
       filter: brightness(0) saturate(100%) invert(35%) sepia(85%) saturate(468%) hue-rotate(130deg) brightness(92%) contrast(101%) !important;
       opacity: 1 !important;
     }

     /* Fix Hamburger Toggle Layout & Clicking Area */
     .btn-toggle {
       width: 45px !important;
       height: 45px !important;
       display: flex !important;
       align-items: center !important;
       justify-content: center !important;
       z-index: 1050 !important; /* Ensure it's above everything */
       cursor: pointer !important;
     }
     
     .btn-toggle a {
       padding: 0 !important;
       margin: 0 !important;
       display: flex !important;
       align-items: center !important;
       justify-content: center !important;
       width: 45px !important;
       height: 45px !important;
       background: transparent !important;
       border-radius: 50% !important;
     }

     .btn-toggle i, .btn-toggle a i {
       color: #0d9488 !important; /* Brand Teal */
       font-size: 26px !important;
       pointer-events: none !important;
     }

     /* Search Area Safety - Don't Block Toggle */
     .search-content {
       max-width: 600px !important;
       pointer-events: auto !important;
     }

     /* GLOBAL DENSITY - "The Amazon Look" */
     html {
       zoom: 0.9 !important; /* Scale to show more data */
       -moz-transform: scale(0.9);
       -moz-transform-origin: 0 0;
     }

     body {
       font-family: "Amazon Ember", Arial, sans-serif !important;
       color: #0F1111 !important;
       background-color: #f3f3f3 !important;
       font-size: 13px !important;
       line-height: 1.3 !important;
     }

     /* Tighten up everything */
     .card {
       margin-bottom: 8px !important;
       border: 1px solid #ddd !important;
       border-radius: 2px !important;
       box-shadow: none !important;
     }
     
     .card-body {
       padding: 8px 12px !important;
     }

     .table th, .table td {
       padding: 4px 8px !important;
       font-size: 12px !important;
     }

     /* Global Title Scaling */
     h1, .h1 { font-size: 18px !important; font-weight: 700 !important; }
     h2, .h2 { font-size: 16px !important; font-weight: 700 !important; }
     h3, .h3 { font-size: 14px !important; font-weight: 700 !important; }

     /* Sidebar - Keep brand colors but make compact */
     .sidebar-wrapper {
       width: 260px !important;
       background: #017A82 !important;
     }
     
     .sidebar-nav .menu-title {
       font-size: 13px !important;
     }

     .top-header .nav-link i, .top-header .nav-link span, .top-header .user-name {
       color: #0d9488 !important;
     }

     /* Responsive fixes */
     @media screen and (max-width: 991px) {
       html body .top-header { left: 0 !important; }
       .page-wrapper { margin-left: 0 !important; }
     }
   </style>
</head>

<body>

  <!--start header-->
    @include('dashboard.layouts.header')
  <!--end top header-->


  <!--start sidebar-->
    @include('dashboard.layouts.sidebar')
  <!--end sidebar-->

  @yield('content')





  <!--bootstrap js-->
   <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js')}}"></script>

  @stack('scripts')

  <!--plugins-->
  <script src="{{ asset('dashboard/assets/js/jquery.min.js')}}"></script>
  <!--plugins-->
  <script src="{{ asset('dashboard/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/metismenu/metisMenu.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/peity/jquery.peity.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/chartjs/js/chart.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/chartjs/js/chartjs-custom.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script>
    $(".data-attributes span").peity("donut")
  </script>

  <script src="{{ asset('dashboard/assets/js/data-widgets.js')}}"></script>
  <script src="{{ asset('dashboard/assets/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</body>




</html>

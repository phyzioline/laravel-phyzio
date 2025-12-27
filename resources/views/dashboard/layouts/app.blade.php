<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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
  <!-- Inter Font - Professional Typography -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
  <!-- Phyzioline Typography System - Load AFTER icon fonts to preserve them -->
  <link rel="stylesheet" href="{{ asset('css/phyzioline-typography.css')}}">
  
  <!-- RTL Support for Arabic -->
  @if(app()->getLocale() == 'ar')
  <link rel="stylesheet" href="{{ asset('dashboard/css/rtl.css')}}">
  <link rel="stylesheet" href="{{ asset('css/rtl-overrides.css')}}">
  @endif

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
     /* CRITICAL: Force White Header & Sidebar Header - Override ALL Themes */
     html body .top-header,
     html body .top-header .navbar,
     html body .sidebar-wrapper .sidebar-header,
     html[data-bs-theme] body .top-header,
     html[data-bs-theme] body .top-header .navbar,
     html[data-bs-theme] body .sidebar-header,
     html[data-bs-theme=blue-theme] body .top-header,
     html[data-bs-theme=blue-theme] body .top-header .navbar,
     html[data-bs-theme=blue-theme] body .sidebar-wrapper .sidebar-header,
     [data-bs-theme=blue-theme] body .sidebar-wrapper .sidebar-header,
     header, .top-header, .sidebar-header, .navbar {
       background-color: #ffffff !important;
       background: #ffffff !important;
       background-image: none !important;
       border-bottom: 1px solid #e0e0e0 !important;
       transition: none !important;
       box-shadow: none !important;
     }

     /* Ensure Logo is visible on white Sidebar Header */
     .sidebar-header img, 
     .sidebar-wrapper .sidebar-header img,
     html[data-bs-theme=blue-theme] .sidebar-wrapper .sidebar-header img,
     [data-bs-theme=blue-theme] body .sidebar-wrapper .sidebar-header img {
       filter: none !important;
       opacity: 1 !important;
     }

     /* Fix Hamburger Toggle Layout & Clicking Area - CRITICAL FIX */
     .btn-toggle {
       width: 45px !important;
       height: 45px !important;
       min-width: 45px !important;
       max-width: 45px !important;
       display: flex !important;
       align-items: center !important;
       justify-content: center !important;
       z-index: 9999 !important; /* Higher than everything */
       cursor: pointer !important;
       position: relative !important;
       pointer-events: auto !important;
       flex-shrink: 0 !important; /* Don't allow shrinking */
       margin-right: 15px !important;
       background: transparent !important;
       border-radius: 50% !important;
       transition: background 0.3s !important;
     }
     
     .btn-toggle:hover {
       background: rgba(13, 148, 136, 0.1) !important;
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
       pointer-events: none !important;
     }

     .btn-toggle i, .btn-toggle a i {
       color: #0d9488 !important; /* Brand Teal */
       font-size: 26px !important;
       pointer-events: none !important;
     }

     /* Navbar - Ensure proper layout */
     .top-header .navbar {
       display: flex !important;
       align-items: center !important;
       gap: 0 !important;
       padding: 0 1.5rem !important;
     }

     /* ============================================
        RESPONSIVE LAYOUT SYSTEM
        Primary: 1440px, Usable: 1280px → 1920px
        Content centered, max container: 1200-1240px
        ============================================ */

     /* Professional Typography System - Inter Font */
     * {
       font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
     }

     body {
       font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
       color: #0F1111 !important;
       background-color: #f3f3f3 !important;
       font-size: 13px !important; /* Base: 13-14px */
       font-weight: 400 !important;
       line-height: 1.5 !important; /* Line height: 1.4-1.5 */
     }
     
     /* Section Headers: 16-18px */
     h5, .h5, .card-title, .section-header {
       font-size: 16px !important;
       font-weight: 600 !important;
       line-height: 1.5 !important;
     }
     
     h6, .h6 {
       font-size: 14px !important;
       font-weight: 500 !important;
       line-height: 1.5 !important;
     }
     
     /* Typography Hierarchy - Inter Weights */
     h1, .h1 { 
       font-size: 32px !important; 
       font-weight: 700 !important; 
       line-height: 1.2 !important;
       letter-spacing: -0.5px !important;
     }
     h2, .h2 { 
       font-size: 28px !important; 
       font-weight: 700 !important; 
       line-height: 1.3 !important;
       letter-spacing: -0.3px !important;
     }
     h3, .h3 { 
       font-size: 24px !important; 
       font-weight: 600 !important; 
       line-height: 1.4 !important;
     }
     h4, .h4 { 
       font-size: 20px !important; 
       font-weight: 600 !important; 
       line-height: 1.4 !important;
     }
     h5, .h5 { 
       font-size: 18px !important; 
       font-weight: 600 !important; 
       line-height: 1.5 !important;
     }
     h6, .h6 { 
       font-size: 16px !important; 
       font-weight: 500 !important; 
       line-height: 1.5 !important;
     }
     
     /* Buttons */
     .btn {
       font-weight: 500 !important;
       font-size: 14px !important;
     }
     
     .btn-lg {
       font-size: 16px !important;
       font-weight: 600 !important;
     }
     
     .btn-sm {
       font-size: 12px !important;
       font-weight: 500 !important;
     }

     /* Cards - Vertical Growth, Minimum Height: 120-160px */
     .card {
       margin-bottom: 8px !important;
       border: 1px solid #ddd !important;
       border-radius: 2px !important;
       box-shadow: none !important;
       min-height: 140px !important; /* Min: 120-160px */
       display: flex !important;
       flex-direction: column !important;
     }
     
     .card-body {
       padding: 12px 16px !important;
       flex: 1 !important;
       display: flex !important;
       flex-direction: column !important;
     }
     
     /* Charts - Height: 280-360px */
     .chart-container {
       min-height: 300px !important; /* Charts: 280-360px */
       max-height: 360px !important;
       height: 300px !important;
     }

     .table th, .table td {
       padding: 4px 8px !important;
       font-size: 12px !important;
     }

     /* Typography already defined above with Inter font */

     /* Sidebar - Fixed Width: 240-260px (NOT scaling with screen) */
     .sidebar-wrapper {
       width: 250px !important; /* Fixed: 240-260px range */
       background: #ffffff !important;
       position: fixed !important;
       left: 0 !important;
       top: 0 !important;
       height: 100vh !important;
       z-index: 1000 !important;
       overflow-y: auto !important;
       border-right: 1px solid #e0e0e0 !important;
       box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05) !important;
     }
     
     /* RTL Sidebar Fix for Arabic */
     [dir="rtl"] .sidebar-wrapper {
       left: auto !important;
       right: 0 !important;
       border-right: none !important;
       border-left: 1px solid #e0e0e0 !important;
       box-shadow: -2px 0 10px rgba(0, 0, 0, 0.05) !important;
     }
     
     /* Body background - Clean white/gray, no sidebar color bleeding */
     body {
       background-color: #f3f3f3 !important;
       background-image: none !important;
     }
     
     .sidebar-nav .menu-title {
       font-size: 13px !important;
     }

     .top-header .nav-link i, .top-header .nav-link span, .top-header .user-name {
       color: #0d9488 !important;
     }

     /* ============================================
        RESPONSIVE BREAKPOINTS
        ============================================ */
     
     /* ≥1440px - Full 3-column layout */
     @media screen and (min-width: 1440px) {
       .main-content {
         max-width: 1220px !important;
       }
     }
     
     /* 1200-1439px - Cards tighten, same layout */
     @media screen and (min-width: 1200px) and (max-width: 1439px) {
       .main-content {
         max-width: 1220px !important;
       }
       .card {
         min-height: 130px !important;
       }
     }
     
     /* 992-1199px - Right panel moves below */
     @media screen and (min-width: 992px) and (max-width: 1199px) {
       .main-content {
         max-width: 100% !important;
         padding: 0 16px !important;
       }
       .card {
         min-height: 120px !important;
       }
     }
     
     /* 768-991px - Single column */
     @media screen and (min-width: 768px) and (max-width: 991px) {
       html body .top-header { 
         left: 0 !important; 
         width: 100% !important;
       }
       .page-wrapper,
       .page-content { 
         margin-left: 0 !important; 
         width: 100% !important;
         max-width: 100% !important;
         padding: 16px !important;
       }
       .main-content {
         max-width: 100% !important;
         padding: 0 !important;
       }
       .sidebar-wrapper {
         transform: translateX(-100%) !important;
         transition: transform 0.3s ease !important;
       }
       .sidebar-wrapper.active {
         transform: translateX(0) !important;
       }
       .main-wrapper {
         margin-left: 0 !important;
         width: 100% !important;
         max-width: 100% !important;
       }
       .card {
         min-height: 120px !important;
       }
       .chart-container {
         min-height: 280px !important;
         max-height: 320px !important;
       }
     }
     
     /* <768px - Mobile view (375px → 414px primary) */
     @media screen and (max-width: 767px) {
       html {
         -webkit-text-size-adjust: 100% !important;
         -moz-text-size-adjust: 100% !important;
         -ms-text-size-adjust: 100% !important;
         text-size-adjust: 100% !important;
       }
       html body .top-header { 
         left: 0 !important; 
         width: 100% !important;
       }
       .page-wrapper,
       .page-content { 
         margin-left: 0 !important; 
         width: 100% !important;
         max-width: 100% !important;
         padding: 16px !important; /* Horizontal padding: 16px */
       }
       .main-content {
         max-width: 100% !important; /* Max container: 100% width */
         padding: 0 !important;
       }
       .sidebar-wrapper {
         transform: translateX(-100%) !important;
         transition: transform 0.3s ease !important;
       }
       .sidebar-wrapper.active {
         transform: translateX(0) !important;
       }
       .main-wrapper {
         margin-left: 0 !important;
         width: 100% !important;
         max-width: 100% !important;
       }
       body {
         font-size: 13px !important;
       }
       .card {
         min-height: 120px !important;
         margin-bottom: 12px !important;
       }
       .card-body {
         padding: 12px 16px !important; /* Horizontal padding: 16px */
       }
       .chart-container {
         min-height: 280px !important;
         max-height: 300px !important;
       }
       h5, .h5, .card-title {
         font-size: 16px !important;
       }
     }
     
     /* CRITICAL: Page Wrapper - Centered Content Container */
     .page-wrapper,
     .page-content {
       margin-left: 250px !important; /* Sidebar width */
       margin-top: 70px !important; /* Header height */
       margin-right: 0 !important;
       transition: all 0.3s ease;
       padding: 20px;
       min-height: calc(100vh - 70px);
       width: calc(100% - 250px) !important;
       max-width: calc(100% - 250px) !important;
       box-sizing: border-box;
       background-color: #f3f3f3 !important;
       overflow-x: hidden !important;
       overflow-y: auto !important; /* Vertical scrolling only */
       display: flex !important;
       justify-content: center !important; /* Center content */
     }
     
     /* Main Content Container - Centered, Max: 1200-1240px */
     .main-content {
       width: 100% !important;
       max-width: 1220px !important; /* Max: 1200-1240px */
       margin: 0 auto !important; /* Center horizontally */
       box-sizing: border-box !important;
     }
     
     /* RTL Fixes for Arabic - No extra margin on main-content */
     [dir="rtl"] .main-content {
       margin-left: 0 !important;
       margin-right: 0 !important; /* No margin - already inside page-wrapper */
     }
     
     /* RTL Fixes for Arabic */
     [dir="rtl"] .page-wrapper,
     [dir="rtl"] .page-content {
       margin-left: 0 !important;
       margin-right: 250px !important; /* Sidebar width on right */
       width: calc(100% - 250px) !important;
       max-width: calc(100% - 250px) !important;
     }
     
     /* Prevent horizontal scroll and scaling issues */
     html, body {
       overflow-x: hidden !important;
       max-width: 100vw !important;
       position: relative !important;
     }
     
     * {
       box-sizing: border-box !important;
     }
     
     /* When sidebar is toggled/collapsed */
     body.toggled .page-wrapper,
     body.toggled .page-content {
       margin-left: 0 !important;
       width: 100% !important;
       max-width: 100% !important;
     }
     
     body.toggled .main-content {
       max-width: 1220px !important; /* Keep centered container */
     }
     
     /* RTL Toggle State */
     [dir="rtl"] body.toggled .page-wrapper,
     [dir="rtl"] body.toggled .page-content {
       margin-right: 0 !important;
       margin-left: 0 !important;
     }
     
     [dir="rtl"] body.toggled .top-header .navbar {
       right: 0 !important;
       left: auto !important;
     }
     
     [dir="rtl"] body.toggled .sidebar-wrapper {
       right: -250px !important;
       left: auto !important;
     }
     
     /* Main wrapper alternative */
     .main-wrapper {
       margin-left: 250px !important;
       margin-top: 70px !important;
       margin-right: 0 !important;
       transition: all 0.3s ease;
       padding: 20px;
       width: calc(100% - 250px) !important;
       background-color: #f3f3f3 !important;
       max-width: calc(100vw - 250px - 40px) !important;
       box-sizing: border-box;
     }
     
     body.toggled .main-wrapper {
       margin-left: 0 !important;
       width: 100% !important;
       max-width: 100% !important;
     }
     
     /* RTL Main Wrapper Fix */
     [dir="rtl"] .main-wrapper {
       margin-left: 0 !important;
       margin-right: 250px !important;
       width: calc(100% - 250px) !important;
       max-width: calc(100vw - 250px - 40px) !important;
     }
     
     [dir="rtl"] body.toggled .main-wrapper {
       margin-right: 0 !important;
       margin-left: 0 !important;
     }
     
     /* Fix header alignment - Ensure it starts after sidebar */
     .top-header {
       left: 250px !important;
       right: 0 !important;
       width: calc(100% - 250px) !important;
       background-color: #ffffff !important;
       z-index: 999 !important;
     }
     
     body.toggled .top-header {
       left: 0 !important;
       width: 100% !important;
     }
     
     /* RTL Header Fix */
     [dir="rtl"] .top-header {
       left: auto !important;
       right: 250px !important;
       width: calc(100% - 250px) !important;
     }
     
     [dir="rtl"] body.toggled .top-header {
       right: 0 !important;
       left: auto !important;
     }
     
     /* Ensure no background color bleeding from sidebar */
     html, body {
       overflow-x: hidden !important;
       max-width: 100vw !important;
       position: relative !important;
     }
     
     /* Fix footer section in sidebar */
     #footer-section {
       position: relative !important;
       margin-top: auto !important;
       padding-top: 20px !important;
       padding-bottom: 20px !important;
       border-top: 1px solid #e0e0e0 !important;
       background: transparent !important;
     }
     
     /* Sidebar navigation container - flexbox for footer positioning */
     .sidebar-nav {
       display: flex !important;
       flex-direction: column !important;
       height: calc(100vh - 60px) !important; /* Full height minus header */
       overflow: hidden !important;
     }
     
     .sidebar-nav > ul {
       flex: 1 !important;
       overflow-y: auto !important;
       overflow-x: hidden !important;
       padding-bottom: 10px !important;
     }
     
     /* Ensure sidebar content doesn't overflow */
     .sidebar-wrapper {
       display: flex !important;
       flex-direction: column !important;
     }
     
     .sidebar-wrapper .sidebar-nav {
       flex: 1 !important;
       min-height: 0 !important;
     }
     
     /* Prevent any scaling/zoom issues - Fixed for both LTR and RTL */
     @media screen and (min-width: 992px) {
       html {
         zoom: 1 !important;
         -webkit-transform: scale(1) !important;
         transform: scale(1) !important;
       }
       
       /* Ensure no zoom in RTL */
       [dir="rtl"] html {
         zoom: 1 !important;
         -webkit-transform: scale(1) !important;
         transform: scale(1) !important;
       }
     }
     
     /* RTL Mobile Responsive Fixes */
     @media screen and (max-width: 991px) {
       [dir="rtl"] .sidebar-wrapper {
         transform: translateX(100%) !important; /* Slide from right */
       }
       [dir="rtl"] .sidebar-wrapper.active {
         transform: translateX(0) !important;
       }
       [dir="rtl"] .top-header .navbar {
         right: 0 !important;
         left: auto !important;
       }
     }
     
     /* Ensure dashboard scrolls vertically, never horizontally */
     html, body {
       overflow-x: hidden !important;
       overflow-y: auto !important;
     }
     
     .page-wrapper,
     .page-content,
     .main-content {
       overflow-x: hidden !important;
     }
     
     /* Fix cards and content overflow */
     .card, .row, .col, [class*="col-"] {
       max-width: 100% !important;
       overflow-x: hidden !important;
     }
     
     /* Fix table overflow */
     .table-responsive, .table {
       max-width: 100% !important;
       overflow-x: auto !important;
     }
     
     /* Main content already defined above with centered container */
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





  <!--jQuery - MUST BE LOADED FIRST-->
  <script src="{{ asset('dashboard/assets/js/jquery.min.js')}}"></script>
  
  <!--bootstrap js-->
   <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js')}}"></script>

  <!--SweetAlert2 - Load before page scripts-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
  
  <!--Page-specific scripts - Load AFTER all libraries-->
  @stack('scripts')
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

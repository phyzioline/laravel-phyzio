<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

   
   <style>
     /* Global Amazon High-Density Redesign with Phyzioline Brand Colors */
     :root {
       --amazon-font-size: 13px;
       --amazon-table-font-size: 12px;
       --brand-color: #017A82; /* Phyzioline Teal */
       --brand-hover: #00A6B4;
       --secondary-color: #565959;
       --amazon-link-color: #007185;
     }

     body {
       font-family: "Amazon Ember", Arial, sans-serif !important;
       font-size: var(--amazon-font-size) !important;
       color: #0F1111 !important;
       background-color: #F2F4F8 !important;
     }

     /* Header Overrides - Force White with High Specificity */
     [data-bs-theme=blue-theme] body .top-header,
     .top-header {
       background-color: #ffffff !important;
       border-bottom: 1px solid #D5D9D9 !important;
       left: 260px !important; /* Sidebar width */
       transition: all 0.3s;
     }
     
     .top-header .navbar {
       padding: 5px 20px !important;
       background: #ffffff !important; /* Explicitly white nav too */
     }
     
     /* Ensure Icons and Text are visible on white background */
     .top-header .material-icons-outlined, 
     .top-header .nav-link,
     .top-header .btn-toggle a i,
     .top-header .user-name {
       color: var(--brand-color) !important;
     }

     /* Page Layout */
     .page-wrapper {
       margin-left: 260px !important;
       padding-top: 60px !important;
       transition: all 0.3s;
     }

     /* Tight Spacing Global Controls */
     .card {
       border-radius: 4px !important;
       border: 1px solid #D5D9D9 !important;
       margin-bottom: 12px !important;
       box-shadow: none !important;
     }
     
     .card-body {
       padding: 10px 15px !important; /* Tight padding */
     }

     /* Tables - Amazon High Density */
     .table {
       font-size: var(--amazon-table-font-size) !important;
       width: 100% !important;
     }
     
     .table th {
       background-color: #F0F2F2 !important;
       padding: 6px 10px !important;
       font-weight: 700 !important;
       text-transform: none !important;
       color: #0F1111 !important;
       border-bottom: 1px solid #D5D9D9 !important;
     }
     
     .table td {
       padding: 6px 10px !important;
       vertical-align: top !important;
       border-bottom: 1px solid #F0F2F2 !important;
     }

     /* Form Controls - Compact & Professional */
     .form-control, .form-select {
       font-size: var(--amazon-font-size) !important;
       padding: 3px 8px !important;
       height: 31px !important;
       border: 1px solid #888C8C !important;
       border-radius: 3px !important;
       box-shadow: 0 1px 2px rgba(15,17,17,0.15) inset !important;
     }
     
     .form-label {
       font-size: 12px !important;
       font-weight: 700 !important;
       margin-bottom: 2px !important;
       color: #0F1111 !important;
     }

     /* Buttons - Compact */
     .btn {
       padding: 3px 12px !important;
       font-size: 13px !important;
       height: 31px !important;
       display: inline-flex !important;
       align-items: center !important;
       justify-content: center !important;
     }
     
     .btn-primary {
       background-color: var(--brand-color) !important;
       border-color: var(--brand-color) !important;
     }

     /* Sidebar Branding - DON'T HIDE */
     .sidebar-wrapper {
       width: 260px !important;
       background: var(--brand-color) !important; /* Ensure brand color constant */
     }
     
     .sidebar-header {
       background: #004d4d !important; 
     }

     /* Responsive fluid adjustments */
     @media screen and (max-width: 991px) {
       .top-header { left: 0 !important; }
       .page-wrapper { margin-left: 0 !important; }
     }

     /* Custom UI Scrollbar */
     ::-webkit-scrollbar { width: 7px; height: 7px; }
     ::-webkit-scrollbar-thumb { background: #bbb; border-radius: 10px; }
     ::-webkit-scrollbar-track { background: #f1f1f1; }
   </style>
   @stack('styles')
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

  <script>
document.getElementById('toggle-button').addEventListener('click', function() {

  var footer = document.getElementById('footer-section');


  if (footer.style.display === "none") {
    footer.style.display = "block";
  } else {
    footer.style.display = "none";
  }
});
  </script>

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
</body>




</html>

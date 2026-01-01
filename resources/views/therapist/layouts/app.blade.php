<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHYZIOLINE | Therapist Dashboard</title>
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
  
  <!-- Therapist Theme & Typography -->
  <link href="{{ asset('dashboard/css/teal-theme.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/line-awesome.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/phyzioline-typography.css')}}">

  <style>
    /* Overlay - Only show on mobile when sidebar is toggled */
    .overlay {
      display: none !important;
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: #000;
      opacity: 0.5;
      z-index: 11;
      cursor: pointer;
      transition: all 0.23s ease-out;
    }
    
    /* Only show overlay on mobile when toggled */
    @media only screen and (max-width: 1199px) {
      .toggled .overlay {
        display: block !important;
      }
    }
    
    /* Hide overlay on desktop */
    @media only screen and (min-width: 1200px) {
      .overlay {
        display: none !important;
      }
    }

    /* Fix main-wrapper to account for fixed header */
    .main-wrapper {
      margin-top: 70px !important; /* Header height */
      padding-top: 20px !important;
      min-height: calc(100vh - 70px);
      position: relative;
      z-index: 1;
    }

    /* Ensure header doesn't overlap content */
    .top-header {
      position: fixed !important;
      top: 0 !important;
      left: 260px !important;
      right: 0 !important;
      z-index: 999 !important;
      height: 70px !important;
    }

    /* When sidebar is toggled, adjust margins */
    body.toggled .main-wrapper {
      margin-left: 0 !important;
    }

    body.toggled .top-header {
      left: 0 !important;
      width: 100% !important;
    }

    /* Ensure main-content has proper spacing */
    .main-content {
      padding: 20px;
      width: 100%;
      position: relative;
    }

    /* Mobile responsive */
    @media only screen and (max-width: 1199px) {
      .main-wrapper {
        margin-left: 0 !important;
        width: 100% !important;
        margin-top: 70px !important;
      }
      
      .top-header {
        left: 0 !important;
        width: 100% !important;
      }
    }
    
    /* Ensure modals are always on top of everything */
    .modal {
      z-index: 9999 !important;
    }
    .modal-backdrop {
      z-index: 9998 !important;
    }
    .modal.show {
      display: block !important;
      pointer-events: auto !important;
    }
    .modal-dialog {
      pointer-events: auto !important;
    }
    .modal-content {
      pointer-events: auto !important;
    }
    /* Hide overlay when modal is open */
    body.modal-open .overlay {
      display: none !important;
      z-index: 1 !important;
    }
  </style>

   <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
   <!-- swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />



</head>

<body>

  <!--start header-->
    @include('therapist.layouts.header')
  <!--end top header-->


  <!--start sidebar-->
    @include('therapist.layouts.sidebar')
  <!--end sidebar-->

  <!-- Overlay for mobile sidebar -->
  <div class="overlay" style="display: none;"></div>

  <main class="main-wrapper">
    <div class="main-content">
        @yield('content')
    </div>
  </main>
  
  
  
  <!--plugins-->
  <script src="{{ asset('dashboard/assets/js/jquery.min.js')}}"></script>
  <!--bootstrap js-->
  <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js')}}"></script>

  <script src="{{ asset('dashboard/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/metismenu/metisMenu.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/peity/jquery.peity.min.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/chartjs/js/chart.js')}}"></script>
  <script src="{{ asset('dashboard/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  
  <script src="{{ asset('dashboard/assets/js/main.js')}}"></script>

  <script>
    // Simple footer toggle script from original layout
    // Assuming footer element exists globally or in partials if needed.
    // Preserving logic but footer-section is in sidebar.php in original, so it might need adjustment if moved.
  </script>

  @stack('scripts')

  <script>
    // Handle overlay click to close sidebar on mobile
    $(document).ready(function() {
      $('.overlay').on('click', function() {
        $('body').removeClass('toggled');
        $(this).hide();
      });
      
      // Show/hide overlay when sidebar is toggled (only on mobile)
      $('.btn-toggle').on('click', function() {
        if ($(window).width() <= 1199) {
          if ($('body').hasClass('toggled')) {
            $('.overlay').show();
          } else {
            $('.overlay').hide();
          }
        }
      });
      
      // Hide overlay on window resize if desktop
      $(window).on('resize', function() {
        if ($(window).width() > 1199) {
          $('.overlay').hide();
          $('body').removeClass('toggled');
        }
      });
    });
  </script>

   @if (\Session::has('message'))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script type="text/javascript">
            $(function() {
                toastr["{{ \Session::get('message')['type'] }}"]('{!! \Session::get('message')['text'] !!}',
                    "{{ ucfirst(\Session::get('message')['type']) }}!");
            });
        </script>
        <?php echo \Session::forget('message'); ?>
    @endif
    
    @if(session('success'))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script type="text/javascript">
            toastr.success("{{ session('success') }}");
        </script>
    @endif

</body>

</html>

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

   <link href="{{ asset('layout/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
   <!-- swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

  <!--start header-->
    @include('therapist.layouts.header')
  <!--end top header-->


  <!--start sidebar-->
    @include('therapist.layouts.sidebar')
  <!--end sidebar-->

  <main class="main-wrapper">
    <div class="main-content">
        @yield('content')
    </div>
  </main>
  
  
  
  <!--bootstrap js-->
  <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js')}}"></script>

  <script>
    // Simple footer toggle script from original layout
    // Assuming footer element exists globally or in partials if needed.
    // Preserving logic but footer-section is in sidebar.php in original, so it might need adjustment if moved.
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
  <script src="{{ asset('dashboard/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  
  <script src="{{ asset('dashboard/assets/js/main.js')}}"></script>

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

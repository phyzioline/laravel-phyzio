<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHYZIOLINE | Dashboard</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('dashboard/images/Frame 127.svg') }}" type="image/png">
    <!-- loader-->
    <link href="{{ asset('dashboard/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('dashboard/assets/js/pace.min.js') }}"></script>

    <!--plugins-->
    <link href="{{ asset('dashboard/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/metismenu/mm-vertical.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/plugins/simplebar/css/simplebar.css') }}">
    <!--bootstrap css-->
    <link href="{{ asset('dashboard/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--main css-->
    <link href="{{ asset('dashboard/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/sass/semi-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/sass/bordered-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/sass/responsive.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/css/dash.css') }}">

    <style>
        body {
            background: linear-gradient(90deg, #02767F 0%, #04D5E5 100%);
        }

        .card-signup {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: 0.3s;
        }

        .card-signup:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card-login {
            padding-top: 50px;
        }
    </style>
</head>

<body>

    <section>
        <div class="container">
            <div class="row mt-3 justify-content-center card-login">
                <div class="col-lg-6 col-sm-12 col-xl-6">
                    <div class="card-back">
                        <div class="center-wrap card-signup p-3">
                            <div class="d-flex justify-content-center">
                                <img style="max-width: 100%;" src="{{ asset('dashboard/images/Frame 131.svg') }}" width="200px" alt="">
                            </div>
                            <h4 style="color: #02767F;" class="text-center my-3">Dashboard Login</h4>
                            <form class="form w-100" action="{{ route('loginAction') }}" method="post"
                                data-kt-redirect-url="#" action="#">
                                @csrf


                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email"
                                        autocomplete="off">
                                </div>


                                <div class="form-group">
                                    <input type="password" class="form-control my-3" name="password"
                                        placeholder="Your Password" autocomplete="off">
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button style="background-color:#02767F ; color: #fff;" class="btn">Login</button>
                                </div>
                            </form>
                                                        

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>





    <!--bootstrap js-->
    <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js') }}"></script>

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


    <!--bootstrap js-->
    <script src="{{ asset('dashboard/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--plugins-->
    <script src="{{ asset('dashboard/assets/js/jquery.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('dashboard/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script src="{{ asset('dashboard/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/main.js') }}"></script>


</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Phyzioline') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web/assets/images/logo.png') }}" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --primary-color: #00897b; /* Teal Green */
            --secondary-color: #00695c; /* Darker Teal */
            --bg-color: #f8f9fa;
            --sidebar-width: 260px;
            --text-color: #333;
            --font-english: 'Inter', sans-serif;
            --font-arabic: 'Cairo', sans-serif;
        }

        body {
            font-family: var(--font-arabic); /* Default to Cairo for uniform look, or switch based on logic */
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
            font-size: 14px; /* Base size */
            font-weight: 400;
        }
        
        /* Typography Rules */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700 !important; /* Bold headers only */
        }
        h1 { font-size: 24px; }
        h2 { font-size: 22px; }
        h3 { font-size: 20px; }
        h4 { font-size: 18px; }

        /* Table Rules */
        table, .table {
            font-size: 12px !important;
        }
        .table thead th {
             font-size: 11px;
             text-transform: uppercase;
             font-weight: 600;
        }

        /* English specific overrides if needed */
        .lang-en {
            font-family: var(--font-english);
        }

        /* Sidebar Styles */
        .dashboard-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--primary-color);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand img {
            max-width: 150px;
            filter: brightness(0) invert(1);
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: 0.3s;
            font-size: 15px;
        }

        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background: var(--secondary-color);
            color: #fff;
            border-left: 4px solid #fff;
        }

        .sidebar-menu li a i {
            font-size: 22px;
            margin-right: 10px; /* Adjust for RTL later */
        }
        
        /* RTL Support for Sidebar Icons */
        html[dir="rtl"] .sidebar-menu li a i {
            margin-right: 0;
            margin-left: 10px;
            border-left: none;
        }
        html[dir="rtl"] .sidebar-menu li a:hover, html[dir="rtl"] .sidebar-menu li a.active {
             border-left: none;
             border-right: 4px solid #fff;
        }

        /* Main Content */
        .dashboard-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
        }
        
        html[dir="rtl"] .dashboard-sidebar {
            left: auto;
            right: 0;
        }
        
        html[dir="rtl"] .dashboard-content {
            margin-left: 0;
            margin-right: var(--sidebar-width);
        }

        /* Header */
        .dashboard-header {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .search-wrapper {
            border: 1px solid #eee;
            border-radius: 30px;
            height: 40px;
            display: flex;
            align-items: center;
            overflow-x: hidden;
            width: 300px;
        }

        .search-wrapper input {
            height: 100%;
            padding: 0.5rem;
            border: none;
            outline: none;
            width: 100%;
        }
        
        .search-wrapper span {
            padding-left: 1rem;
        }

        .user-wrapper {
            display: flex;
            align-items: center;
        }
        
        .user-wrapper img {
            border-radius: 50%;
            margin-right: 1rem;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px; /* RTL adjust needed */
        }
        
        html[dir="rtl"] .icon-box {
            margin-right: 0;
            margin-left: 15px;
        }

        .icon-teal { background: #e0f2f1; color: #00897b; }
        .icon-green { background: #e8f5e9; color: #43a047; }
        .icon-blue { background: #e3f2fd; color: #1e88e5; }
        .icon-orange { background: #fff3e0; color: #fb8c00; }

        /* Mobile Responsive */
        @media only screen and (max-width: 991px) {
            .dashboard-sidebar {
                left: -100%; /* Hide Sidebar */
            }
            html[dir="rtl"] .dashboard-sidebar {
                right: -100%;
            }
            
            .dashboard-content {
                margin-left: 0;
                margin-right: 0 !important;
            }
            
            .dashboard-sidebar.active {
                left: 0;
            }
            html[dir="rtl"] .dashboard-sidebar.active {
                right: 0;
            }
        }
        

        



    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="dashboard-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="las la-clinic-medical"></span> Phyzioline
        </div>

        <ul class="sidebar-menu">
            <!-- Common Links -->
            <!-- Common Links (Hidden for Clinic to avoid 403) -->
            @if(!request()->routeIs('clinic.*') && !auth()->user()->hasRole('clinic'))
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <span class="las la-igloo"></span>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            @endif

            <!-- Instructor Portal (Hidden for Clinics) -->
            @if(!request()->routeIs('clinic.*') && (auth()->user()->hasRole('instructor') || auth()->user()->type == 'therapist'))
                <li class="menu-label mt-3 ml-3 text-white small">{{ __('Instructor Portal') }}</li>
                <li>
                    <a href="{{ route('therapist.courses.index') }}" class="{{ request()->routeIs('therapist.courses.index') ? 'active' : '' }}">
                         <span class="las la-tachometer-alt"></span>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('therapist.courses.create') }}" class="{{ request()->routeIs('therapist.courses.create') ? 'active' : '' }}">
                        <span class="las la-plus-circle"></span>
                        <span>{{ __('Create Course') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('therapist.courses.index') }}" class="{{ request()->routeIs('therapist.courses.index') ? 'active' : '' }}">
                        <span class="las la-book"></span>
                        <span>{{ __('My Courses') }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="">
                        <span class="las la-users"></span>
                        <span>{{ __('Students') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('therapist.earnings.index') }}" class="{{ request()->routeIs('therapist.earnings.index') ? 'active' : '' }}">
                        <span class="las la-wallet"></span>
                        <span>{{ __('Earnings') }}</span>
                    </a>
                </li>
            @endif
            
            <!-- Clinic Specific (Company Dashboard Design) -->
            @if(request()->routeIs('clinic.*') || auth()->user()->hasRole('clinic'))
                 <li>
                    <a href="{{ route('clinic.dashboard') }}" class="{{ request()->routeIs('clinic.dashboard') ? 'active' : '' }}">
                         <span class="las la-chart-pie"></span> <!-- Icon matching Vercel Dashboard -->
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                 
                <!-- Job System for Clinics -->
                 <li>
                    <a href="{{ route('clinic.jobs.index') }}" class="{{ request()->routeIs('clinic.jobs.*') ? 'active' : '' }}">
                        <span class="las la-briefcase"></span>
                        <span>{{ __('Job System') }}</span>
                    </a>
                </li>

                <!-- Clinic Episodes (New ERP Module) -->
                <li>
                    <a href="{{ route('clinic.episodes.index') }}" class="{{ request()->routeIs('clinic.episodes.*') ? 'active' : '' }}">
                        <span class="las la-notes-medical"></span>
                        <span>{{ __('Clinical Episodes') }}</span>
                    </a>
                </li>

                 <li>
                    <a href="{{ route('clinic.departments.index') }}" class="{{ request()->routeIs('clinic.departments.*') ? 'active' : '' }}">
                        <span class="las la-stethoscope"></span>
                        <span>{{ __('Services') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clinic.doctors.index') }}" class="{{ request()->routeIs('clinic.doctors.*') ? 'active' : '' }}">
                         <span class="las la-user-nurse"></span>
                        <span>{{ __('Doctors') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clinic.appointments.index') }}" class="{{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}">
                        <span class="las la-calendar-check"></span>
                        <span>{{ __('Appointments') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clinic.patients.index') }}" class="{{ request()->routeIs('clinic.patients.*') ? 'active' : '' }}">
                         <span class="las la-user-injured"></span>
                        <span>{{ __('Patients') }}</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('clinic.staff.index') }}" class="{{ request()->routeIs('clinic.staff.*') ? 'active' : '' }}">
                        <span class="las la-users"></span>
                        <span>{{ __('Staff') }}</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('clinic.analytics.index') }}" class="{{ request()->routeIs('clinic.analytics.*') ? 'active' : '' }}">
                        <span class="las la-chart-bar"></span>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('clinic.billing.index') }}" class="{{ request()->routeIs('clinic.billing.*') ? 'active' : '' }}">
                        <span class="las la-file-invoice-dollar"></span>
                        <span>{{ __('Billing') }}</span>
                    </a>
                </li>
                  <li>
                    <a href="{{ route('clinic.notifications.index') }}" class="{{ request()->routeIs('clinic.notifications.*') ? 'active' : '' }}">
                        <span class="las la-bell"></span>
                        <span>{{ __('Notifications') }}</span>
                    </a>
                </li>
            @endif

            <!-- Common Footer Links -->
            <li>
                <a href="{{ route('home') }}">
                    <span class="las la-arrow-left"></span>
                    <span>{{ __('Back to Website') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}">
                    <span class="las la-sign-out-alt"></span>
                    <span>{{ __('Logout') }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">
        <!-- Top Header -->
        <header class="dashboard-header">
            <div class="d-flex align-items-center">
                <button id="sidebarToggle" class="btn btn-light d-lg-none mr-3"><i class="las la-bars"></i></button>
                <h4>@yield('header_title', 'Dashboard')</h4>
            </div>

            <div class="user-wrapper">
                <div class="mr-3 text-right d-none d-md-block">
                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ ucfirst(Auth::user()->type) }}</small>
                </div>
                <img src="{{ Auth::user()->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" width="40" height="40" alt="Profile">
                <div>
                     <button class="btn btn-sm btn-light position-relative">
                        <i class="las la-bell" style="font-size: 1.5rem;"></i>
                        <span class="badge badge-danger position-absolute" style="top: -5px; right: -5px;">3</span>
                     </button>
                </div>
            </div>
        </header>

        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('.dashboard-sidebar').toggleClass('active');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

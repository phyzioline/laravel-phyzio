<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Therapist Dashboard | Phyzioline</title>
    <link rel="shortcut icon" href="{{ asset('web/assets/images/logo.png')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/line-awesome.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('web/assets/css/style.css')}}" />
    <link href="{{ asset('dashboard/css/teal-theme.css')}}" rel="stylesheet">
    <!-- Phyzioline Typography System -->
    <link rel="stylesheet" href="{{ asset('css/phyzioline-typography.css')}}">
    <!-- Inter Font - Professional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Professional Typography System - Inter Font */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }
        
        body { 
            background-color: #f4f6f9; 
            font-family: 'Inter', sans-serif !important;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
        }
        
        /* Typography Hierarchy */
        h1 { font-size: 32px; font-weight: 700; line-height: 1.2; letter-spacing: -0.5px; }
        h2 { font-size: 28px; font-weight: 700; line-height: 1.3; letter-spacing: -0.3px; }
        h3 { font-size: 24px; font-weight: 600; line-height: 1.4; }
        h4 { font-size: 20px; font-weight: 600; line-height: 1.4; }
        h5 { font-size: 18px; font-weight: 600; line-height: 1.5; }
        h6 { font-size: 16px; font-weight: 500; line-height: 1.5; }
        
        .btn {
            font-weight: 500;
            font-size: 14px;
        }
    </style>
    @stack('css')
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <a href="{{ '/' . app()->getLocale() }}">
                <img src="{{ asset('web/assets/images/LOGO PHYSIOLINE SVG 1 (2).svg') }}" 
                     alt="Phyzioline Logo" 
                     style="max-width: 180px; height: auto;">
            </a>
        </div>
        <nav>
            <a href="{{ route('therapist.dashboard') }}" class="nav-link {{ request()->routeIs('therapist.dashboard') ? 'active' : '' }}">
                <i class="las la-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('therapist.home_visits.index') }}" class="nav-link {{ request()->routeIs('therapist.home_visits.index') ? 'active' : '' }}">
                <i class="las la-calendar-check"></i> Home Visits
            </a>
            <a href="{{ route('therapist.availability.edit') }}" class="nav-link {{ request()->routeIs('therapist.availability.edit') ? 'active' : '' }}">
                <i class="las la-clock"></i> Availability
            </a>
            <a href="{{ route('therapist.profile.edit') }}" class="nav-link {{ request()->routeIs('therapist.profile.edit') ? 'active' : '' }}">
                <i class="las la-user-circle"></i> My Profile
            </a>
            <hr>
            <a href="{{ route('logout.' . app()->getLocale()) }}" class="nav-link text-danger">
                <i class="las la-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-bar">
            <h4>@yield('header_title', 'Dashboard')</h4>
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script src="{{ asset('web/assets/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{ asset('web/assets/js/bootstrap.min.js')}}"></script>
@stack('scripts')
</body>
</html>

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
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Alexandria', sans-serif; }
    </style>
    @stack('css')
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <a href="{{ '/' . app()->getLocale() }}">
                <img src="{{ asset('web/assets/images/logo.png') }}" alt="Phyzioline" style="max-width: 80%;">
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
            <a href="{{ route('logout') }}" class="nav-link text-danger">
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

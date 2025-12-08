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
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Alexandria', sans-serif; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); padding: 20px; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; display: block; border-radius: 5px; margin-bottom: 5px; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #f0f9ff; color: #007bff; font-weight: bold; }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { flex: 1; padding: 30px; }
        .card-box { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    </style>
    @stack('css')
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}">
                <img src="{{ asset('web/assets/images/logo.png') }}" alt="Phyzioline" style="max-width: 80%;">
            </a>
        </div>
        <nav>
            <a href="{{ route('therapist.dashboard') }}" class="nav-link {{ request()->routeIs('therapist.dashboard') ? 'active' : '' }}">
                <i class="las la-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('therapist.appointments.index') }}" class="nav-link {{ request()->routeIs('therapist.appointments.index') ? 'active' : '' }}">
                <i class="las la-calendar-check"></i> Appointments
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

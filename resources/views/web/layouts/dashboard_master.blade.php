<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Phyzioline') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web/assets/images/logo.png') }}" />
    
    <!-- Fonts - Inter for Professional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <!-- Phyzioline Typography System -->
    <link rel="stylesheet" href="{{ asset('css/phyzioline-typography.css')}}">
    
    <style>
        :root {
            --primary-color: #00897b; /* Teal Green */
            --secondary-color: #00695c; /* Darker Teal */
            --bg-color: #f8f9fa;
            --sidebar-width: 260px;
            --text-color: #333;
            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Professional Typography System - Inter Font */
        * {
            font-family: var(--font-primary) !important;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
        }
        
        /* Typography Hierarchy - Inter Weights */
        h1 { 
            font-size: 32px; 
            font-weight: 700; 
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        h2 { 
            font-size: 28px; 
            font-weight: 700; 
            line-height: 1.3;
            letter-spacing: -0.3px;
        }
        h3 { 
            font-size: 24px; 
            font-weight: 600; 
            line-height: 1.4;
        }
        h4 { 
            font-size: 20px; 
            font-weight: 600; 
            line-height: 1.4;
        }
        h5 { 
            font-size: 18px; 
            font-weight: 600; 
            line-height: 1.5;
        }
        h6 { 
            font-size: 16px; 
            font-weight: 500; 
            line-height: 1.5;
        }
        
        /* Body Text */
        p, body, .text-body {
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
        }
        
        /* Buttons */
        .btn {
            font-weight: 500;
            font-size: 14px;
        }
        
        .btn-lg {
            font-size: 16px;
            font-weight: 600;
        }
        
        .btn-sm {
            font-size: 12px;
            font-weight: 500;
        }

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
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand img {
            max-width: 180px;
            height: auto;
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
            <a href="{{ '/' . app()->getLocale() }}" style="display: block;">
                <img src="{{ asset('web/assets/images/LOGO PHYSIOLINE SVG 1 (2).svg') }}" 
                     alt="Phyzioline Logo" 
                     style="max-width: 180px; height: auto;">
            </a>
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
                    <a href="{{ route('instructor.courses.index') }}" class="{{ request()->routeIs('instructor.courses.index') ? 'active' : '' }}">
                         <span class="las la-tachometer-alt"></span>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.courses.create') }}" class="{{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">
                        <span class="las la-plus-circle"></span>
                        <span>{{ __('Create Course') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.courses.index') }}" class="{{ request()->routeIs('instructor.courses.index') ? 'active' : '' }}">
                        <span class="las la-book"></span>
                        <span>{{ __('My Courses') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('instructor.students.index') }}" class="{{ request()->routeIs('instructor.students.*') ? 'active' : '' }}">
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
                <!-- 1. Dashboard (Overview) -->
                <li>
                    <a href="{{ route('clinic.dashboard') }}" class="{{ request()->routeIs('clinic.dashboard') ? 'active' : '' }}">
                        <span class="las la-chart-pie"></span>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                 
                <!-- 2. Specialty Selection (Critical Setup - if not selected) -->
                @php
                    $user = auth()->user();
                    $clinic = \App\Models\Clinic::where('company_id', $user->id)->first();
                @endphp
                @if($clinic && !$clinic->hasSelectedSpecialty())
                <li>
                    <a href="{{ route('clinic.specialty-selection.show') }}" class="{{ request()->routeIs('clinic.specialty-selection.*') ? 'active' : '' }}" style="background-color: #ff9800; color: white;">
                        <span class="las la-stethoscope"></span>
                        <span>{{ __('Select Specialty') }}</span>
                        <span class="badge badge-warning ml-2">{{ __('Required') }}</span>
                    </a>
                </li>
                @endif

                <!-- 3. Profile & Settings (Clinic Configuration) -->
                <li>
                    <a href="{{ route('clinic.profile.index') }}" class="{{ request()->routeIs('clinic.profile.*') ? 'active' : '' }}">
                        <span class="las la-cog"></span>
                        <span>{{ __('Profile & Settings') }}</span>
                    </a>
                </li>

                <!-- 4. Staff Management (Hire and Manage Staff) -->
                <li>
                    <a href="{{ route('clinic.staff.index') }}" class="{{ request()->routeIs('clinic.staff.*') ? 'active' : '' }}">
                        <span class="las la-users"></span>
                        <span>{{ __('Staff') }}</span>
                    </a>
                </li>

                <!-- 5. Doctors/Therapists (Assign Therapists) -->
                <li>
                    <a href="{{ route('clinic.doctors.index') }}" class="{{ request()->routeIs('clinic.doctors.*') ? 'active' : '' }}">
                        <span class="las la-user-nurse"></span>
                        <span>{{ __('Doctors') }}</span>
                    </a>
                </li>

                <!-- 6. Services/Departments (Set Up Services) -->
                <li>
                    <a href="{{ route('clinic.departments.index') }}" class="{{ request()->routeIs('clinic.departments.*') ? 'active' : '' }}">
                        <span class="las la-hospital"></span>
                        <span>{{ __('Services') }}</span>
                    </a>
                </li>

                <!-- 7. Patients (Register Patients) -->
                <li>
                    <a href="{{ route('clinic.patients.index') }}" class="{{ request()->routeIs('clinic.patients.*') ? 'active' : '' }}">
                        <span class="las la-user-injured"></span>
                        <span>{{ __('Patients') }}</span>
                    </a>
                </li>

                <!-- 8. Clinical Episodes (Create Episodes for Patients) -->
                <li>
                    <a href="{{ route('clinic.episodes.index') }}" class="{{ request()->routeIs('clinic.episodes.*') ? 'active' : '' }}">
                        <span class="las la-notes-medical"></span>
                        <span>{{ __('Clinical Episodes') }}</span>
                    </a>
                </li>

                <!-- 8.5. Clinical Notes/EMR (Documentation) -->
                <li>
                    <a href="{{ route('clinic.clinical-notes.index') }}" class="{{ request()->routeIs('clinic.clinical-notes.*') ? 'active' : '' }}">
                        <span class="las la-file-medical"></span>
                        <span>{{ __('Clinical Notes (EMR)') }}</span>
                    </a>
                </li>

                <!-- 9. Treatment Programs (Create Weekly Programs) -->
                <li>
                    <a href="{{ route('clinic.programs.index') }}" class="{{ request()->routeIs('clinic.programs.*') ? 'active' : '' }}">
                        <span class="las la-clipboard-list"></span>
                        <span>{{ __('Treatment Programs') }}</span>
                    </a>
                </li>
                
                <!-- 10. Appointments (Schedule Appointments) -->
                <li>
                    <a href="{{ route('clinic.appointments.index') }}" class="{{ request()->routeIs('clinic.appointments.*') ? 'active' : '' }}">
                        <span class="las la-calendar-check"></span>
                        <span>{{ __('Appointments') }}</span>
                    </a>
                </li>

                <!-- 10.5. Waitlist (Manage Patient Waitlist) -->
                <li>
                    <a href="{{ route('clinic.waitlist.index') }}" class="{{ request()->routeIs('clinic.waitlist.*') ? 'active' : '' }}">
                        <span class="las la-list"></span>
                        <span>{{ __('Waitlist') }}</span>
                    </a>
                </li>

                <!-- 10.6. Intake Forms (Pre-Visit Questionnaires) -->
                <li>
                    <a href="{{ route('clinic.intake-forms.index') }}" class="{{ request()->routeIs('clinic.intake-forms.*') ? 'active' : '' }}">
                        <span class="las la-file-alt"></span>
                        <span>{{ __('Intake Forms') }}</span>
                    </a>
                </li>

                <!-- 11. Analytics (View Reports) -->
                <li>
                    <a href="{{ route('clinic.analytics.index') }}" class="{{ request()->routeIs('clinic.analytics.*') ? 'active' : '' }}">
                        <span class="las la-chart-bar"></span>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                </li>

                <!-- 12. Billing (Financial Management) -->
                <li>
                    <a href="{{ route('clinic.billing.index') }}" class="{{ request()->routeIs('clinic.billing.*') ? 'active' : '' }}">
                        <span class="las la-file-invoice-dollar"></span>
                        <span>{{ __('Billing') }}</span>
                    </a>
                </li>

                <!-- 12.5. Insurance Claims (RCM) -->
                <li>
                    <a href="{{ route('clinic.insurance-claims.index') }}" class="{{ request()->routeIs('clinic.insurance-claims.*') ? 'active' : '' }}">
                        <span class="las la-file-invoice-dollar"></span>
                        <span>{{ __('Insurance Claims') }}</span>
                    </a>
                </li>

                <!-- 13. Job System (Post Jobs - Secondary Feature) -->
                <li>
                    <a href="{{ route('clinic.jobs.index') }}" class="{{ request()->routeIs('clinic.jobs.*') ? 'active' : '' }}">
                        <span class="las la-briefcase"></span>
                        <span>{{ __('Job System') }}</span>
                    </a>
                </li>

                <!-- 14. Notifications (Alerts) -->
                <li>
                    <a href="{{ route('clinic.notifications.index') }}" class="{{ request()->routeIs('clinic.notifications.*') ? 'active' : '' }}">
                        <span class="las la-bell"></span>
                        <span>{{ __('Notifications') }}</span>
                    </a>
                </li>
            @endif

            <!-- Company Recruitment Dashboard (For Recruitment Companies) -->
            @if(auth()->user()->type === 'company' && !request()->routeIs('clinic.*'))
                <!-- 1. Dashboard -->
                <li>
                    <a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                        <span class="las la-chart-pie"></span>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- 2. Post New Job -->
                <li>
                    <a href="{{ route('company.jobs.create') }}" class="{{ request()->routeIs('company.jobs.create') ? 'active' : '' }}">
                        <span class="las la-plus-circle"></span>
                        <span>{{ __('Post New Job') }}</span>
                    </a>
                </li>

                <!-- 3. My Jobs -->
                <li>
                    <a href="{{ route('company.jobs.index') }}" class="{{ request()->routeIs('company.jobs.index') ? 'active' : '' }}">
                        <span class="las la-briefcase"></span>
                        <span>{{ __('My Jobs') }}</span>
                    </a>
                </li>

                <!-- 4. Applicants -->
                <li>
                    <a href="{{ route('company.jobs.index') }}#applicants" class="{{ request()->routeIs('company.jobs.applicants') || request()->routeIs('company.jobs.*applicants*') ? 'active' : '' }}">
                        <span class="las la-user-friends"></span>
                        <span>{{ __('Applicants') }}</span>
                    </a>
                </li>

                <!-- 5. Job Templates -->
                <li>
                    <a href="{{ route('company.jobs.templates') }}" class="{{ request()->routeIs('company.jobs.templates') ? 'active' : '' }}">
                        <span class="las la-file-alt"></span>
                        <span>{{ __('Job Templates') }}</span>
                    </a>
                </li>

                <!-- 6. Analytics -->
                <li>
                    <a href="{{ route('company.jobs.analytics') }}" class="{{ request()->routeIs('company.jobs.analytics') ? 'active' : '' }}">
                        <span class="las la-chart-bar"></span>
                        <span>{{ __('Analytics') }}</span>
                    </a>
                </li>

                <!-- 7. Profile & Settings -->
                <li>
                    <a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                        <span class="las la-cog"></span>
                        <span>{{ __('Profile & Settings') }}</span>
                    </a>
                </li>
            @endif

            <!-- Common Footer Links -->
            <li>
                <a href="{{ '/' . app()->getLocale() }}">
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
                {{-- Translation Button --}}
                <a href="{{ route('dashboard.locale.switch', ['locale' => app()->getLocale() == 'en' ? 'ar' : 'en']) }}" 
                   class="btn btn-sm btn-light mr-2 d-flex align-items-center" 
                   style="font-size: 13px; font-weight: 700; text-decoration: none;">
                    <i class="las la-globe mr-1"></i> {{ app()->getLocale() == 'en' ? 'AR' : 'EN' }}
                </a>
                
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
            <!-- Verification Status Sticky Bar -->
            @if(Auth::check() && in_array(auth()->user()->type, ['vendor', 'company', 'therapist']) && auth()->user()->verification_status !== 'approved')
                @php
                    $status = auth()->user()->verification_status ?? 'pending';
                    $barColor = $status === 'under_review' ? '#ff9800' : '#dc3545'; // Orange for under_review, Red for pending/rejected
                    $barText = $status === 'under_review' 
                        ? __('Your account is under review. We will notify you once verification is complete.') 
                        : __('Your account is not active. Upload required documents to activate your account and make it visible to users');
                    $barIcon = $status === 'under_review' ? 'la-hourglass-half' : 'la-exclamation-triangle';
                @endphp
                <div class="verification-bar mb-4" style="background-color: {{ $barColor }}; color: white; padding: 12px 20px; border-radius: 8px; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="las {{ $barIcon }} mr-2" style="font-size: 20px;"></i>
                            <span class="font-weight-bold">{{ $barText }}</span>
                        </div>
                            <a href="{{ route('verification.verification-center') }}" class="btn btn-sm btn-light ml-3" style="white-space: nowrap;">
                                <i class="las la-upload mr-1"></i> {{ $status === 'under_review' ? __('View Status') : __('Upload Documents') }}
                            </a>
                    </div>
                </div>
            @endif

            @if(Auth::check() && Auth::user()->isVerified() && session('account_approved'))
                <!-- Green Success Banner (shown once after approval) -->
                <div class="alert alert-success mb-4" style="border-left: 5px solid #28a745; background-color: #f0fff4;">
                    <i class="fas fa-check-circle mr-2"></i>
                    <strong>{{ __('Your account is active and visible to users!') }}</strong>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-left: 5px solid #28a745; background-color: #f0fff4;">
                    <i class="las la-check-circle mr-2"></i>
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

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

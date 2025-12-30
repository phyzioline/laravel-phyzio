<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="logo-name flex-grow-1 text-center fw-bold">
            <img src="{{ asset('dashboard/images/Frame 131.svg') }}" width="170px" alt="">
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>
    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">
            
            <!-- Home Visits Dashboard (Therapist's Main Role) -->
            <li class="menu-label">{{ __('Home Visits Dashboard') }}</li>
            <li>
                <a href="{{ route('therapist.dashboard') }}">
                    <div class="parent-icon"><i class="las la-home"></i></div>
                    <div class="menu-title">{{ __('Home Visits') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('therapist.home_visits.index') }}">
                    <div class="parent-icon"><i class="las la-calendar-check"></i></div>
                    <div class="menu-title">{{ __('My Visits') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('therapist.patients.index') }}">
                    <div class="parent-icon"><i class="las la-user-injured"></i></div>
                    <div class="menu-title">{{ __('My Patients') }}</div>
                </a>
            </li>
            <li>
                 <a href="{{ route('therapist.schedule.index') }}">
                    <div class="parent-icon"><i class="las la-clock"></i></div>
                    <div class="menu-title">{{ __('Schedule') }}</div>
                </a>
            </li>
             <li>
                <a href="{{ route('therapist.earnings.index') }}">
                    <div class="parent-icon"><i class="las la-wallet"></i></div>
                    <div class="menu-title">{{ __('My Earnings') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('therapist.profile.edit') }}">
                    <div class="parent-icon"><i class="las la-user-cog"></i></div>
                    <div class="menu-title">{{ __('Profile & Settings') }}</div>
                </a>
            </li>

            <!-- Courses Dashboard (Instructor Role) -->
            <li class="menu-label mt-3">{{ __('Instructor Portal') }}</li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="las la-graduation-cap"></i></div>
                    <div class="menu-title">{{ __('Courses Management') }}</div>
                </a>
                <ul>
                    <li> <a href="{{ route('instructor.dashboard') }}"><i class="las la-tachometer-alt"></i>{{ __('Dashboard') }}</a>
                    </li>
                    <li> <a href="{{ route('instructor.courses.create.' . app()->getLocale()) }}"><i class="las la-plus-circle"></i>{{ __('Create Course') }}</a>
                    </li>
                    <li> <a href="{{ route('instructor.courses.index.' . app()->getLocale()) }}"><i class="las la-book"></i>{{ __('My Courses') }}</a>
                    </li>
                    {{-- Students link removed - functionality not implemented --}}
                    <li> <a href="{{ route('therapist.earnings.index') }}"><i class="las la-coins"></i>{{ __('Earnings') }}</a>
                    </li>
                </ul>
            </li>

            <!-- Clinic Dashboard (Clinic Role) -->
            <li class="menu-label mt-3">{{ __('Clinic Dashboard') }}</li>
            <li>
                 <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="las la-hospital"></i></div>
                    <div class="menu-title">{{ __('Clinic Portal') }}</div>
                </a>
                <ul>
                    <li> <a href="{{ route('clinic.dashboard') }}"><i class="las la-tachometer-alt"></i>{{ __('Overview') }}</a>
                    </li>
                     {{-- Doctors link removed - functionality not implemented --}}
                     {{-- Appointments link removed - functionality not implemented --}}
                </ul>
            </li>
             
             <li class="menu-label mt-3">{{ __('System') }}</li>
             <li>
                <a href="{{ route('therapist.notifications.index') }}">
                    <div class="parent-icon"><i class="las la-bell"></i></div>
                    <div class="menu-title">{{ __('Notifications') }}</div>
                </a>
            </li>
             <li>
                <a href="{{ url('/') }}">
                    <div class="parent-icon"><i class="las la-globe"></i></div>
                    <div class="menu-title">{{ __('Back to Website') }}</div>
                </a>
            </li>
        </ul>

        <div id="footer-section" style="padding-top: 50px;" class="text-center">
            <p class="mb-1 p-0">Copyright © 2024. All right reserved.</p>
        </div>
    </div>
</aside>

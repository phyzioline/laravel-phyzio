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
            <!-- Dashboard Home -->
            <li>
                <a href="{{ route('therapist.dashboard') }}">
                    <div class="parent-icon"><i class="las la-home"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <a href="{{ route('therapist.appointments.index') }}">
                    <div class="parent-icon"><i class="las la-calendar-check"></i></div>
                    <div class="menu-title">Appointments</div>
                </a>
            </li>

            <!-- Patients (New) -->
            <li>
                <a href="{{ route('therapist.patients.index') }}">
                    <div class="parent-icon"><i class="las la-user-injured"></i></div>
                    <div class="menu-title">Patients</div>
                </a>
            </li>

            <!-- Schedule (New) -->
            <li>
                <a href="{{ route('therapist.schedule.index') }}">
                    <div class="parent-icon"><i class="las la-clock"></i></div>
                    <div class="menu-title">Schedule</div>
                </a>
            </li>

             <!-- Earnings (New) -->
            <li>
                <a href="{{ route('therapist.earnings.index') }}">
                    <div class="parent-icon"><i class="las la-wallet"></i></div>
                    <div class="menu-title">Earnings</div>
                </a>
            </li>

            <!-- Profile -->
            <li>
                <a href="{{ route('therapist.profile.edit') }}">
                    <div class="parent-icon"><i class="las la-user"></i></div>
                    <div class="menu-title">Profile</div>
                </a>
            </li>

            <!-- Notifications (New) -->
             <li>
                <a href="{{ route('therapist.notifications.index') }}">
                    <div class="parent-icon"><i class="las la-bell"></i> <span class="badge badge-danger ml-auto">5</span></div>
                    <div class="menu-title">Notifications</div>
                </a>
            </li>

            <!-- Dashboards (for navigation ease based on previous sidebar) -->
             <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="las la-layer-group"></i></div>
                    <div class="menu-title">Dashboards</div>
                </a>
                <ul>
                    <li> <a href="{{ route('clinic.dashboard') }}"><i class="las la-hospital"></i>Clinic Dashboard</a>
                    </li>
                     <!-- Add other dashboards if needed -->
                </ul>
            </li>

        </ul>

        <div id="footer-section" style="padding-top: 50px;" class="text-center">
            <p class="mb-1 p-0">Copyright © 2024. All right reserved.</p>
        </div>
    </div>
</aside>

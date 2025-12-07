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
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <!-- Profile -->
            <li>
                <a href="{{ route('therapist.profile.edit') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
                    <div class="menu-title">My Profile</div>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <a href="{{ route('therapist.appointments.index') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">calendar_today</i></div>
                    <div class="menu-title">Appointments</div>
                </a>
            </li>

            <!-- Availability -->
            <li>
                <a href="{{ route('therapist.availability.edit') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">schedule</i></div>
                    <div class="menu-title">Availability</div>
                </a>
            </li>

        </ul>

        <div id="footer-section" style="padding-top: 50px;" class="text-center">
            <p class="mb-1 p-0">Copyright © 2024. All right reserved.</p>
        </div>
    </div>
</aside>

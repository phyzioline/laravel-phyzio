@extends('web.therapist.layout')

@section('header_title', 'Therapist Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Key Performance Cards -->
    <div class="col-md-3">
        <div class="card-box text-center bg-teal text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); color: white;">
            <i class="las la-calendar-check d-block mb-2" style="font-size: 2.5rem;"></i>
            <h2 class="text-white">12</h2>
            <p class="text-white-50">Today's Appointments</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-box text-center">
            <i class="las la-users d-block mb-2 text-primary" style="font-size: 2.5rem;"></i>
            <h2>45</h2>
            <p class="text-muted">Active Patients</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-box text-center">
            <i class="las la-file-medical-alt d-block mb-2 text-warning" style="font-size: 2.5rem;"></i>
            <h2>5</h2>
            <p class="text-muted">Pending Notes</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-box text-center">
            <i class="las la-wallet d-block mb-2 text-success" style="font-size: 2.5rem;"></i>
            <h2>$1,250</h2>
            <p class="text-muted">Monthly Earnings</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions Panel -->
    <div class="col-md-12 mb-4">
        <div class="card-box">
            <h5 class="mb-3 border-bottom pb-2">Quick Actions</h5>
            <div class="d-flex justify-content-between flex-wrap">
                <a href="#" class="btn btn-outline-primary m-1 flex-fill py-3">
                    <i class="las la-plus-circle d-block mb-1" style="font-size: 1.5rem;"></i>
                    New Appointment
                </a>
                <a href="#" class="btn btn-outline-success m-1 flex-fill py-3">
                    <i class="las la-play-circle d-block mb-1" style="font-size: 1.5rem;"></i>
                    Start Evaluation
                </a>
                <a href="#" class="btn btn-outline-info m-1 flex-fill py-3">
                    <i class="las la-file-signature d-block mb-1" style="font-size: 1.5rem;"></i>
                    Add Session Note
                </a>
                <a href="#" class="btn btn-outline-secondary m-1 flex-fill py-3">
                    <i class="las la-clipboard-list d-block mb-1" style="font-size: 1.5rem;"></i>
                    Treatment Plan
                </a>
                <a href="#" class="btn btn-outline-dark m-1 flex-fill py-3">
                    <i class="las la-home d-block mb-1" style="font-size: 1.5rem;"></i>
                    Home Visit
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Appointments Timeline -->
    <div class="col-md-8">
        <div class="card-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Today's Schedule</h5>
                <a href="#" class="btn btn-sm btn-light">View Calendar</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>09:00 AM</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">JD</div>
                                    <div>
                                        <div class="fw-bold">John Doe</div>
                                        <small class="text-muted">#PT-1001</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-primary text-primary">Evaluation</span></td>
                            <td><span class="badge bg-soft-success text-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Start</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>10:30 AM</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">AS</div>
                                    <div>
                                        <div class="fw-bold">Sarah Smith</div>
                                        <small class="text-muted">#PT-1005</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-info text-info">Manual Therapy</span></td>
                            <td><span class="badge bg-soft-warning text-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-dark">Check-in</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>02:00 PM</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">MK</div>
                                    <div>
                                        <div class="fw-bold">Mike Johnson</div>
                                        <small class="text-muted">#PT-1012</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-secondary text-secondary">Exercise</span></td>
                            <td><span class="badge bg-soft-success text-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-dark">Check-in</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Pending Tasks & Stats -->
    <div class="col-md-4">
        <div class="card-box">
            <h5 class="mb-3">Pending Tasks</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="las la-exclamation-circle text-warning me-2"></i>
                        Complete Note: John Doe
                    </div>
                    <a href="#" class="btn btn-xs btn-outline-primary">Open</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="las la-clock text-danger me-2"></i>
                        Confirm Session: 10:30 AM
                    </div>
                    <a href="#" class="btn btn-xs btn-outline-success">View</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="las la-file-upload text-info me-2"></i>
                        Upload MRI: Mike Johnson
                    </div>
                    <a href="#" class="btn btn-xs btn-outline-secondary">Upload</a>
                </li>
            </ul>
        </div>

        <div class="card-box mt-3">
            <h5 class="mb-3">Weekly Goal</h5>
            <div class="d-flex justify-content-between mb-1">
                <span>Sessions Completed</span>
                <span class="fw-bold">12/20</span>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="mt-3 text-center">
                <small class="text-muted">Keep it up! You are on track.</small>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('web.layouts.dashboard_master')

@section('title', 'Doctor Profile')
@section('header_title', 'Doctor Profile')

@section('content')
<div class="row">
    <!-- Sidebar / Info -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                 <div class="avatar-circle rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                    <i class="las la-user-md text-muted" style="font-size: 60px;"></i>
                </div>
                <h4 class="font-weight-bold mb-1">{{ $doctor->name }}</h4>
                <p class="text-muted">{{ $doctor->specialty }}</p>
                <div class="badge badge-success px-3 py-2 mt-2">{{ $doctor->status }}</div>
                
                <div class="d-flex justify-content-center mt-4">
                    <a href="#" class="btn btn-outline-primary btn-sm mx-1"><i class="las la-envelope"></i> Message</a>
                    <a href="#" class="btn btn-outline-success btn-sm mx-1"><i class="las la-phone"></i> Call</a>
                </div>
            </div>
             <div class="card-footer bg-white border-0 pb-4">
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <h5 class="font-weight-bold mb-0">{{ $doctor->patients }}</h5>
                        <small class="text-muted">Patients</small>
                    </div>
                    <div class="col-6">
                         <h5 class="font-weight-bold mb-0">4.8</h5>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">Contact Information</h6>
                <div class="d-flex align-items-center mb-2">
                    <i class="las la-envelope text-muted mr-2"></i> {{ $doctor->email }}
                </div>
                 <div class="d-flex align-items-center">
                    <i class="las la-phone text-muted mr-2"></i> {{ $doctor->phone }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
     <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Professional Bio</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $doctor->bio }}</p>
            </div>
        </div>
        
         <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="font-weight-bold mb-0">Recent Activity</h5>
                <button class="btn btn-sm btn-link">View All</button>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <!-- Item 1 -->
                    <div class="pb-3 border-left pl-4 position-relative ml-2">
                        <div class="position-absolute bg-primary rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 5px;"></div>
                        <h6 class="font-weight-bold mb-1">Completed Session</h6>
                        <p class="text-muted small mb-0">Session with Patient Mark Twains completed.</p>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                     <!-- Item 2 -->
                    <div class="pb-3 border-left pl-4 position-relative ml-2">
                        <div class="position-absolute bg-info rounded-circle" style="width: 12px; height: 12px; left: -6px; top: 5px;"></div>
                        <h6 class="font-weight-bold mb-1">New Appointment</h6>
                        <p class="text-muted small mb-0">Scheduled with Patient Sarah Connor for tomorrow.</p>
                        <small class="text-muted">5 hours ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

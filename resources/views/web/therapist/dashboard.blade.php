@extends('web.therapist.layout')

@section('header_title', 'Therapist Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1>12</h1>
            <p class="text-muted">Total Appointments</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1>4.8</h1>
            <p class="text-muted">Average Rating</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box text-center">
            <h1>Active</h1>
            <p class="text-muted">Current Status</p>
        </div>
    </div>
</div>

<div class="card-box">
    <h5>Recent Appointments</h5>
    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">No recent appointments.</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

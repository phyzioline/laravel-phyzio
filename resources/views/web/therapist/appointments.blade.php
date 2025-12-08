@extends('web.therapist.layout')

@section('header_title', 'My Appointments')

@section('content')
<div class="card-box">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through appointments -->
            <tr>
                <td colspan="5" class="text-center">No appointments found.</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

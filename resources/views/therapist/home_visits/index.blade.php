@extends('therapist.layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="mb-3">My Appointments</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->appointment_date->format('Y-m-d') }}</td>
                        <td>{{ $appointment->appointment_time->format('H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $appointment->patient->profile_photo_url ?? asset('dashboard/images/Frame 127.svg') }}" class="rounded-circle" width="30" height="30" alt="">
                                <div>{{ $appointment->patient->name }}</div>
                            </div>
                        </td>
                        <td>
                            @if($appointment->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($appointment->status == 'confirmed')
                                <span class="badge bg-info text-dark">Confirmed</span>
                            @elseif($appointment->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($appointment->status == 'pending')
                                    <form action="{{ route('therapist.home_visits.status', $appointment->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button class="btn btn-sm btn-success">Accept</button>
                                    </form>
                                    <form action="{{ route('therapist.home_visits.status', $appointment->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @elseif($appointment->status == 'confirmed')
                                    <form action="{{ route('therapist.home_visits.status', $appointment->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <button class="btn btn-sm btn-primary">Complete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No appointments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $appointments->links() }}
    </div>
</div>
@endsection

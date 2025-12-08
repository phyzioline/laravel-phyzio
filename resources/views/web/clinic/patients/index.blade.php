@extends('web.therapist.layout')

@section('header_title', 'Patient Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Patients</h2>
    <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary"><i class="las la-user-plus"></i> Add New Patient</a>
</div>

<div class="card-box">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td><strong>{{ $patient->full_name }}</strong></td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->email ?? 'N/A' }}</td>
                    <td>{{ ucfirst($patient->gender ?? 'N/A') }}</td>
                    <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="las la-eye"></i> View</button>
                        <button class="btn btn-sm btn-warning"><i class="las la-edit"></i> Edit</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No patients found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="mt-3">
        {{ $patients->links() }}
    </div>
</div>
@endsection

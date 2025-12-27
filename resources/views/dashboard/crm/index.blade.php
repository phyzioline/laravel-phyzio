@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">CRM - Patients</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Clinic</th>
                                <th>Phone</th>
                                <th>Diagnosis</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                            <tr>
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->full_name }}</td>
                                <td>{{ $patient->clinic->name ?? 'N/A' }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td>{{ $patient->medical_history }}</td>
                                <td>
                                    <a href="{{ route('dashboard.crm.show', $patient->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No patients found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Clinic Profiles</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Clinics</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Clinic Name</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tbody>
                        @foreach($clinic_profiles as $clinic)
                        <tr>
                            <td>{{ $clinic->id }}</td>
                            <td>{{ $clinic->name }}</td>
                            <td>{{ $clinic->plan ?? 'Standard' }}</td>
                            <td>
                                <span class="badge badge-{{ $clinic->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($clinic->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

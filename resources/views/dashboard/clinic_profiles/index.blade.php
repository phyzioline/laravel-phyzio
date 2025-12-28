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
                        @forelse($clinic_profiles as $clinic)
                        <tr>
                            <td>{{ $clinic->id }}</td>
                            <td>
                                <strong>{{ $clinic->name }}</strong>
                                @if(isset($clinic->company_name))
                                    <br><small class="text-muted">Company: {{ $clinic->company_name }}</small>
                                @endif
                            </td>
                            <td>{{ $clinic->plan }}</td>
                            <td>
                                <span class="badge badge-{{ $clinic->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($clinic->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('clinic.dashboard') }}?clinic_id={{ $clinic->id }}" class="btn btn-info btn-sm" title="View Clinic Dashboard">
                                    <i class="las la-eye"></i> View
                                </a>
                                @if($clinic->status == 'inactive')
                                    <form action="{{ route('dashboard.clinic_profiles.update', $clinic->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="activate">
                                        <button type="submit" class="btn btn-success btn-sm" title="Activate Clinic">
                                            <i class="las la-check"></i> Activate
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="las la-clinic fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No clinics found.</p>
                                <p class="text-muted">Clinics will appear here once they register.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Therapist Profiles</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Therapists</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profiles as $profile)
                        <tr>
                            <td>{{ $profile->id }}</td>
                            <td>{{ $profile->user->name ?? 'N/A' }} <br><small>{{ $profile->user->email ?? '' }}</small></td>
                            <td>{{ $profile->specialization }}</td>
                            <td>
                                <span class="badge badge-{{ $profile->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ ucfirst($profile->status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('dashboard.therapist_profiles.update', $profile->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    @if($profile->status != 'approved')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    @else
                                        <input type="hidden" name="status" value="pending">
                                        <button class="btn btn-warning btn-sm">Suspend</button>
                                    @endif
                                </form>
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

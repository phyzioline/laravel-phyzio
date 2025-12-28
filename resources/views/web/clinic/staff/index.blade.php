@extends('web.layouts.dashboard_master')

@section('title', 'Staff')
@section('header_title', 'Staff Directory')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('All Staff Members') }}</h5>
        <a href="{{ route('clinic.staff.create') }}" class="btn btn-primary btn-sm"><i class="las la-user-plus"></i> Add Staff</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Name</th>
                        <th class="border-0">Role</th>
                        <th class="border-0">Contact</th>
                        <th class="border-0">Status</th>
                        <th class="border-0 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle rounded-circle bg-light mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <span class="font-weight-bold">{{ $member->name }}</span>
                            </div>
                        </td>
                        <td>{{ $member->role }}</td>
                        <td>
                            <div class="small">{{ $member->email }}</div>
                            <div class="small text-muted">{{ $member->phone }}</div>
                        </td>
                        <td>
                             <span class="badge {{ $member->status == 'Active' ? 'badge-success' : 'badge-secondary' }}">{{ $member->status }}</span>
                        </td>
                        <td class="text-right">
                            <button class="btn btn-sm btn-link text-muted"><i class="las la-edit"></i></button>
                            <button class="btn btn-sm btn-link text-danger"><i class="las la-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="las la-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No staff members found.</p>
                            <a href="{{ route('clinic.staff.create') }}" class="btn btn-primary btn-sm">
                                <i class="las la-user-plus"></i> Add Your First Staff Member
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

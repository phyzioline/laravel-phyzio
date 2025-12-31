@extends('web.layouts.dashboard_master')

@section('title', 'Staff')
@section('header_title', 'Staff Directory')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('All Staff Members') }}</h5>
        <div class="d-flex align-items-center gap-2">
            <!-- Status Filter -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('clinic.staff.index', ['status' => 'all']) }}" 
                   class="btn btn-sm {{ request('status', 'all') == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All
                </a>
                <a href="{{ route('clinic.staff.index', ['status' => 'active']) }}" 
                   class="btn btn-sm {{ request('status') == 'active' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Active
                </a>
                <a href="{{ route('clinic.staff.index', ['status' => 'inactive']) }}" 
                   class="btn btn-sm {{ request('status') == 'inactive' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Inactive
                </a>
            </div>
            <a href="{{ route('clinic.staff.create') }}" class="btn btn-primary btn-sm"><i class="las la-user-plus"></i> Add Staff</a>
        </div>
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
                            <span class="badge {{ $member->status == 'Active' ? 'badge-success' : 'badge-secondary' }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Toggle Status Button -->
                                <form action="{{ route('clinic.staff.toggle-status', $member->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm {{ $member->is_active ? 'btn-warning' : 'btn-success' }}" 
                                            title="{{ $member->is_active ? 'Deactivate' : 'Activate' }}"
                                            onclick="return confirm('Are you sure you want to {{ $member->is_active ? 'deactivate' : 'activate' }} this staff member?');">
                                        <i class="las {{ $member->is_active ? 'la-ban' : 'la-check-circle' }}"></i>
                                    </button>
                                </form>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('clinic.staff.edit', $member->id) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Edit">
                                    <i class="las la-edit"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('clinic.staff.destroy', $member->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to permanently delete this staff member? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger" 
                                            title="Delete">
                                        <i class="las la-trash"></i>
                                    </button>
                                </form>
                            </div>
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

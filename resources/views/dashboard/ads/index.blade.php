@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* AI & Modules Pages - Full Width (100%) ignoring header and sidebar */
    .page-wrapper {
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-top: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .main-content {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Content area should account for header and sidebar */
    .ai-modules-content {
        margin-left: 250px; /* Sidebar width */
        margin-top: 70px; /* Header height */
        padding: 20px;
        width: calc(100% - 250px);
        min-height: calc(100vh - 70px);
    }
    
    /* RTL Support */
    [dir="rtl"] .ai-modules-content {
        margin-left: 0;
        margin-right: 250px;
    }
    
    /* When sidebar is toggled */
    body.toggled .ai-modules-content {
        margin-left: 0;
        width: 100%;
    }
    
    [dir="rtl"] body.toggled .ai-modules-content {
        margin-right: 0;
    }
</style>
@endpush

@section('content')
<div class="ai-modules-content">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ads Manager</h5>
                <a href="{{ route('dashboard.ads.create') }}" class="btn btn-primary btn-sm">Create Ad</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Impressions</th>
                                <th>Clicks</th>
                                <th>CTR</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ads as $ad)
                            <tr>
                                <td>{{ $ad->id }}</td>
                                <td>{{ $ad->title }}</td>
                                <td>{{ $ad->impressions }}</td>
                                <td>{{ $ad->clicks }}</td>
                                <td>{{ $ad->ctr }}%</td>
                                <td>
                                    <span class="badge bg-{{ $ad->is_active ? 'success' : 'danger' }}">
                                        {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.ads.edit', $ad->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('dashboard.ads.destroy', $ad->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No ads found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $ads->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection

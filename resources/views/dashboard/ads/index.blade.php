@extends('dashboard.layouts.app')

@section('content')
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
@endsection

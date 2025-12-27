@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Treatment Plans</h5>
                <a href="{{ route('dashboard.plans.create') }}" class="btn btn-primary btn-sm">Create Plan</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Diagnosis</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->patient->name ?? 'Unknown' }}</td>
                                <td>{{ $plan->diagnosis }}</td>
                                <td>{{ ucfirst($plan->status) }}</td>
                                <td>{{ $plan->start_date->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('dashboard.plans.show', $plan->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No treatment plans found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $plans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

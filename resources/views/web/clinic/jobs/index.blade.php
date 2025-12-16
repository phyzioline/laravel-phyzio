@extends('web.layouts.dashboard_master')

@section('title', 'Job System')
@section('header_title', 'Job System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Posted Jobs & Training</h4>
            <a href="{{ route('clinic.jobs.create') }}" class="btn btn-primary"><i class="las la-plus"></i> Post New Job</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Salary/Stipend</th>
                                <th>Posted At</th>
                                <th>Applicants</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $job->type == 'job' ? 'success' : 'info' }}">
                                        {{ ucfirst($job->type) }}
                                    </span>
                                </td>
                                <td>{{ $job->location ?? 'Remote' }}</td>
                                <td>{{ $job->salary_range ?? 'N/A' }}</td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($job->applications_count > 0)
                                        <a href="{{ route('clinic.jobs.applicants', $job->id) }}" class="badge badge-primary px-2 py-1">
                                            {{ $job->applications_count }} Applicants
                                        </a>
                                    @else
                                        <span class="badge badge-secondary">0 Applicants</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('clinic.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete"><i class="las la-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="las la-briefcase text-muted" style="font-size: 48px;"></i>
                                    <p class="mt-3">No jobs posted yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

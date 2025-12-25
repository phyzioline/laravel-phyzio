@extends('web.layouts.dashboard_master')

@section('title', 'Job Applicants')
@section('header_title', 'Job Applicants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Applicants for: {{ $job->title }}</h4>
                <p class="text-muted"><i class="las la-clock"></i> Posted: {{ $job->created_at->diffForHumans() }}</p>
            </div>
            <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-secondary"><i class="las la-arrow-left"></i> Back to Jobs</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Therapist</th>
                                <th>Experience</th>
                                <th>Match Score</th>
                                <th>Status</th>
                                <th>Applied At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($job->applications as $app)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $app->therapist->image ? asset($app->therapist->image) : asset('default/default.png') }}" class="rounded-circle mr-3" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $app->therapist->name }}</h6>
                                            <small class="text-muted">{{ $app->therapist->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $app->therapist->therapistProfile->years_experience ?? 'N/A' }} Years
                                </td>
                                <td>
                                    @if($app->match_score >= 80)
                                        <span class="badge badge-success">{{ $app->match_score }}%</span>
                                    @elseif($app->match_score >= 50)
                                        <span class="badge badge-warning">{{ $app->match_score }}%</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $app->match_score ?? 'N/A' }}%</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('company.jobs.updateApplicationStatus', [$job->id, $app->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="reviewed" {{ $app->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                            <option value="interviewed" {{ $app->status == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                            <option value="hired" {{ $app->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                            <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $app->created_at->format('M d, H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" title="View Profile"><i class="las la-eye"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted">No applicants yet.</p>
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


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
                <!-- Bulk Actions -->
                <form id="bulkActionForm" action="{{ route('company.jobs.bulkUpdateApplications', $job->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" onclick="selectAll()">Select All</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">Deselect All</button>
                        </div>
                        <div class="d-flex gap-2">
                            <select name="status" class="form-control form-control-sm" required>
                                <option value="">Bulk Action</option>
                                <option value="reviewed">Mark as Reviewed</option>
                                <option value="interviewed">Mark as Interviewed</option>
                                <option value="hired">Mark as Hired</option>
                                <option value="rejected">Mark as Rejected</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Apply</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)"></th>
                                <th>Therapist</th>
                                <th>Experience</th>
                                <th>Match Score</th>
                                <th>Status</th>
                                <th>Applied At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($job->applications as $app)
                            <tr>
                                <td>
                                    <input type="checkbox" name="application_ids[]" value="{{ $app->id }}" class="application-checkbox">
                                </td>
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
                                    <button class="btn btn-sm btn-info" title="View Profile" onclick="viewProfile({{ $app->therapist->id }})"><i class="las la-eye"></i></button>
                                    <button class="btn btn-sm btn-primary" title="Schedule Interview" data-toggle="modal" data-target="#interviewModal{{ $app->id }}"><i class="las la-calendar"></i></button>
                                    
                                    <!-- Interview Modal -->
                                    <div class="modal fade" id="interviewModal{{ $app->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Schedule Interview</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <form action="{{ route('company.jobs.scheduleInterview', [$job->id, $app->id]) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Date & Time</label>
                                                            <input type="datetime-local" name="scheduled_at" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Interview Type</label>
                                                            <select name="interview_type" class="form-control" required onchange="toggleInterviewFields(this, {{ $app->id }})">
                                                                <option value="online">Online</option>
                                                                <option value="in-person">In-Person</option>
                                                                <option value="phone">Phone</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" id="locationField{{ $app->id }}" style="display:none;">
                                                            <label>Location</label>
                                                            <input type="text" name="location" class="form-control" placeholder="Enter interview location">
                                                        </div>
                                                        <div class="form-group" id="meetingLinkField{{ $app->id }}">
                                                            <label>Meeting Link</label>
                                                            <input type="url" name="meeting_link" class="form-control" placeholder="https://meet.google.com/...">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Notes</label>
                                                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Schedule Interview</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted">No applicants yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            function toggleAll(checkbox) {
                const checkboxes = document.querySelectorAll('.application-checkbox');
                checkboxes.forEach(cb => cb.checked = checkbox.checked);
            }

            function selectAll() {
                document.querySelectorAll('.application-checkbox').forEach(cb => cb.checked = true);
                document.getElementById('selectAllCheckbox').checked = true;
            }

            function deselectAll() {
                document.querySelectorAll('.application-checkbox').forEach(cb => cb.checked = false);
                document.getElementById('selectAllCheckbox').checked = false;
            }

            function toggleInterviewFields(select, appId) {
                const locationField = document.getElementById('locationField' + appId);
                const meetingLinkField = document.getElementById('meetingLinkField' + appId);
                
                if (select.value === 'in-person') {
                    locationField.style.display = 'block';
                    locationField.querySelector('input').required = true;
                    meetingLinkField.style.display = 'none';
                    meetingLinkField.querySelector('input').required = false;
                } else if (select.value === 'online') {
                    locationField.style.display = 'none';
                    locationField.querySelector('input').required = false;
                    meetingLinkField.style.display = 'block';
                    meetingLinkField.querySelector('input').required = true;
                } else {
                    locationField.style.display = 'none';
                    locationField.querySelector('input').required = false;
                    meetingLinkField.style.display = 'none';
                    meetingLinkField.querySelector('input').required = false;
                }
            }

            function viewProfile(therapistId) {
                // Implement profile view functionality
                window.open('/therapist/' + therapistId, '_blank');
            }
        </script>
    </div>
</div>
@endsection


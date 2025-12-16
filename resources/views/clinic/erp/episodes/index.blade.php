@extends('web.layouts.dashboard_master')

@section('title', __('Clinical ERP'))
@section('header_title', __('Clinical Episodes'))

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">{{ __('Active Episodes of Care') }}</h4>
                <p class="text-muted">{{ __('Manage long-term treatment plans') }}</p>
            </div>
            <a href="{{ route('clinic.episodes.create') }}" class="btn btn-primary"><i class="las la-plus-circle"></i> {{ __('Start New Episode') }}</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('Patient') }}</th>
                            <th>{{ __('Specialty') }}</th>
                            <th>{{ __('Diagnosis') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($episodes as $episode)
                        <tr>
                            <td>
                                <div class="font-weight-bold">{{ $episode->patient->name ?? 'Unknown Patient' }}</div>
                                <small class="text-muted">Therapist: {{ $episode->primaryTherapist->name ?? 'Unassigned' }}</small>
                            </td>
                            <td>
                                @if($episode->specialty == 'orthopedic') <span class="badge badge-info mb-1"><i class="las la-bone"></i> Ortho</span>
                                @elseif($episode->specialty == 'neurological') <span class="badge badge-warning mb-1"><i class="las la-brain"></i> Neuro</span>
                                @elseif($episode->specialty == 'pediatric') <span class="badge badge-success mb-1"><i class="las la-baby"></i> Peds</span>
                                @else <span class="badge badge-secondary mb-1">{{ ucfirst($episode->specialty) }}</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($episode->chief_complaint, 30) }}</td>
                            <td>{{ $episode->start_date->format('M d, Y') }}</td>
                            <td><span class="badge badge-dot badge-success"></span> {{ ucfirst($episode->status) }}</td>
                            <td>
                                <a href="{{ route('clinic.episodes.show', $episode->id) }}" class="btn btn-sm btn-light border">{{ __('Open Chart') }} <i class="las la-notes-medical"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="las la-folder-open display-4 text-muted"></i>
                                <p class="mt-2">{{ __('No active episodes. Start a new intake.') }}</p>
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

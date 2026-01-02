@extends('web.layouts.dashboard_master')

@section('title', __('Outcome Tracking'))
@section('header_title', __('Outcome Tracking'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">{{ __('Outcome Tracking') }}</h4>
                <p class="text-muted mb-0">{{ __('Episode') }}: {{ $episode->title }}</p>
            </div>
            <a href="{{ route('clinic.episodes.show', $episode) }}" class="btn btn-secondary">
                <i class="las la-arrow-left"></i> {{ __('Back to Episode') }}
            </a>
        </div>
    </div>
</div>

<!-- Outcome Measures Overview -->
@if($outcomeMeasures->count() > 0)
<div class="row mb-4">
    @foreach($outcomeMeasures as $measureName => $measures)
        @php
            $latest = $measures->last();
            $baseline = $measures->first();
            $improvement = $baseline && $latest ? 
                (($latest->total_score ?? $latest->percentage ?? 0) - ($baseline->total_score ?? $baseline->percentage ?? 0)) : 0;
            $improvementPercent = $baseline && ($baseline->total_score ?? $baseline->percentage ?? 0) > 0 ? 
                ($improvement / ($baseline->total_score ?? $baseline->percentage ?? 1)) * 100 : 0;
        @endphp
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-2">{{ $measureName }}</h6>
                    <div class="mb-2">
                        <small class="text-muted">{{ __('Baseline') }}:</small>
                        <strong>{{ number_format($baseline->total_score ?? $baseline->percentage ?? 0, 1) }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">{{ __('Latest') }}:</small>
                        <strong>{{ number_format($latest->total_score ?? $latest->percentage ?? 0, 1) }}</strong>
                        @if($improvement > 0)
                            <span class="badge badge-success ml-2">+{{ number_format($improvement, 1) }}</span>
                        @elseif($improvement < 0)
                            <span class="badge badge-danger ml-2">{{ number_format($improvement, 1) }}</span>
                        @endif
                    </div>
                    @if($improvementPercent != 0)
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar {{ $improvementPercent > 0 ? 'bg-success' : 'bg-danger' }}" 
                             style="width: {{ abs($improvementPercent) }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $improvementPercent > 0 ? '+' : '' }}{{ number_format($improvementPercent, 1) }}% {{ __('change') }}
                    </small>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('clinic.episodes.outcome-tracking.measure', [$episode->id, urlencode($measureName)]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="las la-chart-line"></i> {{ __('View Chart') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Progression Charts -->
<div class="row mb-4">
    @foreach($chartData as $measureName => $data)
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">{{ $measureName }}</h5>
            </div>
            <div class="card-body">
                <canvas id="chart-{{ md5($measureName) }}" height="100"></canvas>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body text-center py-5">
        <i class="las la-chart-line fa-3x text-muted mb-3"></i>
        <p class="text-muted">{{ __('No outcome measures recorded yet') }}</p>
        <p class="text-muted small">{{ __('Outcome measures will appear here after assessments are completed') }}</p>
    </div>
</div>
@endif

<!-- Assessment Timeline -->
@if($assessments->count() > 0)
<div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="font-weight-bold mb-0">{{ __('Assessment Timeline') }}</h5>
    </div>
    <div class="card-body px-4">
        <div class="timeline">
            @foreach($assessments as $assessment)
            <div class="timeline-item mb-4">
                <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                         style="width: 40px; height: 40px; min-width: 40px;">
                        <i class="las la-stethoscope"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="font-weight-bold mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $assessment->type)) }} {{ __('Assessment') }}
                                </h6>
                                <p class="text-muted mb-1 small">
                                    {{ $assessment->assessment_date->format('F d, Y') }}
                                </p>
                            </div>
                            <span class="badge badge-{{ $assessment->type == 'initial' ? 'primary' : ($assessment->type == 'discharge' ? 'success' : 'info') }}">
                                {{ ucfirst($assessment->type) }}
                            </span>
                        </div>
                        @if($assessment->outcomeMeasures->count() > 0)
                        <div class="mt-2">
                            @foreach($assessment->outcomeMeasures as $measure)
                            <span class="badge badge-light mr-1">
                                {{ $measure->measure_name }}: {{ number_format($measure->total_score ?? $measure->percentage ?? 0, 1) }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($chartData as $measureName => $data)
    const ctx{{ md5($measureName) }} = document.getElementById('chart-{{ md5($measureName) }}');
    if (ctx{{ md5($measureName) }}) {
        new Chart(ctx{{ md5($measureName) }}, {
            type: 'line',
            data: {
                labels: {!! json_encode($data['labels']) !!},
                datasets: [{
                    label: '{{ $measureName }}',
                    data: {!! json_encode($data['data']) !!},
                    borderColor: '#00897b',
                    backgroundColor: 'rgba(0, 137, 123, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    @endforeach
});
</script>
@endpush


@extends('web.layouts.dashboard_master')

@section('title', __('Outcome Progression') . ' - ' . $measureName)
@section('header_title', __('Progression Analysis'))

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('clinic.outcome-tracking.index', $episode->id) }}" class="btn btn-secondary btn-sm">
                    <i class="las la-arrow-left"></i> {{ __('Back to Overview') }}
                </a>
                <h4 class="font-weight-bold mt-2 mb-0">{{ $measureName }} {{ __('Progression') }}</h4>
            </div>
            <div class="text-end text-muted font-weight-bold">
                {{ __('Episode') }}: {{ $episode->episode_number }}<br>
                {{ __('Patient') }}: {{ $episode->patient->full_name ?? '-' }}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Score Progression Chart -->
    <div class="col-lg-12 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Score Trend') }}</h5>
            </div>
            <div class="card-body">
                <div style="height: 350px;">
                    <canvas id="measureChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="col-lg-12 mt-2">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Recorded Scores') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>{{ __('Assessment Date') }}</th>
                                <th>{{ __('Raw Score') }}</th>
                                <th>{{ __('Percentage') }}</th>
                                <th>{{ __('Comments') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($measures as $measure)
                            <tr>
                                <td>{{ $measure->assessed_at ? $measure->assessed_at->format('F d, Y') : '-' }}</td>
                                <td class="font-weight-bold">{{ $measure->total_score ?? '-' }}</td>
                                <td>
                                    @if($measure->percentage)
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $measure->percentage }}%;"></div>
                                        </div>
                                        <small>{{ $measure->percentage }}%</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $measure->comments ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('measureChart').getContext('2d');
    
    const labels = {!! json_encode($measures->pluck('assessed_at')->map(fn($d) => $d->format('M d, Y'))) !!};
    const data = {!! json_encode($measures->pluck('total_score')) !!};
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '{{ $measureName }}',
                data: data,
                borderColor: '#00897b',
                backgroundColor: 'rgba(0, 137, 123, 0.1)',
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#00897b',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
});
</script>
@endpush

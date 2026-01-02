<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Coach Report') }} - {{ $patient->full_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #00897b; padding-bottom: 20px; }
        .header h1 { color: #00897b; margin: 0; }
        .section { margin-bottom: 30px; }
        .section h3 { color: #00897b; border-bottom: 2px solid #00897b; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #00897b; color: white; }
        .progress-bar { height: 25px; background-color: #e0e0e0; border-radius: 5px; overflow: hidden; }
        .progress-fill { height: 100%; background-color: #4caf50; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        @media print { body { margin: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Athlete Performance Report') }}</h1>
        <h2>{{ $patient->full_name }}</h2>
        <p>{{ __('Generated') }}: {{ now()->format('F d, Y') }}</p>
    </div>

    <!-- Return to Play Progress -->
    <div class="section">
        <h3>{{ __('Return to Play Progress') }}</h3>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $rtpProgress['progress'] }}%;">
                {{ $rtpProgress['progress'] }}%
            </div>
        </div>
        <p><strong>{{ __('Current Phase') }}:</strong> {{ ucfirst(str_replace('_', ' ', $rtpProgress['current_phase'])) }}</p>
        <ul>
            @foreach($rtpProgress['phases'] as $phase)
            <li class="{{ $phase === $rtpProgress['current_phase'] ? 'font-weight-bold' : '' }}">
                {{ ucfirst(str_replace('_', ' ', $phase)) }}
                @if($phase === $rtpProgress['current_phase'])
                    <span class="badge badge-success">{{ __('Current') }}</span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Performance Metrics -->
    @if(count($performanceMetrics) > 0)
    <div class="section">
        <h3>{{ __('Performance Metrics') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Vertical Jump') }} (cm)</th>
                    <th>{{ __('Agility Time') }} (sec)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceMetrics as $metric)
                <tr>
                    <td>{{ $metric['date']->format('M d, Y') }}</td>
                    <td>{{ $metric['vertical_jump'] ?? 'N/A' }}</td>
                    <td>{{ $metric['agility_time'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Treatment Summary -->
    <div class="section">
        <h3>{{ __('Treatment Summary') }}</h3>
        <p><strong>{{ __('Total Episodes') }}:</strong> {{ $episodes->count() }}</p>
        <p><strong>{{ __('Total Sessions') }}:</strong> {{ $episodes->sum(function($e) { return $e->assessments->count(); }) }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #00897b; color: white; border: none; border-radius: 5px; cursor: pointer;">
            {{ __('Print Report') }}
        </button>
    </div>
</body>
</html>


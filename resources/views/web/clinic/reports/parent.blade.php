<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Parent Report') }} - {{ $patient->full_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #ff9800; padding-bottom: 20px; }
        .header h1 { color: #ff9800; margin: 0; }
        .section { margin-bottom: 30px; }
        .section h3 { color: #ff9800; border-bottom: 2px solid #ff9800; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #ff9800; color: white; }
        .milestone { padding: 10px; margin: 5px 0; background-color: #fff3e0; border-left: 4px solid #ff9800; }
        @media print { body { margin: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Child Development Report') }}</h1>
        <h2>{{ $patient->full_name }}</h2>
        <p>{{ __('Generated') }}: {{ now()->format('F d, Y') }}</p>
    </div>

    <!-- Developmental Scores -->
    @if(count($developmentalScores) > 0)
    <div class="section">
        <h3>{{ __('Developmental Assessment Scores') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('GMFM Score') }} (%)</th>
                    <th>{{ __('Peabody Score') }} (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($developmentalScores as $score)
                <tr>
                    <td>{{ $score['date']->format('M d, Y') }}</td>
                    <td>{{ $score['gmfm'] ? number_format($score['gmfm'], 1) . '%' : 'N/A' }}</td>
                    <td>{{ $score['peabody'] ? number_format($score['peabody'], 1) . '%' : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Milestone Progress -->
    @if(count($milestoneProgress) > 0)
    <div class="section">
        <h3>{{ __('Milestone Achievements') }}</h3>
        @foreach($milestoneProgress as $progress)
        <div class="milestone">
            <strong>{{ $progress['date']->format('M d, Y') }}</strong>
            <ul>
                @foreach($progress['milestones'] as $milestone)
                <li>{{ $milestone }}</li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Treatment Summary -->
    <div class="section">
        <h3>{{ __('Treatment Summary') }}</h3>
        <p><strong>{{ __('Total Episodes') }}:</strong> {{ $episodes->count() }}</p>
        <p><strong>{{ __('Total Sessions') }}:</strong> {{ $episodes->sum(function($e) { return $e->assessments->count(); }) }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #ff9800; color: white; border: none; border-radius: 5px; cursor: pointer;">
            {{ __('Print Report') }}
        </button>
    </div>
</body>
</html>


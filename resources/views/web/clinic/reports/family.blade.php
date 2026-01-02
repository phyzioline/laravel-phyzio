<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Family Report') }} - {{ $patient->full_name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #9c27b0; padding-bottom: 20px; }
        .header h1 { color: #9c27b0; margin: 0; }
        .section { margin-bottom: 30px; }
        .section h3 { color: #9c27b0; border-bottom: 2px solid #9c27b0; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #9c27b0; color: white; }
        .risk-high { color: #f44336; font-weight: bold; }
        .risk-moderate { color: #ff9800; font-weight: bold; }
        .risk-low { color: #4caf50; font-weight: bold; }
        @media print { body { margin: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Family Care Report') }}</h1>
        <h2>{{ $patient->full_name }}</h2>
        <p>{{ __('Generated') }}: {{ now()->format('F d, Y') }}</p>
    </div>

    <!-- Fall Risk Assessment -->
    @if($fallRisk['latest'])
    <div class="section">
        <h3>{{ __('Fall Risk Assessment') }}</h3>
        <p><strong>{{ __('Latest Assessment') }}:</strong> {{ $fallRisk['latest']['date']->format('M d, Y') }}</p>
        <p><strong>{{ __('Morse Fall Scale Score') }}:</strong> 
            <span class="risk-{{ strtolower($fallRisk['latest']['risk_level']) }}">
                {{ $fallRisk['latest']['score'] }} ({{ $fallRisk['latest']['risk_level'] }} Risk)
            </span>
        </p>
        
        @if(count($fallRisk['history']) > 1)
        <table>
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Score') }}</th>
                    <th>{{ __('Risk Level') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fallRisk['history'] as $risk)
                <tr>
                    <td>{{ $risk['date']->format('M d, Y') }}</td>
                    <td>{{ $risk['score'] }}</td>
                    <td class="risk-{{ strtolower($risk['risk_level']) }}">{{ $risk['risk_level'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

    <!-- Functional Independence Scores -->
    @if(count($functionalScores) > 0)
    <div class="section">
        <h3>{{ __('Functional Independence Scores') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('TUG Test') }} (sec)</th>
                    <th>{{ __('Berg Balance') }} (0-56)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($functionalScores as $score)
                <tr>
                    <td>{{ $score['date']->format('M d, Y') }}</td>
                    <td>{{ $score['tug'] ?? 'N/A' }}</td>
                    <td>{{ $score['berg_balance'] ?? 'N/A' }}</td>
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
        <button onclick="window.print()" style="padding: 10px 20px; background: #9c27b0; color: white; border: none; border-radius: 5px; cursor: pointer;">
            {{ __('Print Report') }}
        </button>
    </div>
</body>
</html>


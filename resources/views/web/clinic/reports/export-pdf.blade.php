<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Financial Report') }} - {{ $monthName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #00897b;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #00897b;
            margin: 0;
            font-size: 24px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .summary-cell.label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 40%;
        }
        .summary-cell.value {
            text-align: right;
            width: 60%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #00897b;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .profit-positive {
            color: #4caf50;
            font-weight: bold;
        }
        .profit-negative {
            color: #f44336;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $clinic->name ?? 'Clinic' }}</h1>
        <h2>{{ __('Financial Report') }} - {{ $monthName }}</h2>
        <p>{{ __('Generated') }}: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <div class="summary-row">
            <div class="summary-cell label">{{ __('Total Revenue') }}</div>
            <div class="summary-cell value">{{ number_format($monthlyReport['total_revenue'] ?? 0, 2) }} {{ __('EGP') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell label">{{ __('Total Expenses') }}</div>
            <div class="summary-cell value">{{ number_format($monthlyReport['total_expenses'] ?? 0, 2) }} {{ __('EGP') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell label">{{ __('Net Profit / Loss') }}</div>
            <div class="summary-cell value {{ ($monthlyReport['net_profit'] ?? 0) >= 0 ? 'profit-positive' : 'profit-negative' }}">
                {{ number_format($monthlyReport['net_profit'] ?? 0, 2) }} {{ __('EGP') }}
                @if(isset($monthlyReport['profit_margin']))
                    ({{ number_format($monthlyReport['profit_margin'], 1) }}%)
                @endif
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-cell label">{{ __('Outstanding Balances') }}</div>
            <div class="summary-cell value">{{ number_format($monthlyReport['outstanding_balances'] ?? 0, 2) }} {{ __('EGP') }}</div>
        </div>
    </div>

    <!-- Expenses by Category -->
    @if(isset($expenseReports['by_category']) && count($expenseReports['by_category']) > 0)
    <h3>{{ __('Expenses by Category') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Amount') }} ({{ __('EGP') }})</th>
                <th>{{ __('Count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenseReports['by_category'] as $category => $data)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $category)) }}</td>
                <td style="text-align: right;">{{ number_format($data->total ?? 0, 2) }}</td>
                <td style="text-align: center;">{{ $data->count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Top Paying Patients -->
    @if(isset($patientReports['top_paying_patients']) && count($patientReports['top_paying_patients']) > 0)
    <h3>{{ __('Top Paying Patients') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('Patient') }}</th>
                <th>{{ __('Total Paid') }} ({{ __('EGP') }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patientReports['top_paying_patients'] as $patient)
            <tr>
                <td>{{ $patient['name'] }}</td>
                <td style="text-align: right;">{{ number_format($patient['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Outstanding Patients -->
    @if(isset($patientReports['outstanding_patients']) && count($patientReports['outstanding_patients']) > 0)
    <h3>{{ __('Patients with Outstanding Balances') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('Patient') }}</th>
                <th>{{ __('Total Invoiced') }} ({{ __('EGP') }})</th>
                <th>{{ __('Total Paid') }} ({{ __('EGP') }})</th>
                <th>{{ __('Remaining Balance') }} ({{ __('EGP') }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patientReports['outstanding_patients'] as $patient)
            <tr>
                <td>{{ $patient['name'] }}</td>
                <td style="text-align: right;">{{ number_format($patient['total_invoiced'], 2) }}</td>
                <td style="text-align: right;">{{ number_format($patient['total_paid'], 2) }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($patient['remaining_balance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>{{ __('This report was generated by Phyzioline Clinic Management System') }}</p>
        <p>{{ __('For questions, contact your system administrator') }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #00897b; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            {{ __('Print Report') }}
        </button>
    </div>
</body>
</html>


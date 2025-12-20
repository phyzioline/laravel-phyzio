
@extends('dashboard.pages.finance.layout')

@section('finance-content')
<div class="row g-4">
    <div class="col-lg-4 col-md-6">
        <div class="card rounded-2 border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body">
                <p class="text-muted mb-1 font-monospace text-uppercase" style="font-size: 11px;">Total Earnings (Paid)</p>
                <h3 class="mb-0 fw-bold amount-positive">${{ number_format($totalEarnings, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card rounded-2 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body">
                <p class="text-muted mb-1 font-monospace text-uppercase" style="font-size: 11px;">Pending Clearance</p>
                <h3 class="mb-0 fw-bold" style="color: #b18700;">${{ number_format($pendingPayments, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card rounded-2 border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
            <div class="card-body">
                <p class="text-muted mb-1 font-monospace text-uppercase" style="font-size: 11px;">Last Payout</p>
                @if($lastPayout)
                    <h3 class="mb-0 fw-bold text-info">${{ number_format($lastPayout->vendor_earnings, 2) }}</h3>
                    <small class="text-muted" style="font-size: 11px;">{{ $lastPayout->paid_at->format('M d, Y') }}</small>
                @else
                    <h3 class="mb-0 fw-bold text-muted">No payouts yet</h3>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Charts Section --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-2">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Earnings Trend (Last 30 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="earningsChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="p-5 text-center bg-white border rounded">
        <h5 class="text-muted">Detailed Statement View</h5>
        <p>Please switch to the <strong>Transaction View</strong> tab to see detailed history table.</p>
        <a href="{{ route('dashboard.payments.index', ['view' => 'transaction']) }}" class="btn-amazon-primary">Go to Transactions</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('earningsChart').getContext('2d');
        
        // Mock data for visual purpose - in real app, pass this from controller
        const dates = Array.from({length: 10}, (_, i) => {
            const d = new Date();
            d.setDate(d.getDate() - (9 - i));
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        
        const data = Array.from({length: 10}, () => Math.floor(Math.random() * 500) + 50);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Earnings ($)',
                    data: data,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

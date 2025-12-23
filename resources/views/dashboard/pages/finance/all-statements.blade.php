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

{{-- All Statements Table --}}
<div class="card border-0 shadow-sm rounded-2 mt-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">All Payment Statements</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Statement Period</th>
                        <th>Total Earnings</th>
                        <th>Commission</th>
                        <th>Net Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Group payments by month
                        $groupedPayments = $payments->groupBy(function($payment) {
                            return $payment->created_at->format('Y-m');
                        });
                    @endphp
                    @forelse($groupedPayments as $month => $monthPayments)
                    @php
                        $totalEarned = $monthPayments->sum('vendor_earnings');
                        $totalCommission = $monthPayments->sum('commission_amount');
                        $paidCount = $monthPayments->where('status', 'paid')->count();
                        $pendingCount = $monthPayments->where('status', 'pending')->count();
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</strong>
                            <br>
                            <small class="text-muted">{{ $monthPayments->count() }} transactions</small>
                        </td>
                        <td class="fw-bold text-success">${{ number_format($totalEarned, 2) }}</td>
                        <td class="text-danger">-${{ number_format($totalCommission, 2) }}</td>
                        <td class="fw-bold">${{ number_format($totalEarned, 2) }}</td>
                        <td>
                            @if($pendingCount > 0)
                                <span class="badge bg-warning rounded-pill">Pending ({{ $pendingCount }})</span>
                            @else
                                <span class="badge bg-success rounded-pill">Paid</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dashboard.payments.index', ['view' => 'transaction', 'date_from' => $month . '-01', 'date_to' => \Carbon\Carbon::parse($month . '-01')->endOfMonth()->format('Y-m-d')]) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            No statements found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


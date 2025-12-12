@extends('dashboard.layouts.app')

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payments & Earnings</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total Earnings (Paid)</p>
                                <h3 class="mb-0 fw-bold text-success">${{ number_format($totalEarnings, 2) }}</h3>
                            </div>
                            <div class="p-3 rounded-circle bg-light-success text-success">
                                <i class="fa fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Pending Clearance</p>
                                <h3 class="mb-0 fw-bold text-warning">${{ number_format($pendingPayments, 2) }}</h3>
                            </div>
                            <div class="p-3 rounded-circle bg-light-warning text-warning">
                                <i class="fa fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Last Payout</p>
                                @if($lastPayout)
                                    <h5 class="mb-0 fw-bold">${{ number_format($lastPayout->vendor_earnings, 2) }}</h5>
                                    <small class="text-muted">{{ $lastPayout->paid_at->format('M d, Y') }}</small>
                                @else
                                    <h5 class="mb-0 fw-bold">No payouts yet</h5>
                                @endif
                            </div>
                            <div class="p-3 rounded-circle bg-light-info text-info">
                                <i class="fa fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="card rounded-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Transaction History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Commission ({{ $payments->first()->commission_rate ?? '15' }}%)</th>
                                <th>Net Earning</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <strong>{{ $payment->payment_reference ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->orderItem && $payment->orderItem->product)
                                            <span class="ms-2">{{ Str::limit($payment->orderItem->product->product_name_en, 30) }}</span>
                                        @else
                                            <span class="text-muted ms-2">Product Deleted</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $payment->quantity }}</td>
                                <td>${{ number_format($payment->subtotal, 2) }}</td>
                                <td class="text-danger">-${{ number_format($payment->commission_amount, 2) }}</td>
                                <td class="fw-bold text-success">+${{ number_format($payment->vendor_earnings, 2) }}</td>
                                <td>
                                    @if($payment->status == 'paid')
                                        <span class="badge bg-success rounded-pill">Paid</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge bg-warning rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa fa-receipt fa-3x mb-3 opacity-50"></i>
                                        <p>No transactions found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('dashboard.layouts.app')

@section('content')
<main class="page-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">My Wallet</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                    </ol>
                </nav>
            </div>
        </div>

    <div class="row">
        {{--Wallet Balance Cards --}}
        <div class="col-lg-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Available Balance</h6>
                    <h3 class="mb-0">${{ number_format($walletSummary['available_balance'], 2) }}</h3>
                    <p class="mb-0 small mt-2">Ready for withdrawal</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Pending Balance</h6>
                    <h3 class="mb-0">${{ number_format($walletSummary['pending_balance'], 2) }}</h3>
                    <p class="mb-0 small mt-2">Awaiting settlement (14 days)</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Total Earned</h6>
                    <h3 class="mb-0">${{ number_format($walletSummary['total_earned'], 2) }}</h3>
                    <p class="mb-0 small mt-2">All-time earnings</p>
                </div>
            </div>
        </div>
    </div>

    @if($walletSummary['on_hold_balance'] > 0)
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-triangle me-2"></i>
        <strong>On Hold:</strong> ${{ number_format($walletSummary['on_hold_balance'], 2) }} is currently on hold due to pending disputes or returns.
    </div>
    @endif

    {{-- Request Payout --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Request Payout</h5>
                    
                    <form action="{{ route('vendor.wallet.payout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="amount" class="form-control" min="100" step="0.01" max="{{ $walletSummary['available_balance'] }}" required placeholder="Minimum: $100">
                            </div>
                            <small class="text-muted">Available: ${{ number_format($walletSummary['available_balance'], 2) }}</small>
                            @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payout Method <span class="text-danger">*</span></label>
                            <select name="payout_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="payoneer">Payoneer</option>
                                <option value="wise">Wise (TransferWise)</option>
                            </select>
                            @error('payout_method')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" {{ $walletSummary['available_balance'] < 100 ? 'disabled' : '' }}>
                            <i class="fa fa-paper-plane me-2"></i>Request Payout
                        </button>
                        
                        @if($walletSummary['available_balance'] < 100)
                        <small class="text-muted d-block mt-2 text-center">Minimum payout amount is $100</small>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Upcoming Settlements --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Upcoming Settlements</h5>
                    
                    @if($walletSummary['upcoming_settlements']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Amount</th>
                                    <th>Available On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($walletSummary['upcoming_settlements']->take(5) as $settlement)
                                <tr>
                                    <td>#{{ $settlement->order_id }}</td>
                                    <td>${{ number_format($settlement->vendor_earnings, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($settlement->hold_until)->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle me-2"></i>No pending settlements.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Payout History --}}
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Payout History</h5>
            
            @if($payoutHistory->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>Requested</th>
                            <th>Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payoutHistory as $payout)
                        <tr>
                            <td><strong>#{{ $payout->id }}</strong></td>
                            <td>${{ number_format($payout->amount, 2) }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payout->payout_method)) }}</span></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'paid' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                    $color = $statusColors[$payout->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($payout->status) }}</span>
                            </td>
                            <td>
                                @if($payout->reference_number)
                                    <code>{{ $payout->reference_number }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($payout->paid_at)
                                    {{ $payout->paid_at->format('M d, Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info mb-0">
                <i class="fa fa-info-circle me-2"></i>No payout requests yet.
            </div>
            @endif
        </div>
    </div>
    </div>
</main>
@endsection
